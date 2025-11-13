<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Balance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Your Balance</h1>
        <p>Welcome, <?php echo htmlspecialchars($user->codename); ?>!</p>

        <div class="card mb-4">
            <div class="card-header">
                Current Balance
            </div>
            <div class="card-body">
                <h5 class="card-title">Balance: $<?php echo htmlspecialchars(number_format($user->balance, 2)); ?></h5>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                Top-up Your Balance
            </div>
            <div class="card-body">
                <p class="card-text">To top-up your balance, please contact an administrator with your payment details.</p>
                <p class="card-text"><strong>Admin Contact:</strong> <?php echo htmlspecialchars($admin_telegram_username); ?></p>
                <p class="card-text"><strong>Your User ID:</strong> <?php echo htmlspecialchars($user->id); ?></p>
                <p class="card-text">Please provide your User ID and proof of payment to the admin for verification. Once verified, your balance will be updated manually.</p>
                <p class="card-text text-muted">Expected response time: 24-48 hours.</p>
            </div>
        </div>

        <a href="<?php echo site_url('users'); ?>" class="btn btn-primary">Back to Profile</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
