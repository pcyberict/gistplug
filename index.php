<?php
session_start();
require_once 'config.php';
require_once 'Database.php';
require_once 'User.php';
require_once 'StreamManager.php';
require_once 'NewsManager.php';

// Rate limiting (basic implementation using session)
if (!isset($_SESSION['rate_limit'])) {
    $_SESSION['rate_limit'] = [];
}

// Basic routing
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Remove query parameters for routing
$route = strtok($path, '?');

// Handle different routes
switch ($route) {
    case '/':
        showHomePage();
        break;
    case '/news':
        showNewsPage();
        break;
    case '/login':
        if ($method === 'POST') {
            handleLogin();
        } else {
            showLoginPage();
        }
        break;
    case '/signup':
        if ($method === 'POST') {
            handleSignup();
        } else {
            showSignupPage();
        }
        break;
    case '/dashboard':
        showDashboard();
        break;
    case '/logout-user':
        logoutUser();
        break;
    case '/admin-login':
        if ($method === 'POST') {
            handleAdminLogin();
        } else {
            showAdminLoginPage();
        }
        break;
    case '/admin':
        if ($method === 'POST') {
            handleAdminSubmission();
        } else {
            showAdminPage();
        }
        break;
    case '/logout':
        logoutAdmin();
        break;
    case '/chat':
        if ($method === 'POST') {
            handleChat();
        }
        break;
    case '/load_more_streams':
        handleLoadMoreStreams();
        break;
    default:
        // Handle dynamic routes
        if (preg_match('/^\/category\/(.+)$/', $route, $matches)) {
            showCategoryPage($matches[1]);
        } elseif (preg_match('/^\/stream\/(\d+)$/', $route, $matches)) {
            showStreamPage((int)$matches[1]);
        } elseif (preg_match('/^\/edit\/(\d+)$/', $route, $matches)) {
            if ($method === 'POST') {
                handleEditStream((int)$matches[1]);
            } else {
                showEditPage((int)$matches[1]);
            }
        } elseif (preg_match('/^\/delete\/(\d+)$/', $route, $matches)) {
            if ($method === 'POST') {
                handleDeleteStream((int)$matches[1]);
            }
        } else {
            http_response_code(404);
            echo "Page not found";
        }
        break;
}

function showHomePage() {
    $streams = StreamManager::loadStreams();
    $initial_streams = array_slice($streams, 0, 6);
    include 'templates/index.php';
}

function showNewsPage() {
    include 'templates/news.php';
}

function showLoginPage() {
    include 'templates/login.php';
}

function handleLogin() {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $user = User::findByEmail($email);
    if ($user && $user->checkPassword($password)) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['message'] = "Welcome back, {$user->username}!";
        $_SESSION['message_type'] = 'success';
        header('Location: /dashboard');
        exit;
    } else {
        $_SESSION['message'] = 'Invalid email or password';
        $_SESSION['message_type'] = 'danger';
        header('Location: /login');
        exit;
    }
}

function showSignupPage() {
    include 'templates/signup.php';
}

function handleSignup() {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (User::exists($username, $email)) {
        $_SESSION['message'] = "Username or email already exists.";
        $_SESSION['message_type'] = 'warning';
        header('Location: /signup');
        exit;
    }
    
    $user = new User();
    $user->username = $username;
    $user->email = $email;
    $user->password = User::hashPassword($password);
    
    if ($user->save()) {
        $_SESSION['message'] = "Signup successful. Please log in.";
        $_SESSION['message_type'] = 'success';
        header('Location: /login');
        exit;
    } else {
        $_SESSION['message'] = "Error creating account.";
        $_SESSION['message_type'] = 'danger';
        header('Location: /signup');
        exit;
    }
}

function showDashboard() {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['message'] = "You must be logged in to view the dashboard.";
        $_SESSION['message_type'] = 'warning';
        header('Location: /login');
        exit;
    }
    include 'templates/dashboard.php';
}

function logoutUser() {
    unset($_SESSION['user_id']);
    unset($_SESSION['username']);
    $_SESSION['message'] = "Logged out successfully";
    $_SESSION['message_type'] = 'info';
    header('Location: /login');
    exit;
}

function showAdminLoginPage() {
    include 'templates/admin_login.php';
}

