<?php
session_start();
require_once 'config.php';
require_once 'Database.php';
require_once 'User.php';
require_once 'StreamManager.php';

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
            handleAdminAddStream();
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

function handleAdminAddStream() {
    if (!isset($_SESSION['admin'])) {
        header('Location: /admin-login');
        exit;
    }
    
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