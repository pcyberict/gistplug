<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PCYBER TV - Live Sports Streaming</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    :root {
      --bg-color: #f8f9fa;
      --text-color: #212529;
      --card-bg: #ffffff;
      --accent: #0d6efd;
      --badge-bg: #e9ecef;
      
      /* Header always uses dark colors */
      --header-bg-color: #01002c;
      --header-text-color: #f1f1f1;
      --header-card-bg: rgba(255, 255, 255, 0.05);
      --header-badge-bg: #1a1e2d;
    }
   
    body.dark-mode {
      --bg-color: #01002c;               /* Very dark navy */
      --text-color: #f1f1f1;             /* Light text */
      --card-bg: rgba(255, 255, 255, 0.05); /* Semi-transparent glass card */
      --accent: #4dabf7;                 /* Soft blue accent */
      --badge-bg: #1a1e2d;               /* Refined dark for badges */
    }

    body {
      background: var(--bg-color);
      color: var(--text-color);
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      min-height: 100vh;
    }

    /* Default to dark mode */
    body {
      background: #01002c;
      color: #f1f1f1;
    }

    body.light-mode {
      background: #f8f9fa;
      color: #212529;
    }

    body.dark-mode {
      background-color: var(--bg-color);
      color: var(--text-color);
      font-family: 'Inter', sans-serif;
    }

    /* Flashscore Header Styles - Always Dark */
    .flashscore-header {
      background-color: var(--header-bg-color);
      color: var(--header-text-color);
      position: sticky;
      top: 0;
      z-index: 1000;
      margin-bottom: 2rem;
      border-bottom: 1px solid var(--header-badge-bg);
    }

    .top-nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 16px;
      border-bottom: 1px solid var(--header-badge-bg);
    }

    .logo {
      display: flex;
      align-items: center;
      font-size: 18px;
      font-weight: bold;
      color: var(--header-text-color);
      text-decoration: none;
    }

    .logo-icon {
      width: 32px;
      height: 24px;
      margin-right: 8px;
      background: linear-gradient(45deg, #ff6b35, #f7931e);
      border-radius: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    .logo-icon::before {
      content: '';
      width: 16px;
      height: 12px;
      background: white;
      border-radius: 2px;
      transform: skew(-10deg);
    }

    .right-nav {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .search-icon, .user-icon, .menu-icon {
      width: 24px;
      height: 24px;
      cursor: pointer;
      fill: var(--header-text-color);
      transition: fill 0.2s;
      opacity: 0.7;
    }

    .search-icon:hover, .user-icon:hover, .menu-icon:hover {
      opacity: 1;
    }

    .main-nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 16px;
      background-color: var(--header-bg-color);
      height: 48px;
      position: relative;
    }

    .nav-left {
      display: flex;
      align-items: center;
      gap: 32px;
    }

    .nav-tabs {
      display: flex;
      align-items: center;
      height: 48px;
      gap: 0;
    }

    .nav-tab {
      display: flex;
      align-items: center;
      padding: 0 20px;
      height: 100%;
      color: var(--header-text-color);
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border-bottom: 3px solid transparent;
      transition: all 0.2s;
      position: relative;
    }

    .nav-tab:hover {
      background-color: var(--header-card-bg);
      border-bottom-color: #ff6b35;
      color: var(--header-text-color);
    }

    .nav-tab.active {
      border-bottom-color: #ff6b35;
      background-color: var(--header-card-bg);
    }

    .nav-icon {
      width: 18px;
      height: 18px;
      margin-right: 8px;
      fill: currentColor;
    }

    .sports-nav {
      display: flex;
      align-items: center;
      gap: 24px;
    }

    .favorite-icon {
      width: 20px;
      height: 20px;
      fill: var(--header-text-color);
      cursor: pointer;
      transition: fill 0.2s;
      opacity: 0.7;
      position: relative;
    }

    .favorite-icon:hover {
      fill: #ff6b35;
      opacity: 1;
    }

    .sport-link {
      color: var(--header-text-color);
      text-decoration: none;
      font-size: 13px;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 8px 0;
      border-bottom: 2px solid transparent;
      transition: all 0.2s;
      opacity: 0.7;
      white-space: nowrap;
    }

    .sport-link:hover {
      color: var(--header-text-color);
      border-bottom-color: #ff6b35;
      opacity: 1;
    }

    .sport-link.active {
      color: var(--header-text-color);
      border-bottom-color: #ff6b35;
      opacity: 1;
    }

    .theme-toggle-header {
      background: none;
      border: none;
      font-size: 1.2rem;
      color: var(--header-text-color);
      cursor: pointer;
      padding: 0.5rem;
      border-radius: 50%;
      transition: all 0.2s;
    }

    .theme-toggle-header:hover {
      background-color: var(--header-card-bg);
    }

    .notification-badge {
      position: absolute;
      top: -4px;
      right: -4px;
      background-color: #ff6b35;
      color: white;
      border-radius: 50%;
      width: 18px;
      height: 18px;
      font-size: 10px;
      font-weight: bold;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 1rem;
    }

    .streams-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
      gap: 2rem;
    }

    .stream-card {
      background: var(--card-bg);
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(13, 110, 253, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    .card {
      background: var(--card-bg);
      color: var(--text-color);
      border-radius: 16px;
      padding: 1.5rem;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      border: 1px solid rgba(255, 255, 255, 0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.4);
    }

    .stream-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 6px 20px rgba(13, 110, 253, 0.2);
    }

    body.dark-mode .stream-card {
      background: var(--card-bg);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      border: 1px solid rgba(255, 255, 255, 0.08);
    }

    body.dark-mode .stream-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.4);
    }

    .stream-video {
      position: relative;
      padding-top: 56.25%;
      overflow: hidden;
      border-radius: 12px 12px 0 0;
    }

    .stream-video iframe {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      border: none;
    }

    .stream-content {
      padding: 1rem;
      flex-grow: 1;
    }

    .stream-title {
      font-size: 1.15rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .badge-category {
      background-color: #c80037a3;
      font-size: 0.6rem;
      font-weight: 500;
      color: var(--text-color);
      padding: 4px 10px 4px 10px;
      border-radius: 20px;
      display: inline-block;
      margin-bottom: 0.75rem;
    }

    .btn-view {
      padding: 0.5rem 1.2rem;
      font-weight: 600;
      border-radius: 25px;
    }

    /* Chat styles */
    .chat-container {
      background: var(--card-bg);
      border-radius: 16px;
      padding: 1.5rem;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .chat-messages {
      height: 300px;
      overflow-y: auto;
      background: rgba(255, 255, 255, 0.02);
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1rem;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
      .main-nav {
        flex-direction: column;
        height: auto;
        padding: 0;
      }

      .nav-left {
        width: 100%;
        flex-direction: column;
        gap: 0;
      }

      .nav-tabs {
        width: 100%;
        height: 48px;
        border-bottom: 1px solid var(--header-badge-bg);
        justify-content: flex-start;
        gap: 0;
      }

      .nav-tab {
        flex: 1;
        justify-content: center;
        text-align: center;
        height: 48px;
        padding: 0 16px;
        min-width: 0;
      }

      .sports-nav {
        width: 100%;
        justify-content: flex-start;
        padding: 12px 16px;
        overflow-x: auto;
        gap: 20px;
      }

      .streams-grid {
        grid-template-columns: 1fr;
      }

      .container {
        padding: 0 0.5rem;
      }
    }
  </style>
</head>
<body class="dark-mode">
  <!-- Flashscore Header -->
  <header class="flashscore-header">
    <!-- Top Navigation -->
    <div class="top-nav">
      <a href="/" class="logo">
        <div class="logo-icon"></div>
        PCYBER TV
      </a>
  
      <div class="right-nav">
        <svg class="search-icon" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
        <div style="position: relative;">
          <svg class="user-icon" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        </div>
        <svg class="menu-icon" viewBox="0 0 24 24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>
      </div>
    </div>
    <nav class="main-nav">
      <div class="nav-left">
        <div class="nav-tabs">
          <a href="/" class="nav-tab active">
            <svg class="nav-icon" viewBox="0 0 24 24">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
            NEWS
          </a>
          <a href="/" class="nav-tab">
            <svg class="nav-icon" viewBox="0 0 24 24">
              <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
            STREAMS
          </a>
          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/dashboard" class="nav-tab">
              <svg class="nav-icon" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
              </svg>
              DASHBOARD
            </a>
          <?php endif; ?>
        </div>
        <div class="sports-nav">
          <div style="position: relative;">
            <svg class="favorite-icon" viewBox="0 0 24 24">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
            <div class="notification-badge"><?php echo count(StreamManager::loadStreams()); ?></div>
          </div>
          <a href="/category/football" class="sport-link active">FOOTBALL</a>
          <a href="/category/basketball" class="sport-link">BASKETBALL</a>
          <a href="/category/tennis" class="sport-link">TENNIS</a>
          <a href="/category/church" class="sport-link">CHURCH</a>
          <a href="/category/entertainment" class="sport-link">ENTERTAINMENT</a>
          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/logout-user" class="sport-link">LOGOUT</a>
          <?php else: ?>
            <a href="/login" class="sport-link">LOGIN</a>
            <a href="/signup" class="sport-link">SIGNUP</a>
          <?php endif; ?>
        </div>
      </div>
      <button class="theme-toggle-header" onclick="toggleTheme()" title="Toggle Light/Dark">☀️</button>
    </nav>
  </header>

  <?php if (isset($_SESSION['message'])): ?>
    <div class="container">
      <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
          <?php echo htmlspecialchars($_SESSION['message']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
  <?php endif; ?>

  <div class="container">

    <?php if (isset($selected_category)): ?>
      <div class="mb-4">
        <h5 class="text-muted">Showing results for category: <strong><?php echo htmlspecialchars($selected_category); ?></strong></h5>
        <a href="/" class="btn btn-outline-secondary btn-sm">↩ Back to All Streams</a>
      </div>
    <?php endif; ?>
    
    <section class="streams-grid" id="streamList">
      <?php if (isset($initial_streams)): $streams = $initial_streams; endif; ?>
      <?php if (!empty($streams)): ?>
        <?php foreach ($streams as $index => $stream): ?>
          <div class="stream-card">
            <div class="stream-video">
              <?php if ($stream['platform'] == 'YouTube'): ?>
                <iframe src="https://www.youtube.com/embed/<?php echo htmlspecialchars($stream['video_id']); ?>" allowfullscreen></iframe>
              <?php elseif ($stream['platform'] == 'Facebook'): ?>
                <iframe src="https://www.facebook.com/plugins/video.php?href=<?php echo urlencode($stream['video_id']); ?>&show_text=0&width=560" allowfullscreen></iframe>
              <?php else: ?>
                <p class="text-danger p-3">Unsupported platform</p>
              <?php endif; ?>
            </div>
            <div class="stream-content">
              <div class="stream-title"><?php echo htmlspecialchars($stream['title']); ?></div>
              <a href="/category/<?php echo urlencode($stream['category']); ?>" class="badge-category text-decoration-none"><?php echo htmlspecialchars($stream['category']); ?></a><br>
              <a href="/stream/<?php echo $index; ?>" class="btn btn-primary btn-view">View Stream</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No streams available.</p>
      <?php endif; ?>
    </section>
    
    <?php if (!isset($selected_category)): ?>
      <button id="loadMoreBtn" class="btn btn-outline-primary" style="display: block; margin: 2rem auto; border-radius: 25px; font-weight: 600; padding: 0.5rem 1.2rem;">
        Load More Streams
      </button>
    <?php endif; ?>
    
    <div class="row mt-5">
      <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <h4>Welcome to PCYBER TV</h4>
            <p>Your premier destination for live sports streaming and entertainment. Watch your favorite teams and events in high quality.</p>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="chat-container">
          <h4>Live Chat</h4>
          <div class="chat-messages">
            <?php
            $chat = StreamManager::loadChat();
            foreach ($chat as $message):
            ?>
              <div class="mb-2">
                <strong><?php echo htmlspecialchars($message['user']); ?>:</strong>
                <?php echo htmlspecialchars($message['message']); ?>
              </div>
            <?php endforeach; ?>
          </div>
          
          <form method="POST" action="/chat">
            <div class="mb-3">
              <input type="text" name="user" class="form-control" placeholder="Your name" required>
            </div>
            <div class="mb-3">
              <textarea name="message" class="form-control" placeholder="Your message" required></textarea>
            </div>
            <button type="submit" class="btn btn-success w-100">Send Message</button>
          </form>
        </div>
      </div>
    </div>
  </div>

<!-- DESKTOP FOOTER HTML -->
<footer class="desktop-footer">
  <div class="footer-content">
    <div class="footer-top">
      <div class="footer-section">
        <h3>Sports</h3>
        <div class="sports-grid">
          <a href="/category/football">Football</a>
          <a href="/category/basketball">Basketball</a>
          <a href="/category/tennis">Tennis</a>
          <a href="/category/church">Church</a>
          <a href="/category/entertainment">Entertainment</a>
        </div>
      </div>
      
      <div class="footer-section">
        <h3>Platform</h3>
        <ul>
          <li><a href="/">Home</a></li>
          <li><a href="/login">Login</a></li>
          <li><a href="/signup">Sign Up</a></li>
          <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="/dashboard">Dashboard</a></li>
          <?php endif; ?>
        </ul>
      </div>
      
      <div class="footer-section">
        <h3>Support</h3>
        <ul>
          <li><a href="#">Live Chat</a></li>
          <li><a href="#">Contact Us</a></li>
          <li><a href="#">FAQ</a></li>
          <li><a href="#">Help</a></li>
        </ul>
      </div>
      
      <div class="footer-section">
        <h3>Follow Us</h3>
        <div class="social-links">
          <a href="#" class="social-link">
            <svg viewBox="0 0 24 24">
              <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
            </svg>
          </a>
          <a href="#" class="social-link">
            <svg viewBox="0 0 24 24">
              <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
            </svg>
          </a>
          <a href="#" class="social-link">
            <svg viewBox="0 0 24 24">
              <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
            </svg>
          </a>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="footer-bottom-content">
        <div class="footer-logo">
          <div class="footer-logo-icon"></div>
          PCYBER TV
        </div>
        
        <div class="footer-legal">
          <a href="/">Home</a>
          <a href="/login">Login</a>
          <a href="/signup">Sign Up</a>
        </div>
      </div>
      
      <div class="footer-disclaimer">
        <p>PCYBER TV - Your premier destination for live sports streaming and entertainment.</p>
        <p class="betting-warning">You must be 18+ to access streaming content.</p>
      </div>
    </div>
  </div>
</footer>

<!-- MOBILE STICKY FOOTER HTML -->
<footer class="mobile-footer">
  <nav class="mobile-nav">
    <a href="/" class="mobile-nav-item active">
      <svg viewBox="0 0 24 24">
        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
      </svg>
      <span>Home</span>
    </a>
    
    <a href="/category/football" class="mobile-nav-item">
      <svg viewBox="0 0 24 24">
        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
      </svg>
      <span>Sports</span>
    </a>
    
    <a href="#" class="mobile-nav-item">
      <svg viewBox="0 0 24 24">
        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
      </svg>
      <span>Chat</span>
    </a>
    
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="/dashboard" class="mobile-nav-item">
        <svg viewBox="0 0 24 24">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
        </svg>
        <span>Account</span>
      </a>
    <?php else: ?>
      <a href="/login" class="mobile-nav-item">
        <svg viewBox="0 0 24 24">
          <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
        </svg>
        <span>Login</span>
      </a>
    <?php endif; ?>
  </nav>
</footer>

<style>
/* Footer Styles */
.desktop-footer {
  background: var(--header-bg-color);
  color: var(--header-text-color);
  margin-top: 4rem;
  padding: 3rem 0 1rem 0;
}

.footer-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

.footer-top {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
  margin-bottom: 2rem;
}

.footer-section h3 {
  color: #ff6b35;
  margin-bottom: 1rem;
  font-size: 1.1rem;
  font-weight: 600;
}

.footer-section ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-section ul li {
  margin-bottom: 0.5rem;
}

.footer-section a {
  color: var(--header-text-color);
  text-decoration: none;
  opacity: 0.8;
  transition: opacity 0.2s;
}

.footer-section a:hover {
  opacity: 1;
  color: #ff6b35;
}

.sports-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 0.5rem;
}

.social-links {
  display: flex;
  gap: 1rem;
}

.social-link {
  width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.social-link:hover {
  background: #ff6b35;
  transform: translateY(-2px);
}

.social-link svg {
  width: 20px;
  height: 20px;
  fill: currentColor;
}

.footer-bottom {
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  padding-top: 2rem;
}

.footer-bottom-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.footer-logo {
  display: flex;
  align-items: center;
  font-size: 18px;
  font-weight: bold;
}

.footer-logo-icon {
  width: 32px;
  height: 24px;
  margin-right: 8px;
  background: linear-gradient(45deg, #ff6b35, #f7931e);
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.footer-logo-icon::before {
  content: '';
  width: 16px;
  height: 12px;
  background: white;
  border-radius: 2px;
  transform: skew(-10deg);
}

.footer-legal {
  display: flex;
  gap: 2rem;
  flex-wrap: wrap;
}

.footer-disclaimer {
  text-align: center;
  font-size: 0.85rem;
  opacity: 0.7;
  line-height: 1.5;
}

.betting-warning {
  margin-top: 1rem;
  font-weight: 500;
  color: #ff6b35;
}

/* Mobile Footer */
.mobile-footer {
  display: none;
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background: var(--header-bg-color);
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  z-index: 1000;
}

.mobile-nav {
  display: flex;
  justify-content: space-around;
  padding: 0.5rem 0;
}

.mobile-nav-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-decoration: none;
  color: var(--header-text-color);
  opacity: 0.7;
  transition: all 0.2s;
  padding: 0.5rem;
  border-radius: 8px;
}

.mobile-nav-item.active,
.mobile-nav-item:hover {
  opacity: 1;
  color: #ff6b35;
}

.mobile-nav-item svg {
  width: 20px;
  height: 20px;
  margin-bottom: 0.25rem;
  fill: currentColor;
}

.mobile-nav-item span {
  font-size: 0.7rem;
  font-weight: 500;
}

@media (max-width: 768px) {
  .desktop-footer {
    display: none;
  }
  
  .mobile-footer {
    display: block;
  }
  
  body {
    padding-bottom: 80px;
  }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Theme toggle functionality
  function toggleTheme() {
    const body = document.body;
    if (body.classList.contains('dark-mode')) {
      body.classList.remove('dark-mode');
      body.classList.add('light-mode');
      localStorage.setItem('theme', 'light');
    } else {
      body.classList.remove('light-mode');
      body.classList.add('dark-mode');
      localStorage.setItem('theme', 'dark');
    }
  }
  
  // Load saved theme
  document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    if (savedTheme === 'light') {
      document.body.classList.remove('dark-mode');
      document.body.classList.add('light-mode');
    }
  });

  // Load more streams functionality
  document.getElementById('loadMoreBtn')?.addEventListener('click', function() {
    const currentStreams = document.querySelectorAll('.stream-card').length;
    fetch(`/load_more_streams?offset=${currentStreams}&limit=6`)
      .then(response => response.json())
      .then(data => {
        const container = document.getElementById('streamList');
        data.streams.forEach((stream, index) => {
          const streamIndex = currentStreams + index;
          const streamHtml = `
            <div class="stream-card">
              <div class="stream-video">
                ${stream.platform === 'YouTube' ? 
                  `<iframe src="https://www.youtube.com/embed/${stream.video_id}" allowfullscreen></iframe>` :
                  stream.platform === 'Facebook' ?
                  `<iframe src="https://www.facebook.com/plugins/video.php?href=${encodeURIComponent(stream.video_id)}&show_text=0&width=560" allowfullscreen></iframe>` :
                  '<p class="text-danger p-3">Unsupported platform</p>'
                }
              </div>
              <div class="stream-content">
                <div class="stream-title">${stream.title}</div>
                <a href="/category/${encodeURIComponent(stream.category)}" class="badge-category text-decoration-none">${stream.category}</a><br>
                <a href="/stream/${streamIndex}" class="btn btn-primary btn-view">View Stream</a>
              </div>
            </div>
          `;
          container.insertAdjacentHTML('beforeend', streamHtml);
        });
        
        if (!data.has_more) {
          this.style.display = 'none';
        }
      })
      .catch(console.error);
  });
</script>
</body>
</html>