function handleAdminLogin() {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === Config::$admin_username && $password === Config::$admin_password) {
        $_SESSION['admin'] = true;
        header('Location: /admin');
        exit;
    } else {
        $_SESSION['message'] = 'Invalid admin username or password';
        $_SESSION['message_type'] = 'danger';
        header('Location: /admin-login');
        exit;
    }
}

function showAdminPage() {
    if (!isset($_SESSION['admin'])) {
        header('Location: /admin-login');
        exit;
    }
    
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
    $total_pages = ceil(count($streams) / Config::$streams_per_page);
    $start = ($page - 1) * Config::$streams_per_page;
    $paginated_streams = array_slice($streams, $start, Config::$streams_per_page);
    
    include 'templates/admin.php';
}

function handleAdminSubmission() {
    if (!isset($_SESSION['admin'])) {
        header('Location: /admin-login');
        exit;
    }
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_news') {
        handleAddNews();
    } else {
        // Default to stream handling for backwards compatibility
        handleAddStream();
    }
}

function handleAddStream() {
    $title = $_POST['title'] ?? '';
    $platform = $_POST['platform'] ?? '';
    $video_id = $_POST['video_id'] ?? '';
    $category = $_POST['category'] ?? '';
    
    $streams = StreamManager::loadStreams();
    array_unshift($streams, [
        'title' => $title,
        'platform' => $platform,
        'video_id' => $video_id,
        'category' => $category
    ]);
    
    StreamManager::saveStreams($streams);
    $_SESSION['message'] = 'Stream added successfully!';
    $_SESSION['message_type'] = 'success';
    header('Location: /admin');
    exit;
}

function handleAddNews() {
    // Handle file uploads first
    $featured_image_path = '';
    $additional_images = [];
    
    // Create uploads directory if it doesn't exist
    $upload_dir = 'uploads/news/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Handle featured image upload
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $file_extension = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_extension, $allowed_extensions)) {
            $featured_image_filename = uniqid() . '_featured.' . $file_extension;
            $featured_image_path = $upload_dir . $featured_image_filename;
            
            if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $featured_image_path)) {
                // Success
            } else {
                $featured_image_path = '';
            }
        }
    }
    
    // Handle additional images
    if (isset($_FILES['additional_images']) && is_array($_FILES['additional_images']['name'])) {
        foreach ($_FILES['additional_images']['name'] as $key => $name) {
            if ($_FILES['additional_images']['error'][$key] === UPLOAD_ERR_OK) {
                $file_extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($file_extension, $allowed_extensions)) {
                    $additional_filename = uniqid() . '_additional.' . $file_extension;
                    $additional_path = $upload_dir . $additional_filename;
                    
                    if (move_uploaded_file($_FILES['additional_images']['tmp_name'][$key], $additional_path)) {
                        $additional_images[] = $additional_path;
                    }
                }
            }
        }
    }
    
    // Determine status based on which button was clicked
    $status = 'draft'; // default
    if (isset($_POST['publish'])) {
        $status = 'published';
    } elseif (isset($_POST['save_draft'])) {
        $status = 'draft';
    }
    
    // Prepare article data
    $article = [
        'title' => $_POST['news_title'] ?? '',
        'slug' => $_POST['news_slug'] ?? '',
        'excerpt' => $_POST['news_excerpt'] ?? '',
        'content' => $_POST['news_content'] ?? '',
        'category' => ($_POST['new_category'] ?? '') ?: ($_POST['news_category'] ?? ''),
        'tags' => $_POST['news_tags'] ?? '',
        'status' => $status,
        'featured' => isset($_POST['featured_article']) ? 1 : 0,
        'allow_comments' => isset($_POST['allow_comments']) ? 1 : 0,
        'featured_image' => $featured_image_path,
        'additional_images' => implode(',', $additional_images),
        'seo_title' => $_POST['seo_title'] ?? '',
        'seo_description' => $_POST['seo_description'] ?? '',
        'publish_date' => $_POST['publish_date'] ?? null,
        'author' => 'Admin' // Could be dynamic based on logged-in user
    ];
    
    // Validate required fields
    if (empty($article['title']) || empty($article['content']) || empty($article['category'])) {
        $_SESSION['message'] = 'Please fill in all required fields (title, content, category).';
        $_SESSION['message_type'] = 'danger';
        header('Location: /admin');
        exit;
    }
    
    // Add the article
    $article_id = NewsManager::addArticle($article);
    
    if ($article_id) {
        $status_message = $article['status'] === 'published' ? 'published' : 'saved as ' . $article['status'];
        $_SESSION['message'] = "Article '{$article['title']}' has been {$status_message} successfully!";
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Error saving the article. Please try again.';
        $_SESSION['message_type'] = 'danger';
    }
    
    header('Location: /admin');
    exit;
}

