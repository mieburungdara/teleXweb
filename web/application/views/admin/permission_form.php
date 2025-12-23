<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                <?php echo isset($permission) ? 'Edit Permission' : 'Add New Permission'; ?>
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Manage a system permission.
            </p>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title"><?php echo isset($permission) ? 'Edit Permission' : 'Permission Details'; ?></h3>
        </div>
        <div class="block-content">
            <?php if ($this->session->flashdata('errors')): ?>
                <div class="alert alert-danger">
                    <p class="mb-0"><strong>Validation Errors:</strong></p>
                    <ul class="mb-0">
                        <?php echo $this->session->flashdata('errors'); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php echo form_open('admin/save_permission'); ?>
                <?php if (isset($permission)): ?>
                    <input type="hidden" name="id" value="<?php echo $permission['id']; ?>">
                <?php endif; ?>

                <div class="row push">
                    <div class="col-lg-8 col-xl-5">
                        <div class="mb-4">
                            <label class="form-label" for="permission_name">Permission Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="permission_name" name="permission_name" value="<?php echo set_value('permission_name', isset($permission) ? $permission['permission_name'] : ''); ?>" required>
                            <small class="form-text text-muted">A unique, programmatic name (e.g., 'manage_users').</small>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="category">Category <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="category" name="category" value="<?php echo set_value('category', isset($permission) ? $permission['category'] : 'general'); ?>" required>
                            <small class="form-text text-muted">A group name for organization (e.g., 'admin', 'file').</small>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?php echo set_value('description', isset($permission) ? $permission['description'] : ''); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="row items-push">
                    <div class="col-lg-8 col-xl-5">
                         <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check-circle me-1"></i> Save Permission
                        </button>
                        <a href="<?php echo site_url('admin/permissions'); ?>" class="btn btn-alt-secondary">
                            <i class="fa fa-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <!-- END Submit -->
            </form>
        </div>
    </div>
</div>
<!-- END Page Content -->
