<div class="card">
    <div class="card-header">
        <h1>Smart Collections</h1>
        <a href="<?php echo site_url('smartcollections/create_edit'); ?>" class="btn btn-primary float-end">Create New Collection</a>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('success_message')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php endif; ?>

        <?php if (empty($collections)): ?>
            <p>You have no smart collections yet. Create one to organize your files dynamically!</p>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($collections as $collection): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h5><a href="<?php echo site_url('smartcollections/view_collection/' . $collection['id']); ?>"><?php echo htmlspecialchars($collection['name']); ?></a></h5>
                            <small class="text-muted">Created: <?php echo date('Y-m-d H:i', strtotime($collection['created_at'])); ?></small>
                        </div>
                        <div>
                            <a href="<?php echo site_url('smartcollections/create_edit/' . $collection['id']); ?>" class="btn btn-sm btn-outline-primary">Edit Rules</a>
                            <a href="<?php echo site_url('smartcollections/delete/' . $collection['id']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this smart collection?');">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
