<div class="card">
    <div class="card-header">
        <h1>Edit Profile</h1>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('errors')): ?>
            <div class="alert alert-danger">
                <h4>Validation Errors:</h4>
                <?php echo $this->session->flashdata('errors'); ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php endif; ?>

        <?php echo form_open('users/update_profile'); ?>
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo set_value('username', $user['username'] ?? ''); ?>" required>
                <?php echo form_error('username', '<div class="text-danger">', '</div>'); ?>
            </div>

            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo set_value('first_name', $user['first_name'] ?? ''); ?>" required>
                <?php echo form_error('first_name', '<div class="text-danger">', '</div>'); ?>
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo set_value('last_name', $user['last_name'] ?? ''); ?>">
                <?php echo form_error('last_name', '<div class="text-danger">', '</div>'); ?>
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
            <a href="<?php echo site_url('users/profile'); ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
