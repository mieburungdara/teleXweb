<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                Edit Permissions
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Role: <strong><?php echo htmlspecialchars($role['role_name']); ?></strong>
            </p>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Assign Permissions</h3>
        </div>
        <div class="block-content">
            <?php if ($this->session->flashdata('error_message')): ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
            <?php endif; ?>

            <?php echo form_open('admin/update_role_permissions'); ?>
                <input type="hidden" name="role_id" value="<?php echo $role['id']; ?>">

                <div class="row push">
                    <div class="col-lg-8 col-xl-5">
                        <div class="mb-4">
                            <label class="form-label">Permissions</label>
                            <?php
                            $permissions_by_category = [];
                            foreach ($all_permissions as $permission) {
                                $permissions_by_category[$permission['category']][] = $permission;
                            }
                            ?>
                            <?php foreach ($permissions_by_category as $category => $permissions_in_category): ?>
                                <h4 class="h5 mt-3"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $category))); ?></h4>
                                <?php foreach ($permissions_in_category as $permission): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="<?php echo $permission['id']; ?>" id="perm_<?php echo $permission['id']; ?>"
                                            <?php echo in_array($permission['id'], $assigned_permission_ids) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="perm_<?php echo $permission['id']; ?>">
                                            <?php echo htmlspecialchars(str_replace('_', ' ', ucfirst($permission['permission_name']))); ?>
                                        </label>
                                        <?php if (!empty($permission['description'])): ?>
                                            <p class="form-text text-muted ps-4"><?php echo htmlspecialchars($permission['description']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="row items-push">
                    <div class="col-lg-8 col-xl-5">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check-circle me-1"></i> Save Permissions
                        </button>
                        <a href="<?php echo site_url('admin/roles'); ?>" class="btn btn-alt-secondary">
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
