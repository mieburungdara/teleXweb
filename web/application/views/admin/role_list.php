<div class="card">
    <div class="card-header">
        <h1>Manage Roles</h1>
        <p>Edit permissions for each role.</p>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('success_message')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php endif; ?>

        <?php if (empty($roles)): ?>
            <p>No roles found.</p>
        <?php else: ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Role Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roles as $role): ?>
                        <tr>
                            <td><?php echo $role['id']; ?></td>
                            <td><?php echo htmlspecialchars($role['name']); ?></td>
                            <td><?php echo htmlspecialchars($role['description']); ?></td>
                            <td>
                                <a href="<?php echo site_url('admin/edit_role_permissions/' . $role['id']); ?>" class="btn btn-sm btn-primary">Edit Permissions</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
