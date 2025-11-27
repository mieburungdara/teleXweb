<div class="card">
    <div class="card-header">
        <h1>My Public Collections</h1>
        <?php if (has_permission('manage_public_collections')): ?>
            <a href="<?php echo site_url('publiccollections/create_edit'); ?>" class="btn btn-primary float-end">Create New Public Collection</a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('success_message')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php endif; ?>

        <?php if (empty($collections)): ?>
            <p>You have no public collections yet. Create one to share your folders!</p>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($collections as $collection): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h5>
                                <a href="<?php echo site_url('publiccollections/create_edit/' . $collection['id']); ?>">
                                    <?php echo htmlspecialchars($collection['name']); ?>
                                </a>
                                <?php if ($collection['is_private']): ?>
                                    <span class="badge bg-secondary">Private</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Public</span>
                                <?php endif; ?>
                            </h5>
                            <small class="text-muted"><?php echo htmlspecialchars($collection['description'] ?? 'No description.'); ?></small><br>
                            <small class="text-muted">Access Code: <strong><?php echo htmlspecialchars($collection['access_code']); ?></strong></small>
                            <?php if (!$collection['is_private']): ?>
                                <small class="text-muted d-block">Shareable Link: <a href="<?php echo site_url('publiccollections/view_public/' . $collection['access_code']); ?>"><?php echo site_url('publiccollections/view_public/' . $collection['access_code']); ?></a></small>
                            <?php endif; ?>
                        </div>
                        <div>
                            <?php if (has_permission('manage_public_collections')): ?>
                                <a href="<?php echo site_url('publiccollections/create_edit/' . $collection['id']); ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="<?php echo site_url('publiccollections/delete/' . $collection['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this public collection?');">Delete</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
