<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                Manage Roles
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Edit permissions for each user role.
            </p>
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
                                <th class="text-center" style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($roles as $role): ?>
                                <tr>
                                    <td class="text-center fs-sm"><?php echo $role['id']; ?></td>
                                    <td class="fw-semibold fs-sm"><?php echo htmlspecialchars($role['name']); ?></td>
                                    <td class="fs-sm"><?php echo htmlspecialchars($role['description']); ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="<?php echo site_url('admin/edit_role_permissions/' . $role['id']); ?>" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Edit Permissions">
                                                <i class="fa fa-pencil-alt"></i> Edit Permissions
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
