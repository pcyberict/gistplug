from flask import Flask, render_template, request, redirect, url_for, session, flash, jsonify
import json, os, time
from urllib.parse import quote
from collections import defaultdict
from flask_sqlalchemy import SQLAlchemy
from flask_bcrypt import Bcrypt
from models import db, bcrypt, User

app = Flask(__name__)
app.secret_key = 'your_very_strong_secret_key'

# Database Configuration
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///users.db'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db.init_app(app)
bcrypt.init_app(app)

# File paths
STREAMS_FILE = os.path.join(os.path.dirname(__file__), 'streams.json')
CHAT_FILE = os.path.join(os.path.dirname(__file__), 'chat.json')

# Admin credentials
ADMIN_USERNAME = 'admin'
ADMIN_PASSWORD = 'Pcyber50@'

# Rate limiting
RATE_LIMIT = defaultdict(list)
MAX_MESSAGES_PER_MINUTE = 5

# Profanity filter
PROFANITY_WORDS = {'sex', 'blood', 'bitch'}

# Pagination
STREAMS_PER_PAGE = 10

def load_json(file):
    if not os.path.exists(file):
        return []
    with open(file, 'r') as f:
        return json.load(f)

def save_json(file, data):
    with open(file, 'w') as f:
        json.dump(data, f, indent=4)

def contains_profanity(text):
    return any(word in text.lower() for word in PROFANITY_WORDS)

def clean_message(text):
    words = text.split()
    return ' '.join('*' * len(word) if word.lower() in PROFANITY_WORDS else word for word in words)

@app.template_filter('url_encode')
def url_encode_filter(s):
    return quote(s)

def get_client_ip():
    if request.environ.get('HTTP_X_FORWARDED_FOR'):
        return request.environ['HTTP_X_FORWARDED_FOR'].split(',')[0].strip()
    return request.remote_addr or 'untracked'

# ===================== User Authentication ===================== #

@app.route('/signup', methods=['GET', 'POST'])
def signup():
    if request.method == 'POST':
        username = request.form['username']
        email = request.form['email']
        raw_password = request.form['password']

        if User.query.filter((User.username == username) | (User.email == email)).first():
            flash("Username or email already exists.", "warning")
            return redirect(url_for('signup'))

        hashed_password = User.hash_password(raw_password)
        new_user = User(username=username, email=email, password=hashed_password)
        db.session.add(new_user)
        db.session.commit()

        flash("Signup successful. Please log in.", "success")
        return redirect(url_for('login'))

    return render_template('signup.html')

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        email = request.form['email']
        password = request.form['password']
        user = User.query.filter_by(email=email).first()

        if user and user.check_password(password):
            session['user_id'] = user.id
            session['username'] = user.username
            flash(f"Welcome back, {user.username}!", 'success')
            return redirect(url_for('dashboard'))

        flash('Invalid email or password', 'danger')
    return render_template('login.html')

@app.route('/dashboard')
def dashboard():
    if 'user_id' not in session:
        flash("You must be logged in to view the dashboard.", "warning")
        return redirect(url_for('login'))

    return render_template('dashboard.html', username=session['username'])

@app.route('/logout-user')
def logout_user():
    session.pop('user_id', None)
    session.pop('username', None)
    flash("Logged out successfully", "info")
    return redirect(url_for('login'))

# ===================== Public Routes ===================== #

@app.route('/')
def index():
    streams = load_json(STREAMS_FILE)
    initial_streams = streams[:6]
    return render_template('index.html', streams=initial_streams, selected_category=None)

@app.route('/load_more_streams')
def load_more_streams():
    try:
        offset = int(request.args.get('offset', 0))
        limit = int(request.args.get('limit', 6))
        all_streams = load_json(STREAMS_FILE)
        sliced = all_streams[offset:offset + limit]
        has_more = offset + limit < len(all_streams)

        return jsonify({
            'streams': [
                {
                    'title': s['title'],
                    'platform': s['platform'],
                    'video_id': s['video_id'],
                    'category': s['category']
                } for s in sliced
            ],
            'has_more': has_more
        })
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/category/<category_name>')
def category(category_name):
    streams = load_json(STREAMS_FILE)
    filtered_streams = [s for s in streams if s.get('category', '').lower() == category_name.lower()]
    return render_template('index.html', streams=filtered_streams, selected_category=category_name)

