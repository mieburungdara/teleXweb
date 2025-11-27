<div class="card">
    <div class="card-header">
        <h1>Edit User Role: <?php echo htmlspecialchars($user['username'] ?? $user['first_name']); ?></h1>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('errors')): ?>
            <div class="alert alert-danger">
                <h4>Validation Errors:</h4>
                <?php echo $this->session->flashdata('errors'); ?>
            </div>
        <?php endif; ?>

        <?php echo form_open('admin/update_user_role'); ?>
            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

            <div class="mb-3">
                <label for="current_role" class="form-label">Current Role:</label>
                <p class="form-control-static" id="current_role"><?php echo htmlspecialchars($user['role_name']); ?> (ID: <?php echo htmlspecialchars($user['role_id']); ?>)</p>
            </div>

            <div class="mb-3">
                <label for="role_id" class="form-label">Select New Role:</label>
                <select class="form-select" id="role_id" name="role_id" required>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?php echo $role['id']; ?>" <?php echo set_select('role_id', $role['id'], $user['role_id'] == $role['id']); ?>>
                            <?php echo htmlspecialchars($role['name']); ?> (ID: <?php echo htmlspecialchars($role['id']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php echo form_error('role_id', '<div class="text-danger">', '</div>'); ?>
            </div>

            <button type="submit" class="btn btn-primary">Update Role</button>
            <a href="<?php echo site_url('admin/users'); ?>" class="btn btn-secondary">Back to User List</a>
        </form>
    </div>
</div>