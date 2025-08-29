<?php
$page_title = 'Login - PCYBER TV';
$current_page = 'login';
include 'templates/header.php';
?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="auth-card">
        <div class="auth-header">
          <div class="auth-logo">
            <div class="auth-logo-icon"></div>
            <h2>Welcome Back</h2>
          </div>
          <p class="auth-subtitle">Sign in to your PCYBER TV account</p>
        </div>
        
        <div class="auth-body">
          <form method="POST" action="/login">
            <div class="mb-4">
              <label for="email" class="form-label">Email Address</label>
              <div class="input-group">
                <span class="input-icon">
                  <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; fill: currentColor;">
                    <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                  </svg>
                </span>
                <input type="email" class="form-control auth-input" id="email" name="email" required placeholder="Enter your email">
              </div>
            </div>
            
            <div class="mb-4">
              <label for="password" class="form-label">Password</label>
              <div class="input-group">
                <span class="input-icon">
                  <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; fill: currentColor;">
                    <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                  </svg>
                </span>
                <input type="password" class="form-control auth-input" id="password" name="password" required placeholder="Enter your password">
              </div>
            </div>
            
            <div class="d-grid mb-4">
              <button type="submit" class="btn btn-primary auth-btn">
                <svg viewBox="0 0 24 24" style="width: 18px; height: 18px; fill: currentColor; margin-right: 8px;">
                  <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Sign In
              </button>
            </div>
          </form>
          
          <div class="auth-links">
            <p class="text-center mb-3">
              Don't have an account? 
              <a href="/signup" class="auth-link">Create one here</a>
            </p>
            <p class="text-center">
              <a href="/" class="auth-link">← Back to Home</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.auth-card {
  background: var(--card-bg);
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  margin-top: 3rem;
  margin-bottom: 3rem;
}

.auth-header {
  text-align: center;
  margin-bottom: 2rem;
}

.auth-logo {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 1rem;
}

.auth-logo-icon {
  width: 60px;
  height: 45px;
  background: linear-gradient(45deg, #ff6b35, #f7931e);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  margin-bottom: 1rem;
}

.auth-logo-icon::before {
  content: '';
  width: 30px;
  height: 22px;
  background: white;
  border-radius: 4px;
  transform: skew(-10deg);
}

.auth-logo h2 {
  color: var(--text-color);
  margin: 0;
  font-weight: 700;
  font-size: 1.8rem;
}

.auth-subtitle {
  color: var(--text-color);
  opacity: 0.7;
  margin: 0;
  font-size: 1rem;
}

.auth-body {
  margin-top: 2rem;
}

.form-label {
  color: var(--text-color);
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.input-group {
  position: relative;
}

.input-icon {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-color);
  opacity: 0.6;
  z-index: 10;
}

.auth-input {
  background: rgba(255, 255, 255, 0.05) !important;
  border: 2px solid rgba(255, 255, 255, 0.1) !important;
  border-radius: 12px !important;
  padding: 0.75rem 1rem 0.75rem 3rem !important;
  color: var(--text-color) !important;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.auth-input:focus {
  background: rgba(255, 255, 255, 0.08) !important;
  border-color: #ff6b35 !important;
  box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25) !important;
  outline: none !important;
}

.auth-input::placeholder {
  color: var(--text-color);
  opacity: 0.5;
}

.auth-btn {
  background: linear-gradient(45deg, #ff6b35, #f7931e) !important;
  border: none !important;
  border-radius: 12px !important;
  padding: 0.875rem 1.5rem !important;
  font-weight: 600 !important;
  font-size: 1rem !important;
  color: white !important;
  transition: all 0.3s ease !important;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.auth-btn:hover {
  transform: translateY(-2px) !important;
  box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4) !important;
}

.auth-links {
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.auth-link {
  color: #ff6b35;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.2s ease;
}

.auth-link:hover {
  color: #f7931e;
  text-decoration: underline;
}

@media (max-width: 768px) {
  .auth-card {
    margin: 1rem 0;
    padding: 1.5rem;
  }
  
  .auth-logo h2 {
    font-size: 1.5rem;
  }
}
</style>

<?php include 'templates/footer.php'; ?>