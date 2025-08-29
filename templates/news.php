<?php
$page_title = 'Sports News - PCYBER TV';
$current_page = 'news';
$stream_count = count(StreamManager::loadStreams());
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
      <a href="#" class="category-tab" data-category="football">Football</a>
      <a href="#" class="category-tab" data-category="transfer">Transfer News</a>
      <a href="#" class="category-tab" data-category="champions">Champions League</a>
      <a href="#" class="category-tab" data-category="premier">Premier League</a>
      <a href="#" class="category-tab" data-category="tennis">Tennis</a>
      <a href="#" class="category-tab" data-category="basketball">Basketball</a>
    </div>
  </div>

  <!-- Featured News -->
  <div class="featured-news mb-5">
    <div class="row">
      <div class="col-lg-8">
        <article class="featured-article">
          <div class="article-image">
            <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=800&h=400&fit=crop" alt="Champions League Trophy" class="img-fluid">
            <div class="article-category">Champions League</div>
          </div>
          <div class="article-content">
            <h2 class="article-title">PSG to face Barcelona in Champions League, Real Madrid draw Man City & Liverpool</h2>
            <p class="article-excerpt">The UEFA Champions League draw has been conducted, setting up blockbuster matchups including PSG vs Barcelona and Real Madrid facing Manchester City in what promises to be an exciting tournament.</p>
            <div class="article-meta">
              <span class="author">By Sports Desk</span>
              <span class="date">2 hours ago</span>
              <span class="read-time">3 min read</span>
            </div>
          </div>
        </article>
      </div>
      
      <div class="col-lg-4">
        <div class="trending-news">
          <h3 class="section-title">Most Read</h3>
          <article class="trending-item">
            <img src="https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=150&h=100&fit=crop" alt="Transfer News" class="trending-image">
            <div class="trending-content">
              <h4>Transfer News LIVE: Newcastle agree record deal for Woltemade, Spurs set to sign Simons</h4>
              <div class="trending-meta">
                <span class="category">Transfer News</span>
                <span class="time">Updated 1h ago</span>
              </div>
            </div>
          </article>
          
          <article class="trending-item">
            <img src="https://images.unsplash.com/photo-1522778119026-d647f0596c20?w=150&h=100&fit=crop" alt="Premier League" class="trending-image">
            <div class="trending-content">
              <h4>Alexander-Arnold dropped from England squad, Anderson and Spence receive maiden call-ups</h4>
              <div class="trending-meta">
                <span class="category">Premier League</span>
                <span class="time">3h ago</span>
              </div>
            </div>
          </article>
          
          <article class="trending-item">
            <img src="https://images.unsplash.com/photo-1560272564-c83b66b1ad12?w=150&h=100&fit=crop" alt="Football Manager" class="trending-image">
            <div class="trending-content">
              <h4>Fenerbahce part ways with Jose Mourinho after failing to reach Champions League</h4>
              <div class="trending-meta">
                <span class="category">Transfer News</span>
                <span class="time">4h ago</span>
              </div>
            </div>
          </article>
        </div>
      </div>
    </div>
  </div>

  <!-- News Grid -->
  <div class="news-grid">
    <div class="row">
      <!-- Transfer News Section -->
      <div class="col-12 mb-4">
        <div class="section-header">
          <h3 class="section-title">Transfer News</h3>
          <a href="#" class="section-link">More Transfer News →</a>
        </div>
      </div>
      
      <div class="col-md-6 col-lg-4 mb-4">
        <article class="news-card">
          <div class="news-image">
            <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=400&h=250&fit=crop" alt="Xavi Simons" class="img-fluid">
            <div class="news-category">Transfer News</div>
          </div>
          <div class="news-content">
            <h4 class="news-title">Tottenham close in on shock Xavi Simons signing after hijacking Chelsea's deal</h4>
            <p class="news-excerpt">Spurs are reportedly set to complete a surprise move for the Dutch midfielder...</p>
            <div class="news-meta">
              <span class="date">2h ago</span>
              <span class="read-time">2 min read</span>
            </div>
          </div>
        </article>
      </div>
      
      <div class="col-md-6 col-lg-4 mb-4">
        <article class="news-card">
          <div class="news-image">
            <img src="https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=400&h=250&fit=crop" alt="West Ham" class="img-fluid">
            <div class="news-category">Premier League</div>
          </div>
          <div class="news-content">
            <h4 class="news-title">Midfielder Mateus Fernandes joins West Ham in £40 million deal from Southampton</h4>
            <p class="news-excerpt">The Portuguese midfielder completes his move to the London Stadium...</p>
            <div class="news-meta">
              <span class="date">3h ago</span>
              <span class="read-time">3 min read</span>
            </div>
          </div>
        </article>
      </div>
      
      <div class="col-md-6 col-lg-4 mb-4">
        <article class="news-card">
          <div class="news-image">
            <img src="https://images.unsplash.com/photo-1522778119026-d647f0596c20?w=400&h=250&fit=crop" alt="Bayern Munich" class="img-fluid">
            <div class="news-category">Bundesliga</div>
          </div>
          <div class="news-content">
            <h4 class="news-title">Bundesliga clubs can't compete with Premier League spending power, says Kompany</h4>
            <p class="news-excerpt">Bayern Munich manager discusses the financial gap between leagues...</p>
            <div class="news-meta">
              <span class="date">4h ago</span>
              <span class="read-time">4 min read</span>
            </div>
          </div>
        </article>
      </div>

      <!-- Premier League Section -->
      <div class="col-12 mb-4">
        <div class="section-header">
          <h3 class="section-title">Premier League</h3>
          <a href="#" class="section-link">More Premier League →</a>
        </div>
      </div>
      
      <div class="col-md-6 col-lg-4 mb-4">
        <article class="news-card">
          <div class="news-image">
            <img src="https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=400&h=250&fit=crop" alt="Fantasy Football" class="img-fluid">
            <div class="news-category">Fantasy</div>
          </div>
          <div class="news-content">
            <h4 class="news-title">Fantasy Premier League: The best picks and hidden gems for Gameweek 3</h4>
            <p class="news-excerpt">Our expert analysis of the top fantasy football picks for the upcoming gameweek...</p>
            <div class="news-meta">
              <span class="date">5h ago</span>
              <span class="read-time">5 min read</span>
            </div>
          </div>
        </article>
      </div>
      
      <div class="col-md-6 col-lg-4 mb-4">
        <article class="news-card">
          <div class="news-image">
            <img src="https://images.unsplash.com/photo-1551958219-acbc608c6377?w=400&h=250&fit=crop" alt="Newcastle United" class="img-fluid">
            <div class="news-category">Premier League</div>
          </div>
          <div class="news-content">
            <h4 class="news-title">Howe says Isak future at Newcastle remains uncertain as club close in on Woltemade</h4>
            <p class="news-excerpt">Newcastle manager addresses speculation about the Swedish striker's future...</p>
            <div class="news-meta">
              <span class="date">6h ago</span>
              <span class="read-time">3 min read</span>
            </div>
          </div>
        </article>
      </div>
      
      <div class="col-md-6 col-lg-4 mb-4">
        <article class="news-card">
          <div class="news-image">
            <img src="https://images.unsplash.com/photo-1577223625816-7546f13df25d?w=400&h=250&fit=crop" alt="Liverpool Arsenal" class="img-fluid">
            <div class="news-category">Premier League</div>
          </div>
          <div class="news-content">
            <h4 class="news-title">Slot coy on Isak chances as Liverpool prepare for blockbuster Arsenal battle</h4>
            <p class="news-excerpt">The Reds manager previews the highly anticipated clash with the Gunners...</p>
            <div class="news-meta">
              <span class="date">7h ago</span>
              <span class="read-time">4 min read</span>
            </div>
          </div>
        </article>
      </div>

      <!-- Tennis Section -->
      <div class="col-12 mb-4">
        <div class="section-header">
          <h3 class="section-title">Tennis - US Open</h3>
          <a href="#" class="section-link">More Tennis →</a>
        </div>
      </div>
      
      <div class="col-md-6 col-lg-4 mb-4">
        <article class="news-card">
          <div class="news-image">
            <img src="https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=400&h=250&fit=crop" alt="US Open Tennis" class="img-fluid">
            <div class="news-category">US Open</div>
          </div>
          <div class="news-content">
            <h4 class="news-title">US Open LIVE: Rybakina takes on Raducanu in huge clash, Alcaraz in early action</h4>
            <p class="news-excerpt">Day six of the US Open features blockbuster matches including Emma Raducanu vs Elena Rybakina...</p>
            <div class="news-meta">
              <span class="date">1h ago</span>
              <span class="read-time">Live</span>
            </div>
          </div>
        </article>
      </div>
      
      <div class="col-md-6 col-lg-4 mb-4">
        <article class="news-card">
          <div class="news-image">
            <img src="https://images.unsplash.com/photo-1622163642998-1ea32b0bbc85?w=400&h=250&fit=crop" alt="Tommy Paul" class="img-fluid">
            <div class="news-category">US Open</div>
          </div>
          <div class="news-content">
            <h4 class="news-title">Tommy Paul battles through late-night classic against Nuno Borges at US Open</h4>
            <p class="news-excerpt">The American overcame a tough challenge in an entertaining late-night encounter...</p>
            <div class="news-meta">
              <span class="date">8h ago</span>
              <span class="read-time">3 min read</span>
            </div>
          </div>
        </article>
      </div>
      
      <div class="col-md-6 col-lg-4 mb-4">
        <article class="news-card">
          <div class="news-image">
            <img src="https://images.unsplash.com/photo-1630766803227-c5d5ff96a5b0?w=400&h=250&fit=crop" alt="Coco Gauff" class="img-fluid">
            <div class="news-category">US Open</div>
          </div>
          <div class="news-content">
            <h4 class="news-title">Coco Gauff gets through serving woes in difficult match to make US Open third round</h4>
            <p class="news-excerpt">The defending champion overcame early struggles to advance at Flushing Meadows...</p>
            <div class="news-meta">
              <span class="date">10h ago</span>
              <span class="read-time">4 min read</span>
            </div>
          </div>
        </article>
      </div>
    </div>
  </div>

  <!-- Load More Button -->
  <div class="text-center mt-4">
    <button class="btn btn-outline-primary btn-lg load-more-news">Load More Articles</button>
  </div>
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
        
        // Simple filter simulation (in real app, this would filter actual content)
        console.log('Filtering by category:', category);
    });
});

// Load more functionality
document.querySelector('.load-more-news')?.addEventListener('click', function() {
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
    
    // Simulate loading
    setTimeout(() => {
        this.innerHTML = 'Load More Articles';
        // In real implementation, this would load more articles
    }, 1000);
});
</script>