function logoutAdmin() {
    unset($_SESSION['admin']);
    $_SESSION['message'] = 'You have been logged out.';
    $_SESSION['message_type'] = 'info';
    header('Location: /admin-login');
    exit;
}

function handleChat() {
    $user = $_POST['user'] ?? '';
    $message = $_POST['message'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $now = time();
    
    // Basic rate limiting
    if (!isset($_SESSION['rate_limit'][$ip])) {
        $_SESSION['rate_limit'][$ip] = [];
    }
    
    $_SESSION['rate_limit'][$ip] = array_filter($_SESSION['rate_limit'][$ip], function($time) use ($now) {
        return $now - $time < 60;
    });
    
    if (count($_SESSION['rate_limit'][$ip]) >= Config::$max_messages_per_minute) {
        http_response_code(429);
        echo "You're sending messages too fast. Please wait.";
        exit;
    }
    
    if (StreamManager::containsProfanity($message)) {
        $message = StreamManager::cleanMessage($message);
    }
    
    StreamManager::addChatMessage($user, $message);
    $_SESSION['rate_limit'][$ip][] = $now;
    
    header('Location: /');
    exit;
}

function handleLoadMoreStreams() {
    $offset = (int)($_GET['offset'] ?? 0);
    $limit = (int)($_GET['limit'] ?? 6);
    $all_streams = StreamManager::loadStreams();
    $sliced = array_slice($all_streams, $offset, $limit);
    $has_more = $offset + $limit < count($all_streams);
    
    header('Content-Type: application/json');
    echo json_encode([
        'streams' => array_map(function($s) {
            return [
                'title' => $s['title'] ?? '',
                'platform' => $s['platform'] ?? '',
                'video_id' => $s['video_id'] ?? '',
                'category' => $s['category'] ?? ''
            ];
        }, $sliced),
        'has_more' => $has_more
    ]);
}

function showCategoryPage($category_name) {
    $streams = StreamManager::loadStreams();
    $filtered_streams = array_filter($streams, function($stream) use ($category_name) {
        return strtolower($stream['category'] ?? '') === strtolower($category_name);
    });
    $selected_category = $category_name;
    include 'templates/index.php';
}

function showStreamPage($stream_id) {
    $streams = StreamManager::loadStreams();
    if ($stream_id >= 0 && $stream_id < count($streams)) {
        $stream = $streams[$stream_id];
        include 'templates/stream.php';
    } else {
        http_response_code(404);
        echo "Stream not found";
    }
}

function showEditPage($index) {
    if (!isset($_SESSION['admin'])) {
        header('Location: /admin-login');
        exit;
    }
    
    $streams = StreamManager::loadStreams();
    if ($index >= 0 && $index < count($streams)) {
        $stream = $streams[$index];
        include 'templates/edit.php';
    } else {
        http_response_code(404);
        echo "Stream not found";
    }
}

function handleEditStream($index) {
    if (!isset($_SESSION['admin'])) {
        header('Location: /admin-login');
        exit;
    }
    
    $streams = StreamManager::loadStreams();
    if ($index >= 0 && $index < count($streams)) {
        $streams[$index]['title'] = $_POST['title'] ?? '';
        $streams[$index]['platform'] = $_POST['platform'] ?? '';
        $streams[$index]['video_id'] = $_POST['video_id'] ?? '';
        $streams[$index]['category'] = $_POST['category'] ?? '';
        StreamManager::saveStreams($streams);
    }
    header('Location: /admin');
    exit;
}

function handleDeleteStream($index) {
    if (!isset($_SESSION['admin'])) {
        header('Location: /admin-login');
        exit;
    }
    
    $streams = StreamManager::loadStreams();
    if ($index >= 0 && $index < count($streams)) {
        array_splice($streams, $index, 1);
        StreamManager::saveStreams($streams);
    }
    header('Location: /admin');
    exit;
}
?>