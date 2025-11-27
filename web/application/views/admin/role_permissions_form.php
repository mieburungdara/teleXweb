<div class="card">
    <div class="card-header">
        <h1>Edit Permissions for Role: <?php echo htmlspecialchars($role['name']); ?></h1>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php endif; ?>

        <?php echo form_open('admin/update_role_permissions'); ?>
            <input type="hidden" name="role_id" value="<?php echo $role['id']; ?>">

            <div class="mb-3">
                <label class="form-label">Permissions</label>
                <?php foreach ($all_permissions as $permission): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="<?php echo $permission; ?>" id="perm_<?php echo $permission; ?>"
                            <?php echo in_array($permission, $role_permissions) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="perm_<?php echo $permission; ?>">
                            <?php echo str_replace('_', ' ', ucfirst($permission)); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="btn btn-primary">Save Permissions</button>
            <a href="<?php echo site_url('admin/roles'); ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
