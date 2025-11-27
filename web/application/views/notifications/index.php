<div class="card">
    <div class="card-header">
        <h1>My Notifications</h1>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('success_message')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php endif; ?>

        <?php if (empty($notifications)): ?>
            <p>You have no new notifications.</p>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($notifications as $notification): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center <?php echo $notification['read_at'] ? 'text-muted' : 'fw-bold'; ?>">
                        <div>
                            <?php if (!$notification['read_at']): ?>
                                <span class="badge bg-primary rounded-pill me-2">New</span>
                            <?php endif; ?>
                            <h5><?php echo htmlspecialchars($notification['subject']); ?></h5>
                            <p class="mb-1"><?php echo nl2br(htmlspecialchars($notification['body'])); ?></p>
                            <small class="text-muted">Sent: <?php echo date('Y-m-d H:i', strtotime($notification['sent_at'])); ?></small>
                        </div>
                        <?php if (!$notification['read_at']): ?>
                            <a href="<?php echo site_url('notifications/mark_as_read/' . $notification['id']); ?>" class="btn btn-sm btn-outline-success">Mark as Read</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
