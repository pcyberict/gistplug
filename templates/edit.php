<?php
$page_title = 'Edit Stream - PCYBER TV Admin';
$current_page = 'admin';
include 'templates/header.php';
?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card">
        <div class="card-body">
          <div class="edit-header mb-4">
            <h3 class="mb-2">
              <svg viewBox="0 0 24 24" style="width: 28px; height: 28px; fill: currentColor; margin-right: 8px;">
                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
              </svg>
              Edit Stream
            </h3>
            <p class="text-muted mb-0">Update stream information and settings</p>
          </div>
          
          <form method="POST" action="/edit/<?php echo $index; ?>">
            <div class="mb-4">
              <label for="title" class="form-label">Stream Title</label>
              <div class="input-group">
                <span class="input-icon">
                  <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; fill: currentColor;">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                  </svg>
                </span>
                <input type="text" class="form-control admin-input" id="title" name="title" 
                       value="<?php echo htmlspecialchars($stream['title']); ?>" required 
                       placeholder="Enter stream title">
              </div>
            </div>
            
            <div class="mb-4">
              <label for="platform" class="form-label">Platform</label>
              <div class="input-group">
                <span class="input-icon">
                  <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; fill: currentColor;">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                  </svg>
                </span>
                <select class="form-control admin-input" id="platform" name="platform" required>
                  <option value="YouTube" <?php echo $stream['platform'] === 'YouTube' ? 'selected' : ''; ?>>YouTube</option>
                  <option value="Facebook" <?php echo $stream['platform'] === 'Facebook' ? 'selected' : ''; ?>>Facebook</option>
                </select>
              </div>
            </div>
            
            <div class="mb-4">
              <label for="video_id" class="form-label">Video ID/URL</label>
              <div class="input-group">
                <span class="input-icon">
                  <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; fill: currentColor;">
                    <path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H6.5C3.24 7 0 10.24 0 13.5S3.24 20 6.5 20H11v-1.5H6.5C4.15 18.5 2 16.35 2 13.5zm4.4 0c0-.55.45-1 1-1h5.3c.55 0 1 .45 1 1s-.45 1-1 1H9.3c-.55 0-1-.45-1-1zm10.2 2.5H13v1.5h5.5c3.26 0 5.5-3.24 5.5-6.5S21.76 3 18.5 3H13v1.5h5.5c2.35 0 4.5 2.15 4.5 4.5s-2.15 4.5-4.5 4.5z"/>
                  </svg>
                </span>
                <input type="text" class="form-control admin-input" id="video_id" name="video_id" 
                       value="<?php echo htmlspecialchars($stream['video_id']); ?>" required 
                       placeholder="Enter video ID or URL">
              </div>
              <div class="form-text">For YouTube: Enter video ID or full URL</div>
            </div>
            
            <div class="mb-4">
              <label for="category" class="form-label">Category</label>
              <div class="input-group">
                <span class="input-icon">
                  <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; fill: currentColor;">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                </span>
                <input type="text" class="form-control admin-input" id="category" name="category" 
                       value="<?php echo htmlspecialchars($stream['category']); ?>" required 
                       placeholder="e.g. football, basketball, tennis">
              </div>
            </div>
            
            <div class="edit-actions">
              <div class="d-flex justify-content-between">
                <a href="/admin" class="btn btn-outline-secondary">
                  <svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor; margin-right: 4px;">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                  </svg>
                  Cancel
                </a>
                <button type="submit" class="btn btn-success admin-btn">
                  <svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor; margin-right: 4px;">
                    <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                  </svg>
                  Update Stream
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.edit-header {
  text-align: center;
  padding-bottom: 1rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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

.admin-input {
  background: rgba(255, 255, 255, 0.05) !important;
  border: 2px solid rgba(255, 255, 255, 0.1) !important;
  border-radius: 12px !important;
  padding: 0.75rem 1rem 0.75rem 3rem !important;
  color: var(--text-color) !important;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.admin-input:focus {
  background: rgba(255, 255, 255, 0.08) !important;
  border-color: #ff6b35 !important;
  box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25) !important;
  outline: none !important;
}

.admin-input::placeholder {
  color: var(--text-color);
  opacity: 0.5;
}

.form-text {
  color: var(--text-color);
  opacity: 0.6;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.admin-btn {
  background: linear-gradient(45deg, #28a745, #20c997) !important;
  border: none !important;
  border-radius: 12px !important;
  padding: 0.75rem 1.5rem !important;
  font-weight: 600 !important;
  color: white !important;
  transition: all 0.3s ease !important;
}

.admin-btn:hover {
  transform: translateY(-2px) !important;
  box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4) !important;
}

.btn-outline-secondary {
  border: 2px solid rgba(255, 255, 255, 0.2) !important;
  color: var(--text-color) !important;
  background: transparent !important;
  border-radius: 12px !important;
  padding: 0.75rem 1.5rem !important;
  font-weight: 600 !important;
  transition: all 0.3s ease !important;
}

.btn-outline-secondary:hover {
  background: rgba(255, 255, 255, 0.1) !important;
  border-color: rgba(255, 255, 255, 0.3) !important;
  color: var(--text-color) !important;
  transform: translateY(-2px) !important;
}

.edit-actions {
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

@media (max-width: 768px) {
  .edit-actions .d-flex {
    flex-direction: column;
    gap: 1rem;
  }
}
</style>

<?php include 'templates/footer.php'; ?>