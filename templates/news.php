<?php
$page_title = 'Sports News - PCYBER TV';
$current_page = 'news';
$stream_count = count(StreamManager::loadStreams());

// Load published news articles
$published_news = NewsManager::getPublishedNews();
$featured_news = NewsManager::getFeaturedNews();
$categories = NewsManager::getCategories();

include 'templates/header.php';
?>

<div class="container">
  <div class="news-header mb-4">
    <h1 class="page-title">Sports News</h1>
    <p class="page-subtitle">Latest sports news, interviews, highlights, and analysis</p>
  </div>

  <!-- News Categories -->
  <div class="news-categories mb-4">
    <div class="category-tabs">
      <a href="#" class="category-tab active" data-category="all">All</a>
      <?php foreach ($categories as $category): ?>
      <a href="#" class="category-tab" data-category="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars(ucfirst($category)); ?></a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Featured News -->
  <div class="featured-news mb-5">
    <?php if (!empty($featured_news)): ?>
    <?php $featured_article = $featured_news[0]; ?>
    <div class="row">
      <div class="col-lg-8">
        <article class="featured-article">
          <div class="article-image">
            <?php if (!empty($featured_article['featured_image'])): ?>
            <img src="/<?php echo htmlspecialchars($featured_article['featured_image']); ?>" alt="<?php echo htmlspecialchars($featured_article['title']); ?>" class="img-fluid">
            <?php else: ?>
            <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=800&h=400&fit=crop" alt="Sports News" class="img-fluid">
            <?php endif; ?>
            <div class="article-category"><?php echo htmlspecialchars($featured_article['category']); ?></div>
          </div>
          <div class="article-content">
            <h2 class="article-title"><?php echo htmlspecialchars($featured_article['title']); ?></h2>
            <p class="article-excerpt"><?php echo htmlspecialchars($featured_article['excerpt'] ?: substr(strip_tags($featured_article['content']), 0, 200) . '...'); ?></p>
            <div class="article-meta">
              <span class="author">By <?php echo htmlspecialchars($featured_article['author']); ?></span>
              <span class="date"><?php echo NewsManager::formatTimeAgo($featured_article['created_at']); ?></span>
              <span class="read-time"><?php echo ceil(str_word_count(strip_tags($featured_article['content'])) / 200); ?> min read</span>
            </div>
          </div>
        </article>
      </div>
      
      <div class="col-lg-4">
        <div class="trending-news">
          <h3 class="section-title">Recent Articles</h3>
          <?php 
          $recent_articles = array_slice($published_news, 1, 3); // Skip the featured article
          foreach ($recent_articles as $article): 
          ?>
          <article class="trending-item">
            <?php if (!empty($article['featured_image'])): ?>
            <img src="/<?php echo htmlspecialchars($article['featured_image']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="trending-image">
            <?php else: ?>
            <img src="https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=150&h=100&fit=crop" alt="News" class="trending-image">
            <?php endif; ?>
            <div class="trending-content">
              <h4><?php echo htmlspecialchars(substr($article['title'], 0, 60) . (strlen($article['title']) > 60 ? '...' : '')); ?></h4>
              <div class="trending-meta">
                <span class="category"><?php echo htmlspecialchars($article['category']); ?></span>
                <span class="time"><?php echo NewsManager::formatTimeAgo($article['created_at']); ?></span>
              </div>
            </div>
          </article>
          <?php endforeach; ?>
          
          <?php if (empty($recent_articles)): ?>
          <div class="text-center py-4">
            <p class="text-muted">No recent articles available. Check back soon!</p>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php else: ?>
    <div class="text-center py-5">
      <h3>No Featured Articles Yet</h3>
      <p class="text-muted">Featured articles will appear here once published.</p>
    </div>
    <?php endif; ?>
  </div>

  <!-- News Grid -->
  <div class="news-grid">
    <div class="row">
      <?php if (!empty($published_news)): ?>
        <?php 
        // Skip the featured article (first one) and show the rest
        $remaining_articles = array_slice($published_news, empty($featured_news) ? 0 : 1);
        
        if (!empty($remaining_articles)):
          foreach ($remaining_articles as $index => $article): 
        ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <article class="news-card">
            <div class="news-image">
              <?php if (!empty($article['featured_image'])): ?>
              <img src="/<?php echo htmlspecialchars($article['featured_image']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="img-fluid">
              <?php else: ?>
              <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=400&h=250&fit=crop" alt="Sports News" class="img-fluid">
              <?php endif; ?>
              <div class="news-category"><?php echo htmlspecialchars($article['category']); ?></div>
            </div>
            <div class="news-content">
              <h4 class="news-title"><?php echo htmlspecialchars($article['title']); ?></h4>
              <p class="news-excerpt"><?php echo htmlspecialchars($article['excerpt'] ?: substr(strip_tags($article['content']), 0, 120) . '...'); ?></p>
              <div class="news-meta">
                <span class="date"><?php echo NewsManager::formatTimeAgo($article['created_at']); ?></span>
                <span class="read-time"><?php echo ceil(str_word_count(strip_tags($article['content'])) / 200); ?> min read</span>
              </div>
            </div>
          </article>
        </div>
        <?php 
          endforeach;
        else: 
        ?>
        <div class="col-12">
          <div class="text-center py-5">
            <h3>No News Articles Yet</h3>
            <p class="text-muted">Published articles will appear here. Check back soon for the latest sports news!</p>
          </div>
        </div>
        <?php endif; ?>
      <?php else: ?>
      <div class="col-12">
        <div class="text-center py-5">
          <h3>No News Articles Available</h3>
          <p class="text-muted">We're working on bringing you the latest sports news. Please check back soon!</p>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Load More Button -->
  <?php if (count($published_news) > 6): ?>
  <div class="text-center mt-4">
    <button class="btn btn-outline-primary btn-lg load-more-news">Load More Articles</button>
  </div>
  <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleTheme() {
    document.body.classList.toggle('light-mode');
    document.body.classList.toggle('dark-mode');
}

// Category filtering
document.querySelectorAll('.category-tab').forEach(tab => {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all tabs
        document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
        
        // Add active class to clicked tab
        this.classList.add('active');
        
        const category = this.dataset.category;
        
        // Filter articles by category
        const articles = document.querySelectorAll('.news-card');
        articles.forEach(article => {
            const articleCategory = article.querySelector('.news-category').textContent.toLowerCase();
            const parentCard = article.closest('.col-md-6, .col-lg-4');
            
            if (category === 'all' || articleCategory.includes(category.toLowerCase())) {
                parentCard.style.display = 'block';
            } else {
                parentCard.style.display = 'none';
            }
        });
    });
});

// Load more functionality
document.querySelector('.load-more-news')?.addEventListener('click', function() {
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
    
    // Simulate loading
    setTimeout(() => {
        this.innerHTML = 'Load More Articles';
        // In real implementation, this would load more articles via AJAX
    }, 1000);
});
</script>