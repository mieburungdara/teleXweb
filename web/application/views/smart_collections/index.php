<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                Smart Collections
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Organize your files dynamically with custom rules.
            </p>
        </div>
        <div class="mt-4 mt-md-0">
             <a href="<?php echo site_url('smartcollections/create_edit'); ?>" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i> Create New
            </a>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">All Smart Collections</h3>
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
                <p>You have no smart collections yet. Create one to get started!</p>
            <?php else: ?>
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Created</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($collections as $collection): ?>
                            <tr>
                                <td class="fw-semibold">
                                    <a href="<?php echo site_url('smartcollections/view_collection/' . $collection['id']); ?>">
                                        <?php echo htmlspecialchars($collection['name']); ?>
                                    </a>
                                </td>
                                <td class="fs-sm text-muted">
                                    <?php echo date('F j, Y', strtotime($collection['created_at'])); ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                         <a href="<?php echo site_url('smartcollections/view_collection/' . $collection['id']); ?>" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="View Files">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="<?php echo site_url('smartcollections/create_edit/' . $collection['id']); ?>" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Edit Rules">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <a href="<?php echo site_url('smartcollections/delete/' . $collection['id']); ?>" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this smart collection?');">
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
