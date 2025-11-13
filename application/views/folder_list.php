<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Folders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>My Folders</h1>
        <a href="<?php echo site_url('users'); ?>" class="btn btn-secondary mb-3">Back to Profile</a>

        <?php if (!empty($folders)): ?>
            <div class="row">
                <?php foreach ($folders as $folder): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($folder->folder_name); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($folder->description); ?></p>
                                <p class="card-text"><strong>Price:</strong> $<?php echo htmlspecialchars(number_format($folder->price, 2)); ?></p>
                                <p class="card-text"><strong>For Sale:</strong> <?php echo $folder->is_for_sale ? 'Yes' : 'No'; ?></p>
                                <a href="<?php echo site_url('folders/detail/' . $folder->id); ?>" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                You have no folders yet.
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
