<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - PCYBER TV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/static/admin.css">
</head>
<body>
    <div class="container-fluid">
        <header class="py-3 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="text-danger">PCYBER TV Admin</h1>
                <nav>
                    <a href="/" class="btn btn-outline-primary me-2">View Site</a>
                    <a href="/logout" class="btn btn-outline-danger">Logout</a>
                </nav>
            </div>
        </header>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Add New Stream</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/admin">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="platform" class="form-label">Platform</label>
                                <select class="form-control" id="platform" name="platform" required>
                                    <option value="YouTube">YouTube</option>
                                    <option value="Facebook">Facebook</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="video_id" class="form-label">Video ID/URL</label>
                                <input type="text" class="form-control" id="video_id" name="video_id" required>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <input type="text" class="form-control" id="category" name="category" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">Add Stream</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4>Manage Streams</h4>
                            <form method="GET" action="/admin" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" placeholder="Search..." value="<?php echo htmlspecialchars($search_query ?? ''); ?>">
                                <button type="submit" class="btn btn-outline-primary">Search</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($paginated_streams)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Platform</th>
                                            <th>Category</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($paginated_streams as $index => $stream): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($stream['title']); ?></td>
                                                <td><?php echo htmlspecialchars($stream['platform']); ?></td>
                                                <td><?php echo htmlspecialchars($stream['category']); ?></td>
                                                <td>
                                                    <a href="/edit/<?php echo $index; ?>" class="btn btn-sm btn-warning">Edit</a>
                                                    <form method="POST" action="/delete/<?php echo $index; ?>" style="display: inline;">
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <?php if ($total_pages > 1): ?>
                                <nav>
                                    <ul class="pagination justify-content-center">
                                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                                <a class="page-link" href="/admin?page=<?php echo $i; ?><?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : ''; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </nav>
                            <?php endif; ?>
                        <?php else: ?>
                            <p>No streams found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>