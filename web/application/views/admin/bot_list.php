<div class="card">
    <div class="card-header">
        <h1>Bot Management</h1>
        <?php if (has_permission('manage_bots')): ?>
            <a href="<?php echo site_url('admin/form'); ?>" class="btn btn-primary float-end">Add New Bot</a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('success_message')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('errors')): ?>
            <div class="alert alert-danger">
                <h4>Validation Errors:</h4>
                <?php echo $this->session->flashdata('errors'); ?>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <?php if (has_permission('manage_users')): ?>
                <a href="<?php echo site_url('admin/users'); ?>" class="btn btn-info">Manage Users</a>
            <?php endif; ?>
            <?php if (has_permission('manage_roles')): ?>
                <a href="<?php echo site_url('admin/roles'); ?>" class="btn btn-info">Manage Roles</a>
            <?php endif; ?>
        </div>

        <?php if (empty($bots)): ?>
            <p>No bots registered yet.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Telegram Bot ID</th>
                            <th>Name</th>
                            <th>Token (Partial)</th>
                            <?php if (has_permission('manage_bots')): ?>
                                <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bots as $bot): ?>
                            <tr>
                                <td><?php echo $bot['id']; ?></td>
                                <td><?php echo $bot['bot_id_telegram']; ?></td>
                                <td><?php echo htmlspecialchars($bot['name']); ?></td>
                                <td><?php echo substr($bot['token'], 0, 10); ?>...</td>
                                <?php if (has_permission('manage_bots')): ?>
                                    <td class="actions">
                                        <a href="<?php echo site_url('admin/form/' . $bot['id']); ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="<?php echo site_url('admin/delete/' . $bot['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this bot?');">Delete</a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>