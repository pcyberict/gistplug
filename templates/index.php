<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCYBER TV - Live Sports Streaming</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/static/styles.css">
</head>
<body>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <div class="container-fluid">
        <header class="py-3 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="text-primary">PCYBER TV</h1>
                <nav>
                    <a href="/" class="btn btn-outline-primary me-2">Home</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/dashboard" class="btn btn-outline-success me-2">Dashboard</a>
                        <a href="/logout-user" class="btn btn-outline-danger">Logout</a>
                    <?php else: ?>
                        <a href="/login" class="btn btn-outline-success me-2">Login</a>
                        <a href="/signup" class="btn btn-outline-info">Sign Up</a>
                    <?php endif; ?>
                </nav>
            </div>
        </header>

        <div class="row">
            <div class="col-md-8">
                <h2>Live Streams</h2>
                <?php if (isset($selected_category)): ?>
                    <p class="text-muted">Category: <?php echo htmlspecialchars($selected_category); ?></p>
                    <a href="/" class="btn btn-secondary mb-3">Show All</a>
                <?php endif; ?>
                
                <div class="row" id="streams-container">
                    <?php if (isset($initial_streams)): $streams = $initial_streams; endif; ?>
                    <?php if (!empty($streams)): ?>
                        <?php foreach ($streams as $index => $stream): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($stream['title']); ?></h5>
                                        <p class="card-text">
                                            <small class="text-muted">Platform: <?php echo htmlspecialchars($stream['platform']); ?></small><br>
                                            <small class="text-muted">Category: <?php echo htmlspecialchars($stream['category']); ?></small>
                                        </p>
                                        <a href="/stream/<?php echo $index; ?>" class="btn btn-primary">Watch Stream</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No streams available.</p>
                    <?php endif; ?>
                </div>
                
                <?php if (!isset($selected_category)): ?>
                    <button id="load-more" class="btn btn-outline-primary">Load More Streams</button>
                <?php endif; ?>
            </div>
            
            <div class="col-md-4">
                <h3>Live Chat</h3>
                <div id="chat-messages" class="border p-3 mb-3" style="height: 300px; overflow-y: scroll;">
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
                    <button type="submit" class="btn btn-success">Send Message</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load more streams functionality
        document.getElementById('load-more')?.addEventListener('click', function() {
            const currentStreams = document.querySelectorAll('#streams-container .col-md-6').length;
            fetch(`/load_more_streams?offset=${currentStreams}&limit=6`)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('streams-container');
                    data.streams.forEach((stream, index) => {
                        const streamIndex = currentStreams + index;
                        const streamHtml = `
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">${stream.title}</h5>
                                        <p class="card-text">
                                            <small class="text-muted">Platform: ${stream.platform}</small><br>
                                            <small class="text-muted">Category: ${stream.category}</small>
                                        </p>
                                        <a href="/stream/${streamIndex}" class="btn btn-primary">Watch Stream</a>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.insertAdjacentHTML('beforeend', streamHtml);
                    });
                    
                    if (!data.has_more) {
                        this.style.display = 'none';
                    }
                })
                .catch(console.error);
        });
    </script>
</body>
</html>