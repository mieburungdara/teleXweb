<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                My Notifications
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Your latest alerts and updates.
            </p>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">All Notifications</h3>
        </div>
        <div class="block-content">
            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success_message')): ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error_message')): ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
            <?php endif; ?>

            <?php if (empty($notifications)): ?>
                <p>You have no new notifications.</p>
            <?php else: ?>
                <ul class="list-activity">
                    <?php foreach ($notifications as $notification): ?>
                        <li>
                            <?php if ($notification['read_at']): ?>
                                <i class="fa fa-check-circle text-success"></i>
                            <?php else: ?>
                                <i class="fa fa-envelope text-primary"></i>
                            <?php endif; ?>
                            <div class="fw-semibold"><?php echo htmlspecialchars($notification['subject']); ?></div>
                            <div><?php echo nl2br(htmlspecialchars($notification['body'])); ?></div>
                            <div class="fs-sm text-muted"><?php echo date('F j, Y, g:i a', strtotime($notification['sent_at'])); ?></div>
                            <?php if (!$notification['read_at']): ?>
                                <a href="<?php echo site_url('notifications/mark_as_read/' . $notification['id']); ?>" class="btn btn-sm btn-alt-secondary mt-2">
                                    <i class="fa fa-check me-1"></i> Mark as Read
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- END Page Content -->
