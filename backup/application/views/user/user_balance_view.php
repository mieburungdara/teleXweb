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
                <h5 class="card-title">Balance: <?php echo htmlspecialchars(number_format($user->balance)); ?> Credits</h5>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                Top-up Your Balance
            </div>
            <div class="card-body">
                <p class="card-text">To top-up your Credits, please visit our dedicated top-up page where you can choose a package and follow the manual payment instructions.</p>
                <a href="<?php echo site_url('users/topup_credits'); ?>" class="btn btn-warning">Go to Top-up Page</a>
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
                                    <td><?php echo htmlspecialchars(number_format($tx->amount)); ?> Credits</td>
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
