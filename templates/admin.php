<?php
$page_title = 'Admin Panel - PCYBER TV';
$current_page = 'admin';
include 'templates/header.php';
?>

<div class="container">
  <div class="admin-header mb-4">
    <div class="row align-items-center">
      <div class="col">
        <h2 class="text-danger mb-1">
          <svg viewBox="0 0 24 24" style="width: 28px; height: 28px; fill: currentColor; margin-right: 8px;">
            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
          </svg>
          PCYBER TV Admin Panel
        </h2>
        <p class="text-muted mb-0">Manage streams, monitor platform activity, and configure settings</p>
      </div>
      <div class="col-auto">
        <a href="/" class="btn btn-outline-primary me-2">
          <svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor; margin-right: 4px;">
            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
          </svg>
          View Site
        </a>
        <a href="/logout" class="btn btn-outline-danger">
          <svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor; margin-right: 4px;">
            <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.59L17 17l5-5z"/>
          </svg>
          Logout
        </a>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h4 class="mb-3">
            <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: currentColor; margin-right: 8px;">
              <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
            </svg>
            Add New Stream
          </h4>
          <form method="POST" action="/admin">
            <div class="mb-3">
              <label for="title" class="form-label">Stream Title</label>
              <input type="text" class="form-control" id="title" name="title" required 
                     style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: var(--text-color);">
            </div>
            <div class="mb-3">
              <label for="platform" class="form-label">Platform</label>
              <select class="form-control" id="platform" name="platform" required
                      style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: var(--text-color);">
                <option value="YouTube">YouTube</option>
                <option value="Facebook">Facebook</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="video_id" class="form-label">Video ID/URL</label>
              <input type="text" class="form-control" id="video_id" name="video_id" required
                     style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: var(--text-color);">
              <div class="form-text">For YouTube: Enter video ID or full URL</div>
            </div>
            <div class="mb-3">
              <label for="category" class="form-label">Category</label>
              <input type="text" class="form-control" id="category" name="category" required
                     style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: var(--text-color);"
                     placeholder="e.g. football, basketball, tennis">
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-success">
                <svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor; margin-right: 4px;">
                  <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                </svg>
                Add Stream
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    
    <div class="col-md-8">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">
              <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: currentColor; margin-right: 8px;">
                <path d="M9 11H7v6h2v-6zm4 0h-2v6h2v-6zm4 0h-2v6h2v-6zm2-7H3v2h2v13h14V6h2V4H19zm-2 0H7V2h10v2z"/>
              </svg>
              Manage Streams
            </h4>
            <form method="GET" action="/admin" class="d-flex">
              <input type="text" name="search" class="form-control me-2" placeholder="Search streams..." 
                     value="<?php echo htmlspecialchars($search_query ?? ''); ?>"
                     style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: var(--text-color);">
              <button type="submit" class="btn btn-outline-primary">
                <svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor;">
                  <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                </svg>
              </button>
            </form>
          </div>
          
          <?php if (!empty($paginated_streams)): ?>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead style="background: rgba(255, 255, 255, 0.05);">
                  <tr>
                    <th style="border-color: rgba(255, 255, 255, 0.1); color: var(--text-color);">Title</th>
                    <th style="border-color: rgba(255, 255, 255, 0.1); color: var(--text-color);">Platform</th>
                    <th style="border-color: rgba(255, 255, 255, 0.1); color: var(--text-color);">Category</th>
                    <th style="border-color: rgba(255, 255, 255, 0.1); color: var(--text-color);">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($paginated_streams as $index => $stream): ?>
                    <tr style="background: rgba(255, 255, 255, 0.02);">
                      <td style="border-color: rgba(255, 255, 255, 0.1); color: var(--text-color);">
                        <div class="d-flex align-items-center">
                          <div class="me-2" style="width: 40px; height: 30px; background: linear-gradient(45deg, #ff6b35, #f7931e); border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                            <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; fill: white;">
                              <path d="M8 5v14l11-7z"/>
                            </svg>
                          </div>
                          <div>
                            <div style="font-weight: 600;"><?php echo htmlspecialchars($stream['title']); ?></div>
                            <small class="text-muted"><?php echo htmlspecialchars($stream['video_id']); ?></small>
                          </div>
                        </div>
                      </td>
                      <td style="border-color: rgba(255, 255, 255, 0.1);">
                        <span class="badge bg-primary"><?php echo htmlspecialchars($stream['platform']); ?></span>
                      </td>
                      <td style="border-color: rgba(255, 255, 255, 0.1);">
                        <span class="badge bg-secondary"><?php echo htmlspecialchars($stream['category']); ?></span>
                      </td>
                      <td style="border-color: rgba(255, 255, 255, 0.1);">
                        <div class="btn-group" role="group">
                          <a href="/edit/<?php echo $index; ?>" class="btn btn-sm btn-warning">
                            <svg viewBox="0 0 24 24" style="width: 14px; height: 14px; fill: currentColor; margin-right: 2px;">
                              <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                            </svg>
                            Edit
                          </a>
                          <form method="POST" action="/delete/<?php echo $index; ?>" style="display: inline;">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this stream?')">
                              <svg viewBox="0 0 24 24" style="width: 14px; height: 14px; fill: currentColor; margin-right: 2px;">
                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                              </svg>
                              Delete
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
              <nav class="mt-4">
                <ul class="pagination justify-content-center">
                  <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                      <a class="page-link" href="/admin?page=<?php echo $i; ?><?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : ''; ?>" 
                         style="background: <?php echo $i == $page ? '#ff6b35' : 'rgba(255, 255, 255, 0.05)'; ?>; border-color: rgba(255, 255, 255, 0.1); color: var(--text-color);">
                        <?php echo $i; ?>
                      </a>
                    </li>
                  <?php endfor; ?>
                </ul>
              </nav>
            <?php endif; ?>
          <?php else: ?>
            <div class="text-center py-5">
              <svg viewBox="0 0 24 24" style="width: 64px; height: 64px; fill: #6c757d; margin-bottom: 16px;">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
              </svg>
              <h5 class="text-muted">No streams found</h5>
              <p class="text-muted">Add a new stream to get started</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.admin-header {
  background: rgba(255, 255, 255, 0.02);
  border-radius: 12px;
  padding: 1.5rem;
}

.form-control:focus {
  background: rgba(255, 255, 255, 0.08) !important;
  border-color: #ff6b35 !important;
  box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25) !important;
}

.table-hover tbody tr:hover {
  background: rgba(255, 255, 255, 0.05) !important;
}

.page-link:hover {
  background: rgba(255, 107, 53, 0.1) !important;
  border-color: #ff6b35 !important;
}
</style>

<?php include 'templates/footer.php'; ?>