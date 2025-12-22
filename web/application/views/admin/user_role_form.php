<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                Edit User Role
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Change the role for user: <strong><?php echo htmlspecialchars($user['username'] ?? $user['first_name']); ?></strong>
            </p>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Assign New Role</h3>
        </div>
        <div class="block-content">
            <?php if ($this->session->flashdata('errors')): ?>
                <div class="alert alert-danger">
                    <?php echo $this->session->flashdata('errors'); ?>
                </div>
            <?php endif; ?>

            <?php echo form_open('admin/update_user_role'); ?>
                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

                <div class="row push">
                    <div class="col-lg-8 col-xl-5">
                        <div class="mb-4">
                            <label class="form-label">Current Role</label>
                            <p class="form-control-plaintext"><strong><?php echo htmlspecialchars($user['role_name']); ?></strong></p>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="role_id">Select New Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="role_id" name="role_id" required>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?php echo $role['id']; ?>" <?php echo set_select('role_id', $role['id'], $user['role_id'] == $role['id']); ?>>
                                        <?php echo htmlspecialchars($role['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="row items-push">
                    <div class="col-lg-8 col-xl-5">
                         <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check-circle me-1"></i> Update Role
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