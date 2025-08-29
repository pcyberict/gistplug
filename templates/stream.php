<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($stream['title']); ?> - PCYBER TV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/static/styles.css">
</head>
<body>
    <div class="container-fluid">
        <header class="py-3 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="text-primary">PCYBER TV</h1>
                <nav>
                    <a href="/" class="btn btn-outline-primary">Back to Home</a>
                </nav>
            </div>
        </header>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3><?php echo htmlspecialchars($stream['title']); ?></h3>
                        <p class="text-muted mb-0">
                            Platform: <?php echo htmlspecialchars($stream['platform']); ?> | 
                            Category: <?php echo htmlspecialchars($stream['category']); ?>
                        </p>
                    </div>
                    <div class="card-body">
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
                            <div class="alert alert-warning">
                                <p>Unsupported platform: <?php echo htmlspecialchars($stream['platform']); ?></p>
                                <p>Video ID: <?php echo htmlspecialchars($stream['video_id']); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <h3>Live Chat</h3>
                <div id="chat-messages" class="border p-3 mb-3" style="height: 400px; overflow-y: scroll;">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>