<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Subscription Management</h1>
        <p>Welcome, <?php echo htmlspecialchars($user->codename); ?>!</p>

        <div class="card mb-4">
            <div class="card-header">
                Your Current Plan
            </div>
            <div class="card-body">
                <h5 class="card-title">Plan: <?php echo htmlspecialchars(ucfirst($user->subscription_plan)); ?></h5>
                <p class="card-text">Status: <?php echo htmlspecialchars(ucfirst($user->payment_status)); ?></p>
                <?php if ($user->subscription_start_date): ?>
                    <p class="card-text">Started: <?php echo htmlspecialchars($user->subscription_start_date); ?></p>
                <?php endif; ?>
                <?php if ($user->subscription_end_date): ?>
                    <p class="card-text">Ends: <?php echo htmlspecialchars($user->subscription_end_date); ?></p>
                <?php endif; ?>
                <p class="card-text">Benefits: <?php echo htmlspecialchars($plan_benefits[$user->subscription_plan]); ?></p>
                <h6 class="mt-3">Your Current Limits:</h6>
                <ul>
                    <?php foreach ($plan_limits as $limit_name => $limit_value): ?>
                        <li><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $limit_name))); ?>: 
                            <?php echo ($limit_value == -1) ? 'Unlimited' : htmlspecialchars($limit_value); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                Upgrade Your Plan
            </div>
            <div class="card-body">
                <p class="card-text">Explore our premium plans to unlock more features and higher limits.</p>
                <a href="<?php echo site_url('users/upgrade_plan'); ?>" class="btn btn-primary">Upgrade Now</a>
            </div>
        </div>

        <?php if ($user->subscription_plan != 'free' && $user->payment_status == 'active'): ?>
            <div class="card mb-4">
                <div class="card-header">
                    Manage Subscription
                </div>
                <div class="card-body">
                    <p class="card-text">If you wish to cancel your current subscription, you can do so here.</p>
                    <a href="<?php echo site_url('users/cancel_subscription'); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel your subscription?');">Cancel Subscription</a>
                </div>
            </div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header">
                Subscription History
            </div>
            <div class="card-body">
                <?php if (!empty($subscription_history)): ?>
                    <ul class="list-group">
                        <?php foreach ($subscription_history as $sub): ?>
                            <li class="list-group-item">
                                Plan: <?php echo htmlspecialchars(ucfirst($sub->plan_name)); ?> - 
                                Amount: <?php echo htmlspecialchars($sub->amount); ?> <?php echo htmlspecialchars($sub->currency); ?> - 
                                Status: <?php echo htmlspecialchars(ucfirst($sub->status)); ?> - 
                                Started: <?php echo htmlspecialchars($sub->created_at); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No subscription history found.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
