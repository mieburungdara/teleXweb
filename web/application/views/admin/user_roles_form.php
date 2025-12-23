<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                Edit User Roles
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Change the roles for user: <strong><?php echo htmlspecialchars($user['username'] ?? $user['first_name']); ?></strong>
            </p>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Assign Roles</h3>
        </div>
        <div class="block-content">
            <?php if ($this->session->flashdata('errors')): ?>
                <div class="alert alert-danger">
                    <?php echo $this->session->flashdata('errors'); ?>
                </div>
            <?php endif; ?>

            <?php echo form_open('admin/update_user_roles'); ?>
                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

                <div class="row push">
                    <div class="col-lg-8 col-xl-5">
                        <div class="mb-4">
                            <label class="form-label">Select Roles</label>
                            <?php foreach ($all_roles as $role): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="role_ids[]" value="<?php echo $role['id']; ?>" id="role_<?php echo $role['id']; ?>"
                                        <?php echo in_array($role['id'], $assigned_role_ids) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="role_<?php echo $role['id']; ?>">
                                        <?php echo htmlspecialchars($role['role_name']); ?>
                                    </label>
                                    <p class="form-text text-muted ps-4"><?php echo htmlspecialchars($role['description']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="row items-push">
                    <div class="col-lg-8 col-xl-5">
                         <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check-circle me-1"></i> Update Roles
                        </button>
                        <a href="<?php echo site_url('admin/users'); ?>" class="btn btn-alt-secondary">
                           <i class="fa fa-arrow-left me-1"></i> Back to User List
                        </a>
                    </div>
                </div>
                <!-- END Submit -->
            </form>
        </div>
    </div>
</div>
<!-- END Page Content -->