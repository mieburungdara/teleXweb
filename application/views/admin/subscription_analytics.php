<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Analytics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Subscription Analytics</h1>
        <a href="<?php echo site_url('admin'); ?>" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Active Subscribers</h5>
                        <p class="card-text fs-3"><?php echo htmlspecialchars($total_active_subscribers); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">New Subscribers (Last 30 Days)</h5>
                        <p class="card-text fs-3"><?php echo htmlspecialchars($new_subscribers_last_30_days); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Churn Rate (Last 30 Days)</h5>
                        <p class="card-text fs-3"><?php echo htmlspecialchars(number_format($churn_rate_last_30_days, 2)); ?>%</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Revenue (Last 30 Days)</h5>
                        <p class="card-text fs-3">$<?php echo htmlspecialchars(number_format($revenue_last_30_days, 2)); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        Subscription Status Distribution
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($status_distribution as $status): ?>
                            <li class="list-group-item"><?php echo htmlspecialchars(ucfirst($status->status)); ?>: <?php echo htmlspecialchars($status->count); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        Subscribers by Plan
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($subscribers_by_plan as $plan): ?>
                            <li class="list-group-item"><?php echo htmlspecialchars(ucfirst($plan->plan_name)); ?>: <?php echo htmlspecialchars($plan->count); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
