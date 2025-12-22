<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                My Public Collections
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Manage your shared collections of folders.
            </p>
        </div>
        <div class="mt-4 mt-md-0">
             <?php if (has_permission('manage_public_collections')): ?>
                <a href="<?php echo site_url('publiccollections/create_edit'); ?>" class="btn btn-primary">
                    <i class="fa fa-plus me-1"></i> Create New
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">All Collections</h3>
        </div>
        <div class="block-content">
            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success_message')): ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error_message')): ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
            <?php endif; ?>

            <?php if (empty($collections)): ?>
                <p>You have no public collections yet. Create one to share your folders!</p>
            <?php else: ?>
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Shareable Link</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($collections as $collection): ?>
                            <tr>
                                <td class="fw-semibold">
                                    <a href="<?php echo site_url('publiccollections/create_edit/' . $collection['id']); ?>">
                                        <?php echo htmlspecialchars($collection['name']); ?>
                                    </a>
                                    <div class="fs-sm text-muted"><?php echo htmlspecialchars($collection['description'] ?? 'No description.'); ?></div>
                                </td>
                                <td>
                                    <?php if ($collection['is_private']): ?>
                                        <span class="badge bg-secondary">Private</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Public</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!$collection['is_private']): ?>
                                        <a href="<?php echo site_url('publiccollections/view_public/' . $collection['access_code']); ?>" target="_blank">
                                            <?php echo site_url('publiccollections/view_public/' . $collection['access_code']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="<?php echo site_url('publiccollections/create_edit/' . $collection['id']); ?>" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Edit">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <a href="<?php echo site_url('publiccollections/delete/' . $collection['id']); ?>" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this public collection?');">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- END Page Content -->
