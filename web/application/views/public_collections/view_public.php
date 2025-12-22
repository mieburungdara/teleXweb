<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Public Collection: <?php echo htmlspecialchars($collection['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .hero { background-color: #fff; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="hero p-5 my-4 bg-light rounded-3">
            <h1 class="display-5 fw-bold"><?php echo htmlspecialchars($collection['name']); ?></h1>
            <p class="fs-4"><?php echo htmlspecialchars($collection['description'] ?? 'No description provided.'); ?></p>
            <p class="text-muted">
                Created by: <?php echo htmlspecialchars($collection['username'] ?? 'Anonymous'); ?>
            </p>
        </div>
        <!-- END Header -->

        <!-- Folders -->
        <?php if (empty($folders)): ?>
            <div class="alert alert-info text-center">This collection currently contains no folders.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($folders as $folder): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <a href="<?php echo site_url('folders/view_shared/' . $folder['code']); ?>" class="card h-100 text-decoration-none text-dark shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fa fa-folder me-2"></i><?php echo htmlspecialchars($folder['folder_name']); ?></h5>
                                <p class="card-text text-muted"><?php echo htmlspecialchars($folder['description'] ?? 'No description.'); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <!-- END Folders -->

        <!-- Footer -->
        <footer class="py-3 my-4">
            <p class="text-center text-muted">&copy; <?php echo date('Y'); ?> teleXweb</p>
        </footer>
        <!-- END Footer -->
    </div>
</body>
</html>
