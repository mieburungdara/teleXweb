<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                Manage Roles
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Create, edit, and assign permissions to user roles.
            </p>
        </div>
        <div class="mt-3 mt-md-0 ms-md-3">
            <a href="<?php echo site_url('admin/role_form'); ?>" class="btn btn-sm btn-alt-primary">
                <i class="fa fa-plus-circle"></i> Add New Role
            </a>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <!-- Role List Block -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">All Roles</h3>
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

            <?php if (empty($roles)): ?>
                <p>No roles found.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 80px;">ID</th>
                                <th>Role Name</th>
                                <th>Description</th>
                                <th class="text-center" style="width: 250px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($roles as $role): ?>
                                <tr>
                                    <td class="text-center fs-sm"><?php echo $role['id']; ?></td>
                                    <td class="fw-semibold fs-sm"><?php echo htmlspecialchars($role['role_name']); ?></td>
                                    <td class="fs-sm"><?php echo htmlspecialchars($role['description']); ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="<?php echo site_url('admin/role_form/' . $role['id']); ?>" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Edit Role">
                                                <i class="fa fa-fw fa-pencil-alt"></i>
                                            </a>
                                            <a href="<?php echo site_url('admin/edit_role_permissions/' . $role['id']); ?>" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Edit Permissions">
                                                <i class="fa fa-fw fa-key"></i>
                                            </a>
                                            <a href="<?php echo site_url('admin/delete_role/' . $role['id']); ?>" class="btn btn-sm btn-alt-danger" data-bs-toggle="tooltip" title="Delete Role" onclick="return confirm('Are you sure you want to delete this role?');">
                                                <i class="fa fa-fw fa-times"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- END Role List Block -->
</div>
<!-- END Page Content -->
