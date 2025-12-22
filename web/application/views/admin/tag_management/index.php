<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                Tag Management
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Manage existing tags and find potential duplicates.
            </p>
        </div>
        <div class="mt-4 mt-md-0">
            <a href="<?php echo site_url('admin/tagmanagement/find_duplicates'); ?>" class="btn btn-warning">
                <i class="fa fa-search-plus me-1"></i> Find Duplicate Tags
            </a>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">All Tags</h3>
        </div>
        <div class="block-content">
            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success_message')): ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error_message')): ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
            <?php endif; ?>

            <?php if (empty($tags)): ?>
                <p>No tags found.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 80px;">ID</th>
                                <th>Tag Name</th>
                                <th>Created By</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tags as $tag): ?>
                                <tr>
                                    <td class="text-center fs-sm"><?php echo $tag['id']; ?></td>
                                    <td class="fw-semibold fs-sm"><?php echo htmlspecialchars($tag['tag_name']); ?></td>
                                    <td class="fs-sm"><?php echo htmlspecialchars($tag['username'] ?? 'N/A'); ?></td>
                                    <td class="fs-sm"><?php echo date('Y-m-d H:i', strtotime($tag['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- END Page Content -->
