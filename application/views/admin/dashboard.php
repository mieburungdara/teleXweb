<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Admin Dashboard</h1>
        <p>Welcome to the admin panel.</p>
        <div class="list-group">
            <a href="<?php echo site_url('admin/subscriptions'); ?>" class="list-group-item list-group-item-action">Manage Subscriptions</a>
            <a href="<?php echo site_url('admin/subscription_analytics'); ?>" class="list-group-item list-group-item-action">Subscription Analytics</a>
            <a href="<?php echo site_url('admin/manage_user_balance'); ?>" class="list-group-item list-group-item-action">Manage User Balances</a>
            <!-- Add more admin links here -->
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
