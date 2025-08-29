<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo $page_title ?? 'PCYBER TV - Live Sports Streaming'; ?></title>
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
          <a href="/" class="nav-tab <?php echo ($current_page ?? '') === 'home' ? 'active' : ''; ?>">
            <svg class="nav-icon" viewBox="0 0 24 24">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
            NEWS
          </a>
          <a href="/" class="nav-tab <?php echo ($current_page ?? '') === 'streams' ? 'active' : ''; ?>">
            <svg class="nav-icon" viewBox="0 0 24 24">
              <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
            STREAMS
          </a>
          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/dashboard" class="nav-tab <?php echo ($current_page ?? '') === 'dashboard' ? 'active' : ''; ?>">
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
            <div class="notification-badge"><?php echo isset($stream_count) ? $stream_count : count(StreamManager::loadStreams()); ?></div>
          </div>
          <a href="/category/football" class="sport-link <?php echo isset($selected_category) && $selected_category === 'football' ? 'active' : ''; ?>">FOOTBALL</a>
          <a href="/category/basketball" class="sport-link <?php echo isset($selected_category) && $selected_category === 'basketball' ? 'active' : ''; ?>">BASKETBALL</a>
          <a href="/category/tennis" class="sport-link <?php echo isset($selected_category) && $selected_category === 'tennis' ? 'active' : ''; ?>">TENNIS</a>
          <a href="/category/church" class="sport-link <?php echo isset($selected_category) && $selected_category === 'church' ? 'active' : ''; ?>">CHURCH</a>
          <a href="/category/entertainment" class="sport-link <?php echo isset($selected_category) && $selected_category === 'entertainment' ? 'active' : ''; ?>">ENTERTAINMENT</a>
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