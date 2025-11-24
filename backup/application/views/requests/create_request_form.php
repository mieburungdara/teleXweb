<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1><?php echo htmlspecialchars($title); ?></h1>
        <p>Post a request for the community (Public Bounty) or send it to a specific creator (Direct Request).</p>

        <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <form action="<?php echo site_url('requests/create'); ?>" method="post">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo set_value('title'); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Detailed Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?php echo set_value('description'); ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="reward_amount" class="form-label">Reward Amount (Credits)</label>
                    <input type="number" step="0.01" class="form-control" id="reward_amount" name="reward_amount" value="<?php echo set_value('reward_amount'); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="priority" class="form-label">Priority</label>
                    <select class="form-select" id="priority" name="priority">
                        <option value="normal" <?php echo set_select('priority', 'normal', TRUE); ?>>Normal</option>
                        <option value="low" <?php echo set_select('priority', 'low'); ?>>Low</option>
                        <option value="urgent" <?php echo set_select('priority', 'urgent'); ?>>Urgent</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="deadline_at" class="form-label">Deadline (Optional)</label>
                    <input type="datetime-local" class="form-control" id="deadline_at" name="deadline_at" value="<?php echo set_value('deadline_at'); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="target_creator_user_id" class="form-label">Direct to Creator (Optional)</label>
                    <input type="number" class="form-control" id="target_creator_user_id" name="target_creator_user_id" value="<?php echo set_value('target_creator_user_id'); ?>" placeholder="Enter Creator User ID">
                    <div class="form-text">Leave blank to post as a Public Bounty.</div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Post Request</button>
            <a href="<?php echo site_url('requests'); ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
