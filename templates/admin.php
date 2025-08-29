<?php
// Check if user is admin
if (!isset($_SESSION['admin'])) {
    header('Location: /admin-login');
    exit;
}

// Load streams and handle pagination/search
$streams = StreamManager::loadStreams();
$search_query = $_GET['search'] ?? '';

if ($search_query) {
    $search_query = strtolower(trim($search_query));
    $streams = array_filter($streams, function($stream) use ($search_query) {
        return strpos(strtolower($stream['title'] ?? ''), $search_query) !== false ||
               strpos(strtolower($stream['platform'] ?? ''), $search_query) !== false ||
               strpos(strtolower($stream['category'] ?? ''), $search_query) !== false;
    });
}

$page = (int)($_GET['page'] ?? 1);
$total_streams = count($streams);
$total_pages = ceil($total_streams / Config::$streams_per_page);
$start = ($page - 1) * Config::$streams_per_page;
$paginated_streams = array_slice($streams, $start, Config::$streams_per_page);

// Get stats
$categories = array_unique(array_column(StreamManager::loadStreams(), 'category'));
$chat_messages = StreamManager::loadChat();
$total_users = count(glob('instance/users.db')) ? 1 : 0; // Simple user count
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>PCYBER TV Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <style>
    :root {
      --primary-bg: #01002c;
      --sidebar-bg: #1a1e2d;
      --card-bg: rgba(255, 255, 255, 0.05);
      --text-color: #f1f1f1;
      --text-muted: #8b9dc3;
      --accent-red: #ff4757;
      --accent-green: #2ed573;
      --accent-yellow: #ffa502;
      --accent-blue: #4dabf7;
      --accent-purple: #a55eea;
      --border-color: rgba(255, 255, 255, 0.1);
      --hover-bg: rgba(255, 255, 255, 0.08);
    }

    body.light-mode {
      --primary-bg: #f8f9fa;
      --sidebar-bg: #ffffff;
      --card-bg: #ffffff;
      --text-color: #212529;
      --text-muted: #6c757d;
      --border-color: #dee2e6;
      --hover-bg: #f8f9fa;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background-color: var(--primary-bg);
      color: var(--text-color);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      overflow-x: hidden;
    }

    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      height: 100vh;
      width: 250px;
      background-color: var(--sidebar-bg);
      transform: translateX(-100%);
      transition: transform 0.3s ease;
      z-index: 1000;
      border-right: 1px solid var(--border-color);
    }

    .sidebar.active {
      transform: translateX(0);
    }

    .sidebar-header {
      padding: 1rem;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .sidebar-header .logo {
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, var(--accent-red), var(--accent-purple));
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
    }

    .sidebar-nav {
      padding: 1rem 0;
    }

    .nav-item {
      margin: 0.25rem 0;
    }

    .nav-link {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.75rem 1rem;
      color: var(--text-muted);
      text-decoration: none;
      transition: all 0.3s ease;
      border-radius: 0;
      margin: 0 0.5rem;
      border-radius: 8px;
    }

    .nav-link:hover,
    .nav-link.active {
      background-color: var(--accent-red);
      color: white;
    }

    .nav-link i {
      width: 20px;
      text-align: center;
    }

    .main-content {
      margin-left: 0;
      min-height: 100vh;
      transition: margin-left 0.3s ease;
    }

    .main-content.shifted {
      margin-left: 250px;
    }

    .header {
      background-color: var(--sidebar-bg);
      padding: 1rem;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
    }

    .menu-toggle {
      background: none;
      border: none;
      color: var(--text-color);
      font-size: 1.5rem;
      cursor: pointer;
      padding: 0.5rem;
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }

    .menu-toggle:hover {
      background-color: var(--hover-bg);
    }

    .header-title {
      flex: 1;
      font-size: 1.5rem;
      font-weight: 600;
      margin-left: 1rem;
    }

    .header-actions {
      display: flex;
      gap: 0.5rem;
      align-items: center;
    }

    .theme-toggle,
    .logout-btn {
      background: none;
      border: none;
      color: var(--text-color);
      font-size: 1.2rem;
      cursor: pointer;
      padding: 0.5rem;
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }

    .theme-toggle:hover,
    .logout-btn:hover {
      background-color: var(--hover-bg);
    }

    .content {
      padding: 2rem;
    }

    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: var(--card-bg);
      border-radius: 16px;
      padding: 1.5rem;
      border: 1px solid var(--border-color);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .stat-number {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .stat-label {
      color: var(--text-muted);
      font-size: 0.9rem;
    }

    .red { color: var(--accent-red); }
    .green { color: var(--accent-green); }
    .yellow { color: var(--accent-yellow); }
    .blue { color: var(--accent-blue); }
    .purple { color: var(--accent-purple); }

    .card {
      background: var(--card-bg);
      color: var(--text-color);
      border: 1px solid var(--border-color);
      border-radius: 16px;
      padding: 1.5rem;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control,
    .form-select {
      background-color: var(--card-bg);
      color: var(--text-color);
      border: 1px solid var(--border-color);
      border-radius: 8px;
    }

    .form-control::placeholder {
      color: var(--text-muted);
      opacity: 0.8;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--accent-red);
      box-shadow: 0 0 0 0.2rem rgba(255, 71, 87, 0.25);
      background-color: var(--card-bg);
      color: var(--text-color);
    }

    .list-group-item {
      background-color: var(--card-bg);
      color: var(--text-color);
      border: 1px solid var(--border-color);
      border-radius: 8px !important;
      margin-bottom: 0.5rem;
    }

    .btn-primary {
      background-color: var(--accent-red);
      border-color: var(--accent-red);
    }

    .btn-primary:hover {
      background-color: #ff3742;
      border-color: #ff3742;
    }

    .btn-success {
      background-color: var(--accent-green);
      border-color: var(--accent-green);
    }

    .btn-success:hover {
      background-color: #26d062;
      border-color: #26d062;
    }

    .btn-outline-primary {
      border-color: var(--accent-red);
      color: var(--accent-red);
    }

    .btn-outline-primary:hover {
      background-color: var(--accent-red);
      border-color: var(--accent-red);
      color: white;
    }

    .btn-outline-danger {
      border-color: var(--accent-red);
      color: var(--accent-red);
    }

    .btn-outline-danger:hover {
      background-color: var(--accent-red);
      border-color: var(--accent-red);
      color: white;
    }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 999;
      display: none;
    }

    .overlay.active {
      display: block;
    }

    .page-link {
      background-color: var(--card-bg);
      border-color: var(--border-color);
      color: var(--text-color);
    }

    .page-link:hover {
      background-color: var(--hover-bg);
      border-color: var(--border-color);
      color: var(--text-color);
    }

    .page-item.active .page-link {
      background-color: var(--accent-red);
      border-color: var(--accent-red);
      color: white;
    }

    .alert-success {
      background-color: rgba(46, 213, 115, 0.1);
      border-color: var(--accent-green);
      color: var(--accent-green);
    }

    .alert-danger {
      background-color: rgba(255, 71, 87, 0.1);
      border-color: var(--accent-red);
      color: var(--accent-red);
    }

    .alert-info {
      background-color: rgba(55, 66, 250, 0.1);
      border-color: var(--accent-blue);
      color: var(--accent-blue);
    }

    .stream-item-title {
      flex: 1;
      min-width: 0;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .content-section {
      display: none;
    }

    .content-section.active {
      display: block;
    }

    .table th, .table td {
      border-color: var(--border-color);
      color: var(--text-color);
    }

    .table-hover tbody tr:hover {
      background-color: rgba(255, 71, 87, 0.1);
    }

    @media (min-width: 768px) {
      .sidebar {
        transform: translateX(0);
      }
      
      .main-content {
        margin-left: 250px;
      }
      
      .menu-toggle {
        display: none;
      }
    }

    @media (max-width: 576px) {
      .dashboard-grid {
        grid-template-columns: 1fr;
      }
      
      .content {
        padding: 1rem;
      }
      
      .stream-item-title {
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>
  <div class="overlay" id="overlay"></div>
  
  <!-- Sidebar -->
  <nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <div class="logo">
        <i class="fas fa-tv"></i>
      </div>
      <div>
        <div style="font-weight: 600;">PCYBER TV</div>
        <div style="font-size: 0.8rem; color: var(--text-muted);">STREAMING PLATFORM</div>
      </div>
    </div>
    
    <div class="sidebar-nav">
      <div class="nav-item">
        <a href="#" class="nav-link active" data-section="dashboard">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="#" class="nav-link" data-section="streams">
          <i class="fas fa-tv"></i>
          <span>Live Streams</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="#" class="nav-link" data-section="categories">
          <i class="fas fa-folder"></i>
          <span>Categories</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="#" class="nav-link" data-section="chat">
          <i class="fas fa-comments"></i>
          <span>Chat Messages</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="#" class="nav-link" data-section="news">
          <i class="fas fa-newspaper"></i>
          <span>News Management</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="#" class="nav-link" data-section="users">
          <i class="fas fa-users"></i>
          <span>Users</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="/" class="nav-link">
          <i class="fas fa-home"></i>
          <span>View Site</span>
        </a>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <!-- Header -->
    <div class="header">
      <button class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
      </button>
      <div class="header-title" id="headerTitle">Dashboard</div>
      <div class="header-actions">
        <button class="theme-toggle" id="themeToggle" title="Toggle Light/Dark">
          <i class="fas fa-moon"></i>
        </button>
        <a href="/logout" class="logout-btn" title="Logout">
          <i class="fas fa-sign-out-alt"></i>
        </a>
      </div>
    </div>

    <!-- Content Area -->
    <div class="content">
      <!-- Dashboard Section -->
      <div id="dashboard-section" class="content-section active">
        <div class="dashboard-grid">
          <div class="stat-card">
            <div class="stat-number red"><?php echo count($categories); ?></div>
            <div class="stat-label">Categories</div>
          </div>
          <div class="stat-card">
            <div class="stat-number green"><?php echo count(StreamManager::loadStreams()); ?></div>
            <div class="stat-label">Total Streams</div>
          </div>
          <div class="stat-card">
            <div class="stat-number yellow"><?php echo count($chat_messages); ?></div>
            <div class="stat-label">Chat Messages</div>
          </div>
          <div class="stat-card">
            <div class="stat-number blue"><?php echo $total_users; ?></div>
            <div class="stat-label">Registered Users</div>
          </div>
          <div class="stat-card">
            <div class="stat-number purple">
              <i class="fas fa-play-circle"></i>
            </div>
            <div class="stat-label">Live TV Active</div>
          </div>
          <div class="stat-card">
            <div class="stat-number blue">
              <i class="fas fa-shield-alt"></i>
            </div>
            <div class="stat-label">Admin Panel</div>
          </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mt-4">
          <div class="col-md-4">
            <div class="card">
              <h5 class="mb-3">Latest Streams</h5>
              <p class="text-muted mb-3">Recently Added</p>
              <div class="news-list">
                <?php 
                $recent_streams = array_slice(StreamManager::loadStreams(), 0, 3);
                foreach ($recent_streams as $stream): 
                ?>
                <div class="news-item mb-2 p-2" style="background: rgba(255, 71, 87, 0.1); border-radius: 8px;">
                  <div class="d-flex justify-content-between align-items-center">
                    <span style="font-size: 0.9rem;"><?php echo htmlspecialchars(substr($stream['title'], 0, 25)) . '...'; ?></span>
                    <span class="badge" style="background: var(--accent-red); color: white;"><?php echo $stream['platform']; ?></span>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <h5 class="mb-3">Active Categories</h5>
              <p class="text-muted mb-3">Stream Categories</p>
              <div class="news-list">
                <?php foreach (array_slice($categories, 0, 3) as $category): ?>
                <div class="news-item mb-2 p-2" style="background: rgba(46, 213, 115, 0.1); border-radius: 8px;">
                  <div class="d-flex justify-content-between align-items-center">
                    <span style="font-size: 0.9rem;"><?php echo htmlspecialchars($category); ?></span>
                    <span class="badge" style="background: var(--accent-green); color: white;">Active</span>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <h5 class="mb-3">Recent Chat</h5>
              <p class="text-muted mb-3">Latest Messages</p>
              <div class="news-list">
                <?php 
                $recent_chat = array_slice($chat_messages, -3);
                foreach ($recent_chat as $message): 
                ?>
                <div class="news-item mb-2 p-2" style="background: rgba(255, 165, 2, 0.1); border-radius: 8px;">
                  <div class="d-flex justify-content-between align-items-center">
                    <span style="font-size: 0.9rem;"><?php echo htmlspecialchars(substr($message['message'], 0, 20)) . '...'; ?></span>
                    <span class="badge" style="background: var(--accent-yellow); color: white;"><?php echo htmlspecialchars($message['user']); ?></span>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Live Streams Management Section -->
      <div id="streams-section" class="content-section">
        <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type'] === 'success' ? 'success' : ($_SESSION['message_type'] === 'danger' ? 'danger' : 'info'); ?> mb-4">
          <i class="fas fa-info-circle me-2"></i>
          <?php echo htmlspecialchars($_SESSION['message']); ?>
        </div>
        <?php 
        unset($_SESSION['message']); 
        unset($_SESSION['message_type']); 
        endif; 
        ?>

        <!-- Search Bar -->
        <div class="mb-4 d-flex gap-2 align-items-center justify-content-center flex-wrap">
          <form method="GET" action="/admin" class="d-flex gap-2 align-items-center">
            <input
              type="text"
              name="search"
              class="form-control"
              style="max-width: 400px;"
              placeholder="Search by title, platform, or category"
              value="<?php echo htmlspecialchars($search_query); ?>"
            />
            <button type="submit" class="btn btn-outline-primary">
              <i class="fas fa-search"></i> Search
            </button>
            <?php if ($search_query): ?>
            <a href="/admin" class="btn btn-outline-danger">
              <i class="fas fa-times"></i> Clear
            </a>
            <?php endif; ?>
          </form>
        </div>

        <div class="row">
          <div class="col-md-4">
            <div class="card">
              <h4 class="mb-3">
                <i class="fas fa-plus me-2"></i>
                Add New Stream
              </h4>
              <form method="POST" action="/admin">
                <div class="mb-3">
                  <label for="title" class="form-label">Stream Title</label>
                  <input type="text" class="form-control" id="title" name="title" required placeholder="Enter stream title">
                </div>
                <div class="mb-3">
                  <label for="platform" class="form-label">Platform</label>
                  <select class="form-select" id="platform" name="platform" required>
                    <option value="YouTube">YouTube</option>
                    <option value="Facebook">Facebook</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="video_id" class="form-label">Video ID/URL</label>
                  <input type="text" class="form-control" id="video_id" name="video_id" required placeholder="Video ID or full URL">
                  <div class="form-text" style="color: var(--text-muted);">For Facebook: Enter full video URL</div>
                </div>
                <div class="mb-3">
                  <label for="category" class="form-label">Category</label>
                  <input type="text" class="form-control" id="category" name="category" required placeholder="e.g. Football, Basketball">
                </div>
                <button type="submit" class="btn btn-success w-100">
                  <i class="fas fa-plus me-2"></i>
                  Add Stream
                </button>
              </form>
            </div>
          </div>
          
          <div class="col-md-8">
            <div class="card">
              <h4 class="mb-3">
                <i class="fas fa-list me-2"></i>
                Manage Streams (<?php echo $total_streams; ?> total)
              </h4>
              
              <?php if (!empty($paginated_streams)): ?>
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Stream Details</th>
                        <th>Platform</th>
                        <th>Category</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($paginated_streams as $index => $stream): ?>
                        <tr>
                          <td>
                            <div class="d-flex align-items-center">
                              <div class="me-2" style="width: 40px; height: 30px; background: linear-gradient(45deg, #ff6b35, #f7931e); border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-play" style="color: white; font-size: 12px;"></i>
                              </div>
                              <div>
                                <div style="font-weight: 600;"><?php echo htmlspecialchars($stream['title']); ?></div>
                                <small style="color: var(--text-muted);"><?php echo htmlspecialchars($stream['video_id']); ?></small>
                              </div>
                            </div>
                          </td>
                          <td>
                            <span class="badge" style="background: var(--accent-blue); color: white;"><?php echo htmlspecialchars($stream['platform']); ?></span>
                          </td>
                          <td>
                            <span class="badge" style="background: var(--accent-purple); color: white;"><?php echo htmlspecialchars($stream['category']); ?></span>
                          </td>
                          <td>
                            <div class="btn-group" role="group">
                              <a href="/edit/<?php echo ($start + $index); ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                              </a>
                              <form method="POST" action="/delete/<?php echo ($start + $index); ?>" style="display: inline;">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this stream?')">
                                  <i class="fas fa-trash"></i> Delete
                                </button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <nav class="mt-4">
                  <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                      <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="/admin?page=<?php echo $i; ?><?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : ''; ?>">
                          <?php echo $i; ?>
                        </a>
                      </li>
                    <?php endfor; ?>
                  </ul>
                </nav>
                <?php endif; ?>
              <?php else: ?>
                <div class="text-center py-5">
                  <i class="fas fa-tv" style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                  <h5 style="color: var(--text-muted);">No streams found</h5>
                  <p style="color: var(--text-muted);">Add a new stream to get started</p>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Categories Section -->
      <div id="categories-section" class="content-section">
        <div class="card">
          <h4 class="mb-3">
            <i class="fas fa-folder me-2"></i>
            Stream Categories
          </h4>
          <div class="row">
            <?php foreach ($categories as $category): ?>
            <div class="col-md-3 mb-3">
              <div class="stat-card text-center">
                <div class="stat-number green">
                  <?php 
                  $cat_count = count(array_filter(StreamManager::loadStreams(), function($s) use ($category) {
                      return $s['category'] === $category;
                  }));
                  echo $cat_count;
                  ?>
                </div>
                <div class="stat-label"><?php echo htmlspecialchars($category); ?></div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Chat Section -->
      <div id="chat-section" class="content-section">
        <div class="card">
          <h4 class="mb-3">
            <i class="fas fa-comments me-2"></i>
            Recent Chat Messages
          </h4>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Message</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach (array_slice($chat_messages, -10) as $message): ?>
                <tr>
                  <td>
                    <span class="badge" style="background: var(--accent-blue); color: white;">
                      <?php echo htmlspecialchars($message['user']); ?>
                    </span>
                  </td>
                  <td><?php echo htmlspecialchars($message['message']); ?></td>
                  <td>
                    <button class="btn btn-sm btn-outline-danger">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Users Section -->
      <div id="users-section" class="content-section">
        <div class="card">
          <h4 class="mb-3">
            <i class="fas fa-users me-2"></i>
            User Management
          </h4>
          <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            User management features can be expanded here. Currently showing basic user statistics.
          </div>
          <div class="stat-card text-center">
            <div class="stat-number blue"><?php echo $total_users; ?></div>
            <div class="stat-label">Total Registered Users</div>
          </div>
        </div>
      </div>

      <!-- News Management Section -->
      <div id="news-section" class="content-section">
        <div class="row">
          <div class="col-12 mb-4">
            <div class="card">
              <h4 class="mb-3">
                <i class="fas fa-newspaper me-2"></i>
                News Management
              </h4>
              
              <!-- Tab Navigation -->
              <ul class="nav nav-tabs" id="newsTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="add-news-tab" data-bs-toggle="tab" data-bs-target="#add-news" type="button" role="tab">
                    <i class="fas fa-plus me-2"></i>Add News Article
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="manage-news-tab" data-bs-toggle="tab" data-bs-target="#manage-news" type="button" role="tab">
                    <i class="fas fa-list me-2"></i>Manage Articles
                  </button>
                </li>
              </ul>
              
              <div class="tab-content mt-3" id="newsTabContent">
                <!-- Add News Article Tab -->
                <div class="tab-pane fade show active" id="add-news" role="tabpanel">
                  <form method="POST" action="/admin" enctype="multipart/form-data" id="newsForm">
                    <input type="hidden" name="action" value="add_news">
                    
                    <div class="row">
                      <div class="col-lg-8">
                        <!-- Article Title -->
                        <div class="mb-3">
                          <label for="news_title" class="form-label">Article Title *</label>
                          <input type="text" class="form-control" id="news_title" name="news_title" required placeholder="Enter compelling article title">
                        </div>
                        
                        <!-- Article Slug -->
                        <div class="mb-3">
                          <label for="news_slug" class="form-label">URL Slug</label>
                          <input type="text" class="form-control" id="news_slug" name="news_slug" placeholder="auto-generated-from-title">
                          <div class="form-text">Leave empty to auto-generate from title</div>
                        </div>
                        
                        <!-- Article Excerpt -->
                        <div class="mb-3">
                          <label for="news_excerpt" class="form-label">Article Excerpt</label>
                          <textarea class="form-control" id="news_excerpt" name="news_excerpt" rows="3" placeholder="Brief summary for previews and SEO"></textarea>
                        </div>
                        
                        <!-- CKEditor Content -->
                        <div class="mb-3">
                          <label for="news_content" class="form-label">Article Content *</label>
                          <textarea class="form-control" id="news_content" name="news_content" rows="15" required></textarea>
                        </div>
                        
                        <!-- SEO Settings -->
                        <div class="card mb-3" style="background: var(--card-bg); border: 1px solid var(--border-color);">
                          <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-search me-2"></i>SEO Settings</h6>
                          </div>
                          <div class="card-body">
                            <div class="mb-3">
                              <label for="seo_title" class="form-label">SEO Title</label>
                              <input type="text" class="form-control" id="seo_title" name="seo_title" placeholder="SEO optimized title (60 chars max)">
                            </div>
                            <div class="mb-3">
                              <label for="seo_description" class="form-label">Meta Description</label>
                              <textarea class="form-control" id="seo_description" name="seo_description" rows="2" placeholder="SEO meta description (160 chars max)"></textarea>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-lg-4">
                        <!-- Publishing Controls -->
                        <div class="card mb-3" style="background: var(--card-bg); border: 1px solid var(--border-color);">
                          <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Publishing</h6>
                          </div>
                          <div class="card-body">
                            <div class="mb-3">
                              <label for="news_status" class="form-label">Status</label>
                              <select class="form-select" id="news_status" name="news_status">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="scheduled">Scheduled</option>
                              </select>
                            </div>
                            
                            <div class="mb-3" id="publish_date_group" style="display: none;">
                              <label for="publish_date" class="form-label">Publish Date & Time</label>
                              <input type="datetime-local" class="form-control" id="publish_date" name="publish_date">
                            </div>
                            
                            <div class="mb-3">
                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="featured_article" name="featured_article">
                                <label class="form-check-label" for="featured_article">Featured Article</label>
                              </div>
                            </div>
                            
                            <div class="mb-3">
                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="allow_comments" name="allow_comments" checked>
                                <label class="form-check-label" for="allow_comments">Allow Comments</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <!-- Featured Image -->
                        <div class="card mb-3" style="background: var(--card-bg); border: 1px solid var(--border-color);">
                          <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-image me-2"></i>Featured Image</h6>
                          </div>
                          <div class="card-body">
                            <div class="mb-3">
                              <input type="file" class="form-control" id="featured_image" name="featured_image" accept="image/*">
                              <div class="form-text">Recommended: 1200x630px for social sharing</div>
                            </div>
                            <div id="featured_image_preview" class="text-center" style="display: none;">
                              <img id="featured_preview_img" src="" class="img-fluid" style="max-height: 200px; border-radius: 8px;">
                              <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFeaturedImage()">Remove</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <!-- Additional Images -->
                        <div class="card mb-3" style="background: var(--card-bg); border: 1px solid var(--border-color);">
                          <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-images me-2"></i>Additional Images</h6>
                          </div>
                          <div class="card-body">
                            <div class="mb-3">
                              <input type="file" class="form-control" id="additional_images" name="additional_images[]" accept="image/*" multiple>
                              <div class="form-text">Upload multiple images for the article gallery</div>
                            </div>
                            <div id="additional_images_preview" class="row g-2"></div>
                          </div>
                        </div>
                        
                        <!-- Categories -->
                        <div class="card mb-3" style="background: var(--card-bg); border: 1px solid var(--border-color);">
                          <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-folder me-2"></i>Category</h6>
                          </div>
                          <div class="card-body">
                            <div class="mb-3">
                              <select class="form-select" id="news_category" name="news_category" required>
                                <option value="">Select Category</option>
                                <option value="football">Football</option>
                                <option value="basketball">Basketball</option>
                                <option value="tennis">Tennis</option>
                                <option value="transfer-news">Transfer News</option>
                                <option value="champions-league">Champions League</option>
                                <option value="premier-league">Premier League</option>
                                <option value="general-sports">General Sports</option>
                              </select>
                            </div>
                            <div class="mb-3">
                              <label for="new_category" class="form-label">Or Create New Category</label>
                              <input type="text" class="form-control" id="new_category" name="new_category" placeholder="Enter new category name">
                            </div>
                          </div>
                        </div>
                        
                        <!-- Tags -->
                        <div class="card mb-3" style="background: var(--card-bg); border: 1px solid var(--border-color);">
                          <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-tags me-2"></i>Tags</h6>
                          </div>
                          <div class="card-body">
                            <div class="mb-3">
                              <input type="text" class="form-control" id="news_tags" name="news_tags" placeholder="Enter tags separated by commas">
                              <div class="form-text">e.g., transfer, arsenal, premier league</div>
                            </div>
                            <div id="tags_preview" class="mb-2"></div>
                            <div class="suggested-tags">
                              <small class="text-muted">Popular tags:</small>
                              <div class="mt-1">
                                <span class="badge tag-suggestion me-1 mb-1" style="background: var(--accent-blue); cursor: pointer;">breaking</span>
                                <span class="badge tag-suggestion me-1 mb-1" style="background: var(--accent-blue); cursor: pointer;">exclusive</span>
                                <span class="badge tag-suggestion me-1 mb-1" style="background: var(--accent-blue); cursor: pointer;">match-report</span>
                                <span class="badge tag-suggestion me-1 mb-1" style="background: var(--accent-blue); cursor: pointer;">interview</span>
                                <span class="badge tag-suggestion me-1 mb-1" style="background: var(--accent-blue); cursor: pointer;">analysis</span>
                                <span class="badge tag-suggestion me-1 mb-1" style="background: var(--accent-blue); cursor: pointer;">injury-update</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="d-grid gap-2">
                          <button type="submit" name="publish" class="btn btn-success">
                            <i class="fas fa-paper-plane me-2"></i>Publish Article
                          </button>
                          <button type="submit" name="save_draft" class="btn btn-outline-primary">
                            <i class="fas fa-save me-2"></i>Save as Draft
                          </button>
                          <button type="button" class="btn btn-outline-secondary" onclick="previewArticle()">
                            <i class="fas fa-eye me-2"></i>Preview
                          </button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                
                <!-- Manage Articles Tab -->
                <div class="tab-pane fade" id="manage-news" role="tabpanel">
                  <div class="row mb-3">
                    <div class="col-md-8">
                      <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search articles..." id="article_search">
                        <button class="btn btn-outline-primary" type="button">
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <select class="form-select" id="status_filter">
                        <option value="">All Status</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="scheduled">Scheduled</option>
                      </select>
                    </div>
                  </div>
                  
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>Article</th>
                          <th>Category</th>
                          <th>Status</th>
                          <th>Date</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody id="articles_table">
                        <!-- Sample Article Row -->
                        <tr>
                          <td>
                            <div class="d-flex align-items-center">
                              <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=60&h=40&fit=crop" class="me-3" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                              <div>
                                <div style="font-weight: 600;">PSG to face Barcelona in Champions League draw</div>
                                <small style="color: var(--text-muted);">UEFA Champions League draw has been conducted...</small>
                              </div>
                            </div>
                          </td>
                          <td><span class="badge" style="background: var(--accent-purple); color: white;">Champions League</span></td>
                          <td><span class="badge" style="background: var(--accent-green); color: white;">Published</span></td>
                          <td>
                            <div>Aug 29, 2025</div>
                            <small style="color: var(--text-muted);">2:30 PM</small>
                          </td>
                          <td>
                            <div class="btn-group" role="group">
                              <button type="button" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                              </button>
                              <button type="button" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-eye"></i>
                              </button>
                              <button type="button" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                              </button>
                            </div>
                          </td>
                        </tr>
                        <!-- More sample rows can be added here -->
                      </tbody>
                    </table>
                  </div>
                  
                  <!-- Pagination -->
                  <nav aria-label="Articles pagination">
                    <ul class="pagination justify-content-center">
                      <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                      </li>
                      <li class="page-item active"><a class="page-link" href="#">1</a></li>
                      <li class="page-item"><a class="page-link" href="#">2</a></li>
                      <li class="page-item"><a class="page-link" href="#">3</a></li>
                      <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                      </li>
                    </ul>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- CKEditor CDN -->
  <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Initialize CKEditor
    let newsEditor;
    ClassicEditor
      .create(document.querySelector('#news_content'), {
        toolbar: [
          'heading', '|',
          'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
          'outdent', 'indent', '|',
          'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
          'undo', 'redo', 'horizontalLine', 'specialCharacters'
        ],
        image: {
          toolbar: ['imageTextAlternative', 'imageCaption', 'imageStyle:inline', 'imageStyle:block', 'imageStyle:side']
        }
      })
      .then(editor => {
        newsEditor = editor;
      })
      .catch(error => {
        console.error('CKEditor initialization error:', error);
      });

    // News Management JavaScript Functions
    function initializeNewsManagement() {
      // Publishing status change handler
      const statusSelect = document.getElementById('news_status');
      const publishDateGroup = document.getElementById('publish_date_group');
      
      statusSelect.addEventListener('change', function() {
        if (this.value === 'scheduled') {
          publishDateGroup.style.display = 'block';
          document.getElementById('publish_date').required = true;
        } else {
          publishDateGroup.style.display = 'none';
          document.getElementById('publish_date').required = false;
        }
      });

      // Featured image preview
      const featuredImageInput = document.getElementById('featured_image');
      featuredImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            document.getElementById('featured_preview_img').src = e.target.result;
            document.getElementById('featured_image_preview').style.display = 'block';
          };
          reader.readAsDataURL(file);
        }
      });

      // Additional images preview
      const additionalImagesInput = document.getElementById('additional_images');
      additionalImagesInput.addEventListener('change', function(e) {
        const preview = document.getElementById('additional_images_preview');
        preview.innerHTML = '';
        
        Array.from(e.target.files).forEach((file, index) => {
          const reader = new FileReader();
          reader.onload = function(e) {
            const col = document.createElement('div');
            col.className = 'col-6 col-md-4';
            col.innerHTML = `
              <div style="position: relative;">
                <img src="${e.target.result}" class="img-fluid" style="border-radius: 4px; height: 80px; width: 100%; object-fit: cover;">
                <button type="button" class="btn btn-sm btn-outline-danger" style="position: absolute; top: 5px; right: 5px; padding: 2px 6px;" onclick="removeAdditionalImage(${index})">
                  <i class="fas fa-times" style="font-size: 10px;"></i>
                </button>
              </div>
            `;
            preview.appendChild(col);
          };
          reader.readAsDataURL(file);
        });
      });

      // Tags management
      const tagsInput = document.getElementById('news_tags');
      const tagsPreview = document.getElementById('tags_preview');
      
      tagsInput.addEventListener('input', function() {
        updateTagsPreview();
      });

      // Tag suggestions click handlers
      document.querySelectorAll('.tag-suggestion').forEach(tag => {
        tag.addEventListener('click', function() {
          const currentTags = tagsInput.value;
          const newTag = this.textContent;
          
          if (!currentTags.includes(newTag)) {
            tagsInput.value = currentTags ? currentTags + ', ' + newTag : newTag;
            updateTagsPreview();
          }
        });
      });

      // Auto-generate slug from title
      const titleInput = document.getElementById('news_title');
      const slugInput = document.getElementById('news_slug');
      
      titleInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.value === generateSlug(titleInput.value)) {
          slugInput.value = generateSlug(this.value);
        }
      });

      // Form validation
      const newsForm = document.getElementById('newsForm');
      newsForm.addEventListener('submit', function(e) {
        if (!validateNewsForm()) {
          e.preventDefault();
        }
      });
    }

    function updateTagsPreview() {
      const tagsInput = document.getElementById('news_tags');
      const tagsPreview = document.getElementById('tags_preview');
      
      if (tagsInput.value.trim()) {
        const tags = tagsInput.value.split(',').map(tag => tag.trim()).filter(tag => tag);
        tagsPreview.innerHTML = tags.map(tag => 
          `<span class="badge me-1 mb-1" style="background: var(--accent-green); color: white;">${tag}</span>`
        ).join('');
      } else {
        tagsPreview.innerHTML = '';
      }
    }

    function generateSlug(title) {
      return title
        .toLowerCase()
        .replace(/[^a-z0-9 -]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
    }

    function removeFeaturedImage() {
      document.getElementById('featured_image').value = '';
      document.getElementById('featured_image_preview').style.display = 'none';
    }

    function removeAdditionalImage(index) {
      // This would need to be more sophisticated in a real implementation
      // For now, we'll just hide the preview
      event.target.closest('.col-6').style.display = 'none';
    }

    function validateNewsForm() {
      const title = document.getElementById('news_title').value.trim();
      const category = document.getElementById('news_category').value;
      const newCategory = document.getElementById('new_category').value.trim();
      const content = newsEditor ? newsEditor.getData().trim() : '';
      
      if (!title) {
        alert('Please enter an article title.');
        return false;
      }
      
      if (!category && !newCategory) {
        alert('Please select a category or create a new one.');
        return false;
      }
      
      if (!content) {
        alert('Please enter article content.');
        return false;
      }
      
      return true;
    }

    function previewArticle() {
      const title = document.getElementById('news_title').value;
      const content = newsEditor ? newsEditor.getData() : '';
      
      if (!title || !content) {
        alert('Please fill in the title and content before previewing.');
        return;
      }
      
      // Create preview window
      const previewWindow = window.open('', '_blank', 'width=800,height=600');
      previewWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
          <title>Preview: ${title}</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
          <style>
            body { background: #01002c; color: #f1f1f1; padding: 20px; }
            .preview-container { max-width: 800px; margin: 0 auto; background: rgba(255,255,255,0.05); padding: 30px; border-radius: 12px; }
            .preview-header { border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 20px; margin-bottom: 30px; }
            .preview-title { font-size: 2rem; font-weight: bold; margin-bottom: 10px; }
            .preview-meta { color: #8b9dc3; font-size: 0.9rem; }
            .preview-content { line-height: 1.8; }
            .preview-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 20px 0; }
          </style>
        </head>
        <body>
          <div class="preview-container">
            <div class="preview-header">
              <div class="preview-title">${title}</div>
              <div class="preview-meta">Preview Mode - ${new Date().toLocaleDateString()}</div>
            </div>
            <div class="preview-content">
              ${content}
            </div>
          </div>
        </body>
        </html>
      `);
      previewWindow.document.close();
    }

    // Search and filter functionality for manage articles
    function initializeArticleManagement() {
      const searchInput = document.getElementById('article_search');
      const statusFilter = document.getElementById('status_filter');
      
      if (searchInput) {
        searchInput.addEventListener('input', function() {
          filterArticles();
        });
      }
      
      if (statusFilter) {
        statusFilter.addEventListener('change', function() {
          filterArticles();
        });
      }
    }

    function filterArticles() {
      // This would be implemented with actual article data
      console.log('Filtering articles...');
    }

    // Navigation functionality
    document.addEventListener('DOMContentLoaded', function() {
      const menuToggle = document.getElementById('menuToggle');
      const sidebar = document.getElementById('sidebar');
      const mainContent = document.getElementById('mainContent');
      const overlay = document.getElementById('overlay');
      const headerTitle = document.getElementById('headerTitle');
      const themeToggle = document.getElementById('themeToggle');
      const navLinks = document.querySelectorAll('.nav-link[data-section]');
      const contentSections = document.querySelectorAll('.content-section');

      // Menu toggle
      menuToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
      });

      overlay.addEventListener('click', function() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
      });

      // Theme toggle
      themeToggle.addEventListener('click', function() {
        document.body.classList.toggle('light-mode');
        const isLight = document.body.classList.contains('light-mode');
        themeToggle.innerHTML = isLight ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        
        // Save theme preference
        localStorage.setItem('theme', isLight ? 'light' : 'dark');
      });

      // Load saved theme
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'light') {
        document.body.classList.add('light-mode');
        themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
      }

      // Navigation with proper section switching
      navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          
          // Don't handle external links
          if (this.getAttribute('href') === '/') {
            window.location.href = '/';
            return;
          }
          
          const targetSection = this.dataset.section;
          
          // Update active nav - remove from all, add to current
          navLinks.forEach(nav => nav.classList.remove('active'));
          this.classList.add('active');
          
          // Show target section - hide all, show target
          contentSections.forEach(section => {
            section.classList.remove('active');
            section.style.display = 'none';
          });
          
          const targetElement = document.getElementById(targetSection + '-section');
          if (targetElement) {
            targetElement.classList.add('active');
            targetElement.style.display = 'block';
          }
          
          // Update header title
          const sectionTitle = this.querySelector('span').textContent;
          headerTitle.textContent = sectionTitle;
          
          // Close sidebar on mobile
          if (window.innerWidth < 768) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
          }
        });
      });

      // Initialize first section properly
      const firstSection = document.getElementById('dashboard-section');
      if (firstSection) {
        firstSection.style.display = 'block';
        firstSection.classList.add('active');
      }
      
      // Hide other sections initially
      contentSections.forEach(section => {
        if (section.id !== 'dashboard-section') {
          section.style.display = 'none';
          section.classList.remove('active');
        }
      });

      // Initialize News Management features
      initializeNewsManagement();
      initializeArticleManagement();

      // Auto-close sidebar on larger screens
      window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
          sidebar.classList.remove('active');
          overlay.classList.remove('active');
        }
      });
    });
  </script>
</body>
</html>