<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                Edit Profile
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Update your personal information.
            </p>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">User Details</h3>
        </div>
        <div class="block-content">
            <?php if ($this->session->flashdata('errors')): ?>
                <div class="alert alert-danger">
                    <?php echo $this->session->flashdata('errors'); ?>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error_message')): ?>
                <div class="alert alert-danger">
                    <?php echo $this->session->flashdata('error_message'); ?>
                </div>
            <?php endif; ?>

            <?php echo form_open('users/update_profile'); ?>
                 <div class="row push">
                    <div class="col-lg-8 col-xl-5">
                        <div class="mb-4">
                            <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo set_value('username', $user['username'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="first_name">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo set_value('first_name', $user['first_name'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo set_value('last_name', $user['last_name'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="row items-push">
                    <div class="col-lg-8 col-xl-5">
                         <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check-circle me-1"></i> Update Profile
                        </button>
                        <a href="<?php echo site_url('users/profile'); ?>" class="btn btn-alt-secondary">
                           <i class="fa fa-arrow-left me-1"></i> Cancel
                        </a>
                    </div>
                </div>
                <!-- END Submit -->
            </form>
        </div>
    </div>
</div>
<!-- END Page Content -->
