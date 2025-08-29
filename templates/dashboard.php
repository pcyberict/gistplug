<?php
$page_title = 'Dashboard - PCYBER TV';
$current_page = 'dashboard';
include 'templates/header.php';
?>

<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="mb-4">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p class="text-muted">Manage your account and explore platform features from your dashboard.</p>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h4 class="mb-3">
                <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: currentColor; margin-right: 8px;">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                </svg>
                Your Account
              </h4>
              <div class="mb-3">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                <p><strong>User ID:</strong> <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
                <p class="text-muted">Welcome to your dashboard! From here you can access all platform features.</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h4 class="mb-3">
                <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: currentColor; margin-right: 8px;">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                Quick Actions
              </h4>
              <div class="d-grid gap-2">
                <a href="/" class="btn btn-primary">
                  <svg viewBox="0 0 24 24" style="width: 18px; height: 18px; fill: currentColor; margin-right: 8px;">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                  </svg>
                  Browse Live Streams
                </a>
                <a href="/#chat" class="btn btn-success">
                  <svg viewBox="0 0 24 24" style="width: 18px; height: 18px; fill: currentColor; margin-right: 8px;">
                    <path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h4l4 4 4-4h4c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                  </svg>
                  Join Live Chat
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <h4 class="mb-3">
                <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: currentColor; margin-right: 8px;">
                  <path d="M22 16V4c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2zm-11.5 0L8 13.5l2.5-3L12 12l4.5-6 4.5 6H10.5z"/>
                </svg>
                Platform Statistics
              </h4>
              <div class="row">
                <div class="col-md-3">
                  <div class="text-center p-3">
                    <h5 class="text-primary"><?php echo count(StreamManager::loadStreams()); ?></h5>
                    <p class="text-muted">Live Streams</p>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="text-center p-3">
                    <h5 class="text-success"><?php echo count(StreamManager::loadChat()); ?></h5>
                    <p class="text-muted">Chat Messages</p>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="text-center p-3">
                    <h5 class="text-warning">5</h5>
                    <p class="text-muted">Categories</p>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="text-center p-3">
                    <h5 class="text-info">24/7</h5>
                    <p class="text-muted">Service</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'templates/footer.php'; ?>