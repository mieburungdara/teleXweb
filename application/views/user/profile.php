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
                <p><strong>Subscription Plan:</strong> <?php echo htmlspecialchars(ucfirst($user->subscription_plan)); ?></p>
                <p><strong>Balance:</strong> $<?php echo htmlspecialchars(number_format($user->balance, 2)); ?></p>
                <!-- Add more profile details here -->
                <a href="<?php echo site_url('users/subscription'); ?>" class="btn btn-primary">Manage Subscription</a>
                <a href="<?php echo site_url('users/balance'); ?>" class="btn btn-info">Manage Balance</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
