<div class="card">
    <div class="card-header">
        <h1>User Management</h1>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('success_message')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php endif; ?>

        <div class="mb-3">
            <?php if (has_permission('manage_bots')): ?>
                <a href="<?php echo site_url('admin'); ?>" class="btn btn-info">Manage Bots</a>
            <?php endif; ?>
            <?php if (has_permission('manage_roles')): ?>
                <a href="<?php echo site_url('admin/roles'); ?>" class="btn btn-info">Manage Roles</a>
            <?php endif; ?>
        </div>

        <?php if (empty($users)): ?>
            <p>No users registered yet.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Telegram ID</th>
                            <th>Username</th>
                            <th>First Name</th>
                            <th>Role</th>
                            <?php if (has_permission('manage_users')): ?>
                                <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo $user['telegram_id']; ?></td>
                                <td><?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['role_name']); ?> (ID: <?php echo $user['role_id']; ?>)</td>
                                <?php if (has_permission('manage_users')): ?>
                                    <td class="actions">
                                        <a href="<?php echo site_url('admin/edit_user_role/' . $user['id']); ?>" class="btn btn-sm btn-primary">Edit Role</a>
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