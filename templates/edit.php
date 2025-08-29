<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Stream - PCYBER TV Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/static/admin.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Edit Stream</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/edit/<?php echo $index; ?>">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($stream['title']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="platform" class="form-label">Platform</label>
                                <select class="form-control" id="platform" name="platform" required>
                                    <option value="YouTube" <?php echo $stream['platform'] === 'YouTube' ? 'selected' : ''; ?>>YouTube</option>
                                    <option value="Facebook" <?php echo $stream['platform'] === 'Facebook' ? 'selected' : ''; ?>>Facebook</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="video_id" class="form-label">Video ID/URL</label>
                                <input type="text" class="form-control" id="video_id" name="video_id" value="<?php echo htmlspecialchars($stream['video_id']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($stream['category']); ?>" required>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="/admin" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-success">Update Stream</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>