<div class="card">
    <div class="card-header">
        <h1>Tag Management</h1>
        <p>Manage existing tags and find potential duplicates.</p>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('success_message')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php endif; ?>

        <div class="mb-3">
            <a href="<?php echo site_url('admin/tagmanagement/find_duplicates'); ?>" class="btn btn-warning">Find Duplicate Tags</a>
        </div>

        <?php if (empty($tags)): ?>
            <p>No tags found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tag Name</th>
                            <th>Created By</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tags as $tag): ?>
                            <tr>
                                <td><?php echo $tag['id']; ?></td>
                                <td><?php echo htmlspecialchars($tag['tag_name']); ?></td>
                                <td><?php echo htmlspecialchars($tag['username'] ?? 'N/A'); ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($tag['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
