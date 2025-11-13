<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Subscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Subscription for User: <?php echo htmlspecialchars($user->codename); ?> (ID: <?php echo htmlspecialchars($user->id); ?>)</h1>
        <a href="<?php echo site_url('admin/subscriptions'); ?>" class="btn btn-secondary mb-3">Back to Subscriptions</a>

        <form action="<?php echo site_url('admin/edit_user_subscription/' . $user->id); ?>" method="post">
            <div class="mb-3">
                <label for="subscription_plan" class="form-label">Subscription Plan</label>
                <select class="form-select" id="subscription_plan" name="subscription_plan" required>
                    <option value="free" <?php echo ($user->subscription_plan == 'free') ? 'selected' : ''; ?>>Free</option>
                    <option value="pro" <?php echo ($user->subscription_plan == 'pro') ? 'selected' : ''; ?>>TeleX Pro</option>
                    <option value="enterprise" <?php echo ($user->subscription_plan == 'enterprise') ? 'selected' : ''; ?>>TeleX Enterprise</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="payment_status" class="form-label">Payment Status</label>
                <input type="text" class="form-control" id="payment_status" name="payment_status" value="<?php echo htmlspecialchars($user->payment_status); ?>" required>
            </div>
            <div class="mb-3">
                <label for="subscription_start_date" class="form-label">Subscription Start Date</label>
                <input type="datetime-local" class="form-control" id="subscription_start_date" name="subscription_start_date" value="<?php echo htmlspecialchars(str_replace(' ', 'T', $user->subscription_start_date)); ?>">
            </div>
            <div class="mb-3">
                <label for="subscription_end_date" class="form-label">Subscription End Date</label>
                <input type="datetime-local" class="form-control" id="subscription_end_date" name="subscription_end_date" value="<?php echo htmlspecialchars(str_replace(' ', 'T', $user->subscription_end_date)); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
