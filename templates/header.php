<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo isset($page_title) ? $page_title : 'PCYBER TV - Live Sports Streaming'; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/styles.css" rel="stylesheet" />
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
          <a href="/" class="nav-tab <?php echo (isset($current_page) && $current_page === 'home') ? 'active' : ''; ?>">
            <svg class="nav-icon" viewBox="0 0 24 24">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
            NEWS
          </a>
          <a href="/" class="nav-tab <?php echo (isset($current_page) && $current_page === 'streams') ? 'active' : ''; ?>">
            <svg class="nav-icon" viewBox="0 0 24 24">
              <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
            STREAMS
          </a>
          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/dashboard" class="nav-tab <?php echo (isset($current_page) && $current_page === 'dashboard') ? 'active' : ''; ?>">
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
          <a href="/category/football" class="sport-link <?php echo (isset($selected_category) && $selected_category === 'football') ? 'active' : ''; ?>">FOOTBALL</a>
          <a href="/category/basketball" class="sport-link <?php echo (isset($selected_category) && $selected_category === 'basketball') ? 'active' : ''; ?>">BASKETBALL</a>
          <a href="/category/tennis" class="sport-link <?php echo (isset($selected_category) && $selected_category === 'tennis') ? 'active' : ''; ?>">TENNIS</a>
          <a href="/category/church" class="sport-link <?php echo (isset($selected_category) && $selected_category === 'church') ? 'active' : ''; ?>">CHURCH</a>
          <a href="/category/entertainment" class="sport-link <?php echo (isset($selected_category) && $selected_category === 'entertainment') ? 'active' : ''; ?>">ENTERTAINMENT</a>
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