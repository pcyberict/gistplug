<?php
$page_title = htmlspecialchars($stream['title']) . ' - PCYBER TV';
$current_page = 'streams';
include 'templates/header.php';
?>

<div class="container">
  <div class="row">
    <div class="col-md-8">
      <div class="card">
        <div class="card-body p-0">
          <div class="stream-header p-3 border-bottom">
            <h3 class="mb-2"><?php echo htmlspecialchars($stream['title']); ?></h3>
            <div class="d-flex align-items-center gap-3">
              <span class="badge bg-primary"><?php echo htmlspecialchars($stream['platform']); ?></span>
              <span class="badge bg-secondary"><?php echo htmlspecialchars($stream['category']); ?></span>
            </div>
          </div>
          
          <div class="stream-video-container">
            <?php if ($stream['platform'] === 'YouTube'): ?>
              <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/<?php echo htmlspecialchars($stream['video_id']); ?>" 
                        title="<?php echo htmlspecialchars($stream['title']); ?>"
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                </iframe>
              </div>
            <?php elseif ($stream['platform'] === 'Facebook'): ?>
              <div class="ratio ratio-16x9">
                <iframe src="<?php echo htmlspecialchars($stream['video_id']); ?>" 
                        title="<?php echo htmlspecialchars($stream['title']); ?>"
                        frameborder="0" 
                        allowfullscreen>
                </iframe>
              </div>
            <?php else: ?>
              <div class="alert alert-warning m-3">
                <h5>Unsupported Platform</h5>
                <p>Platform: <?php echo htmlspecialchars($stream['platform']); ?></p>
                <p>Video ID: <?php echo htmlspecialchars($stream['video_id']); ?></p>
              </div>
            <?php endif; ?>
          </div>
          
          <div class="stream-actions p-3 border-top">
            <div class="d-flex justify-content-between align-items-center">
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary btn-sm">
                  <svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor; margin-right: 4px;">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                  </svg>
                  Like
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm">
                  <svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor; margin-right: 4px;">
                    <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.05 4.11c-.05.23-.09.46-.09.7 0 1.66 1.34 3 3 3s3-1.34 3-3-1.34-3-3-3z"/>
                  </svg>
                  Share
                </button>
              </div>
              <a href="/" class="btn btn-outline-primary">
                <svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor; margin-right: 4px;">
                  <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Back to Streams
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h4 class="mb-3">
            <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: currentColor; margin-right: 8px;">
              <path d="M20 2H4c-1.1 0-2-.9-2 2v12c0 1.1.9 2 2 2h4l4 4 4-4h4c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
            </svg>
            Live Chat
          </h4>
          
          <div class="chat-messages" style="height: 400px; overflow-y: auto; background: rgba(255, 255, 255, 0.02); border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
            <?php
            $chat = StreamManager::loadChat();
            foreach ($chat as $message):
            ?>
              <div class="chat-message mb-3">
                <div class="d-flex align-items-start">
                  <div class="chat-avatar me-2">
                    <div style="width: 32px; height: 32px; background: linear-gradient(45deg, #ff6b35, #f7931e); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 12px;">
                      <?php echo strtoupper(substr($message['user'], 0, 1)); ?>
                    </div>
                  </div>
                  <div class="chat-content flex-grow-1">
                    <div class="chat-username" style="font-weight: 600; font-size: 0.85rem; color: #ff6b35; margin-bottom: 2px;">
                      <?php echo htmlspecialchars($message['user']); ?>
                    </div>
                    <div class="chat-text" style="font-size: 0.9rem; line-height: 1.4;">
                      <?php echo htmlspecialchars($message['message']); ?>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          
          <form method="POST" action="/chat">
            <div class="mb-3">
              <input type="text" name="user" class="form-control" placeholder="Your name" required style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: var(--text-color);">
            </div>
            <div class="mb-3">
              <textarea name="message" class="form-control" placeholder="Your message" required rows="3" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: var(--text-color); resize: vertical;"></textarea>
            </div>
            <button type="submit" class="btn btn-success w-100">
              <svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor; margin-right: 4px;">
                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
              </svg>
              Send Message
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.stream-video-container iframe {
  background: #000;
}

.chat-message:hover {
  background: rgba(255, 255, 255, 0.02);
  border-radius: 8px;
  padding: 8px;
  margin: -8px;
}

.form-control:focus {
  background: rgba(255, 255, 255, 0.08) !important;
  border-color: #ff6b35 !important;
  box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25) !important;
}

.stream-header {
  background: rgba(255, 255, 255, 0.02);
}

.stream-actions {
  background: rgba(255, 255, 255, 0.02);
}
</style>

<?php include 'templates/footer.php'; ?>