<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                User Management
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Manage all registered users and their roles.
            </p>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <!-- User List Block -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">All Users</h3>
            <div class="block-options">
                <div class="btn-group" role="group" aria-label="Horizontal Primary">
                    <?php if (has_permission('manage_bots')): ?>
                        <a href="<?php echo site_url('admin'); ?>" class="btn btn-sm btn-alt-primary">Manage Bots</a>
                    <?php endif; ?>
                    <?php if (has_permission('manage_roles')): ?>
                        <a href="<?php echo site_url('admin/roles'); ?>" class="btn btn-sm btn-alt-primary">Manage Roles</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="block-content">
            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success_message')): ?>
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <div class="flex-shrink-0">
                        <i class="fa fa-fw fa-check"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="mb-0"><?php echo $this->session->flashdata('success_message'); ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error_message')): ?>
                 <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <div class="flex-shrink-0">
                        <i class="fa fa-fw fa-times"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="mb-0"><?php echo $this->session->flashdata('error_message'); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (empty($users)): ?>
                <p>No users registered yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 80px;">ID</th>
                                <th>Username</th>
                                <th>Telegram ID</th>
                                <th>Role</th>
                                <?php if (has_permission('manage_users')): ?>
                                    <th class="text-center" style="width: 100px;">Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td class="text-center fs-sm"><?php echo $user['id']; ?></td>
                                    <td class="fw-semibold fs-sm"><?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?></td>
                                    <td class="fs-sm"><?php echo $user['telegram_id']; ?></td>
                                    <td class="fs-sm">
                                        <?php if (!empty($user['roles'])): ?>
                                            <?php foreach ($user['roles'] as $role): ?>
                                                <span class="badge bg-primary"><?php echo htmlspecialchars($role['role_name']); ?></span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">No Roles</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if (has_permission('manage_users')): ?>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="<?php echo site_url('admin/edit_user_roles/' . $user['id']); ?>" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Edit Roles">
                                                    <i class="fa fa-pencil-alt"></i>
                                                </a>
                                            </div>
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
    <!-- END User List Block -->
</div>
<!-- END Page Content -->