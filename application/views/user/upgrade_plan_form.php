<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upgrade Plan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Upgrade Your Plan</h1>

        <div class="card mb-4">
            <div class="card-header">
                Choose Your New Plan
            </div>
            <div class="card-body">
                <form action="<?php echo site_url('users/upgrade_plan'); ?>" method="post">
                    <div class="mb-3">
                        <label for="new_plan" class="form-label">Select Plan:</label>
                        <select class="form-select" id="new_plan" name="new_plan" required>
                            <option value="">-- Select a plan --</option>
                            <option value="pro">TeleX Pro</option>
                            <option value="enterprise">TeleX Enterprise</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Proceed to Payment</button>
                    <a href="<?php echo site_url('users/subscription'); ?>" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
