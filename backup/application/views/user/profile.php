<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>User Profile</h1>
        <p>Welcome, <?php echo htmlspecialchars($user->codename); ?>!</p>
        <div class="card">
            <div class="card-header">
                Profile Details
            </div>
            <div class="card-body">
                <p><strong>User ID:</strong> <?php echo htmlspecialchars($user->id); ?></p>
                <p><strong>Codename:</strong> <?php echo htmlspecialchars($user->codename); ?></p>

                <p><strong>Balance:</strong> <?php echo htmlspecialchars(number_format($user->balance)); ?> Credits</p>
                <!-- Add more profile details here -->

                <a href="<?php echo site_url('users/balance'); ?>" class="btn btn-info">Manage Balance</a>
                <a href="<?php echo site_url('marketplace'); ?>" class="btn btn-success">Marketplace</a>
                <a href="<?php echo site_url('users/topup_credits'); ?>" class="btn btn-warning">Top Up Credits</a>
            </div>
        </div>

        <!-- Achievements Section -->
        <div class="card mt-4">
            <div class="card-header">
                Achievements
            </div>
            <div class="card-body">
                <?php if (!empty($achievements)): ?>
                    <div class="row">
                        <?php foreach ($achievements as $achievement): ?>
                            <div class="col-md-2 col-sm-4 col-6 text-center mb-3">
                                <img src="<?php echo htmlspecialchars($achievement->badge_icon_url ?: 'https://via.placeholder.com/80'); ?>" 
                                     alt="<?php echo htmlspecialchars($achievement->name); ?>" 
                                     class="img-thumbnail" 
                                     title="<?php echo htmlspecialchars($achievement->name) . ' - ' . htmlspecialchars($achievement->description); ?>"
                                     data-bs-toggle="tooltip" data-bs-placement="top">
                                <p class="mt-2 small"><?php echo htmlspecialchars($achievement->name); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No achievements earned yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
