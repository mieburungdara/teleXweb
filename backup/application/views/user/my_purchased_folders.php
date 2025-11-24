<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Purchased Folders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>My Purchased Folders</h1>
        <p>Welcome, <?php echo htmlspecialchars($user->codename); ?>!</p>

        <a href="<?php echo site_url('users'); ?>" class="btn btn-secondary mb-3">Back to Profile</a>

        <?php if (!empty($purchased_folders)): ?>
            <div class="row">
                <?php foreach ($purchased_folders as $folder): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($folder->folder_name); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($folder->description); ?></p>
                                <p class="card-text"><strong>Price Paid:</strong> $<?php echo htmlspecialchars(number_format($folder->price_at_purchase, 2)); ?></p>
                                <p class="card-text"><strong>Purchased On:</strong> <?php echo htmlspecialchars($folder->purchase_date); ?></p>
                                <p class="card-text"><strong>Seller ID:</strong> <?php echo htmlspecialchars($folder->seller_id); ?></p>
                                <a href="https://t.me/<?php echo htmlspecialchars($bot_username); ?>?start=folder_access_<?php echo htmlspecialchars($folder->folder_id); ?>_<?php echo htmlspecialchars($user->id); ?>" class="btn btn-primary" target="_blank">Get Content via Bot</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                You haven't purchased any folders yet.
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
