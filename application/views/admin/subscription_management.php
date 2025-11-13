<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Subscription Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Admin Subscription Management</h1>
        <a href="<?php echo site_url('admin'); ?>" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <?php if (!empty($subscriptions)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Plan Name</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Period Start</th>
                        <th>Period End</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subscriptions as $sub): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($sub->id); ?></td>
                            <td><?php echo htmlspecialchars($sub->user_id); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($sub->plan_name)); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($sub->status)); ?></td>
                            <td><?php echo htmlspecialchars($sub->amount); ?> <?php echo htmlspecialchars($sub->currency); ?></td>
                            <td><?php echo htmlspecialchars($sub->current_period_start); ?></td>
                            <td><?php echo htmlspecialchars($sub->current_period_end); ?></td>
                            <td>
                                <a href="<?php echo site_url('admin/edit_user_subscription/' . $sub->user_id); ?>" class="btn btn-sm btn-primary">Edit User Plan</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No subscriptions found.</p>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