@app.route('/chat', methods=['POST'])
def chat():
    user = request.form['user']
    message = request.form['message']
    ip = get_client_ip()
    now = time.time()

    RATE_LIMIT[ip] = [t for t in RATE_LIMIT[ip] if now - t < 60]
    if len(RATE_LIMIT[ip]) >= MAX_MESSAGES_PER_MINUTE:
        return "You're sending messages too fast. Please wait.", 429

    if contains_profanity(message):
        message = clean_message(message)

    chat = load_json(CHAT_FILE)
    chat.append({'user': user, 'message': message})
    save_json(CHAT_FILE, chat)
    RATE_LIMIT[ip].append(now)

    return redirect(url_for('index'))

# ===================== Admin Authentication ===================== #

@app.route('/admin-login', methods=['GET', 'POST'])
def admin_login():
    if request.method == 'POST':
        if request.form['username'] == ADMIN_USERNAME and request.form['password'] == ADMIN_PASSWORD:
            session['admin'] = True
            return redirect(url_for('admin'))
        else:
            flash('Invalid admin username or password', 'danger')
    return render_template('admin_login.html')

@app.route('/logout')
def logout():
    session.pop('admin', None)
    flash('You have been logged out.', 'info')
    return redirect(url_for('admin_login'))

# ===================== Admin Panel ===================== #

@app.route('/admin', methods=['GET', 'POST'])
def admin():
    if not session.get('admin'):
        return redirect(url_for('admin_login'))

    if request.method == 'POST':
        title = request.form['title']
        platform = request.form['platform']
        video_id = request.form['video_id']
        category = request.form['category']

        streams = load_json(STREAMS_FILE)
        streams.insert(0, {
            'title': title,
            'platform': platform,
            'video_id': video_id,
            'category': category
        })
        save_json(STREAMS_FILE, streams)
        return redirect(url_for('admin'))

    streams = load_json(STREAMS_FILE)
    search_query = request.args.get('search', '').strip().lower()

    if search_query:
        streams = [s for s in streams if search_query in s.get('title', '').lower() or
                   search_query in s.get('platform', '').lower() or
                   search_query in s.get('category', '').lower()]

    page = request.args.get('page', 1, type=int)
    total_pages = (len(streams) + STREAMS_PER_PAGE - 1) // STREAMS_PER_PAGE
    start = (page - 1) * STREAMS_PER_PAGE
    end = start + STREAMS_PER_PAGE
    paginated_streams = streams[start:end]

    return render_template('admin.html', streams=paginated_streams, page=page, total_pages=total_pages, search=search_query)

@app.route('/edit/<int:index>', methods=['GET', 'POST'])
def edit(index):
    if not session.get('admin'):
        return redirect(url_for('admin_login'))

    streams = load_json(STREAMS_FILE)
    if not (0 <= index < len(streams)):
        return "Stream not found", 404

    if request.method == 'POST':
        streams[index]['title'] = request.form['title']
        streams[index]['platform'] = request.form['platform']
        streams[index]['video_id'] = request.form['video_id']
        streams[index]['category'] = request.form['category']
        save_json(STREAMS_FILE, streams)
        return redirect(url_for('admin'))

    stream = streams[index]
    return render_template('edit.html', stream=stream, index=index)

@app.route('/delete/<int:index>', methods=['POST'])
def delete(index):
    if not session.get('admin'):
        return redirect(url_for('admin_login'))

    streams = load_json(STREAMS_FILE)
    if 0 <= index < len(streams):
        del streams[index]
        save_json(STREAMS_FILE, streams)
    return redirect(url_for('admin'))

@app.route('/stream/<int:stream_id>')
def stream(stream_id):
    streams = load_json(STREAMS_FILE)
    if 0 <= stream_id < len(streams):
        return render_template('stream.html', stream=streams[stream_id])
    return "Stream not found", 404

# ===================== Run App ===================== #

if __name__ == '__main__':
    with app.app_context():
        db.create_all()
   
