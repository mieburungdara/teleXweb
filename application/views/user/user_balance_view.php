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

        <div class="card mt-4">
            <div class="card-header">
                Transaction History
            </div>
            <div class="card-body">
                <?php if (!empty($transactions)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $tx): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($tx->id); ?></td>
                                    <td><?php echo htmlspecialchars(ucfirst($tx->transaction_type)); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($tx->amount, 2)); ?></td>
                                    <td><?php echo htmlspecialchars($tx->description); ?></td>
                                    <td><?php echo htmlspecialchars($tx->created_at); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation">
                        <?php echo $pagination_links; ?>
                    </nav>
                <?php else: ?>
                    <p>No transactions found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
