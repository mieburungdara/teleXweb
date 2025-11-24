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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="<?php echo site_url('requests/create'); ?>" class="btn btn-primary">Post a New Request</a>
            <div class="btn-group">
                <a href="<?php echo site_url('requests?sort_by=time'); ?>" class="btn btn-outline-secondary">Newest</a>
                <a href="<?php echo site_url('requests?sort_by=reward'); ?>" class="btn btn-outline-secondary">Highest Reward</a>
            </div>
        </div>

        <?php if (!empty($bounties)): ?>
            <div class="list-group">
                <?php foreach ($bounties as $bounty): ?>
                    <a href="<?php echo site_url('requests/view/' . $bounty->id); ?>" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><?php echo htmlspecialchars($bounty->title); ?></h5>
                            <small>Posted by: <?php echo htmlspecialchars($bounty->requester_username); ?></small>
                        </div>
                        <p class="mb-1"><?php echo nl2br(htmlspecialchars(substr($bounty->description, 0, 150))); ?>...</p>
                        <strong class="text-success"><?php echo htmlspecialchars(number_format($bounty->reward_amount, 2)); ?> Credits</strong>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No open public bounties at the moment. Why not post one?</div>
        <?php endif; ?>
    </div>
</body>
</html>
