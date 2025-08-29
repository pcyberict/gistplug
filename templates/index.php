<?php
$page_title = 'PCYBER TV - Live Sports Streaming';
$current_page = 'home';
$stream_count = count(StreamManager::loadStreams());
include 'templates/header.php';
?>

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

<?php include 'templates/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleTheme() {
    document.body.classList.toggle('light-mode');
    document.body.classList.toggle('dark-mode');
}

// Load more streams functionality
document.getElementById('loadMoreBtn')?.addEventListener('click', function() {
    const currentStreams = document.querySelectorAll('.stream-card').length;
    
    fetch(`/load_more_streams?offset=${currentStreams}&limit=6`)
        .then(response => response.json())
        .then(data => {
            if (data.streams && data.streams.length > 0) {
                const streamList = document.getElementById('streamList');
                
                data.streams.forEach((stream, index) => {
                    const streamCard = document.createElement('div');
                    streamCard.className = 'stream-card';
                    
                    let embedHtml = '';
                    if (stream.platform === 'YouTube') {
                        embedHtml = `<iframe src="https://www.youtube.com/embed/${stream.video_id}" allowfullscreen></iframe>`;
                    } else if (stream.platform === 'Facebook') {
                        embedHtml = `<iframe src="https://www.facebook.com/plugins/video.php?href=${encodeURIComponent(stream.video_id)}&show_text=0&width=560" allowfullscreen></iframe>`;
                    } else {
                        embedHtml = '<p class="text-danger p-3">Unsupported platform</p>';
                    }
                    
                    streamCard.innerHTML = `
                        <div class="stream-video">
                            ${embedHtml}
                        </div>
                        <div class="stream-content">
                            <div class="stream-title">${stream.title}</div>
                            <a href="/category/${encodeURIComponent(stream.category)}" class="badge-category text-decoration-none">${stream.category}</a><br>
                            <a href="/stream/${currentStreams + index}" class="btn btn-primary btn-view">View Stream</a>
                        </div>
                    `;
                    
                    streamList.appendChild(streamCard);
                });
                
                if (!data.has_more) {
                    document.getElementById('loadMoreBtn').style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error loading more streams:', error);
        });
});
</script>