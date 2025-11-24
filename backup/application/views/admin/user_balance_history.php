<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Balance History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Balance History for <?php echo htmlspecialchars($user->codename); ?></h1>
        <a href="<?php echo site_url('admin/manage_user_balance/' . $user->id); ?>" class="btn btn-secondary mb-3">Back to Balance Management</a>

        <div class="card">
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
                                                <th>Admin ID</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($transactions as $tx): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($tx->id); ?></td>
                                                    <td><?php echo htmlspecialchars(ucfirst($tx->transaction_type)); ?></td>
                                                    <td><?php htmlspecialchars(number_format($tx->amount, 2)); ?></td>
                                                    <td><?php echo htmlspecialchars($tx->description); ?></td>
                                                    <td><?php echo htmlspecialchars($tx->admin_id); ?></td>
                                                    <td><?php echo htmlspecialchars($tx->created_at); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <nav aria-label="Page navigation">
                                        <?php echo $pagination_links; ?>
                                    </nav>
                                <?php else: ?>
                                    <p>No transactions found for this user.</p>
                                <?php endif; ?>
                            </div>        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
