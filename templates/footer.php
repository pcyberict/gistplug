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
</script>
</body>
</html>