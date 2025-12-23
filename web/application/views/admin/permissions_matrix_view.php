<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                Permissions Matrix
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Assign permissions to roles across the system.
            </p>
        </div>
        <div class="mt-3 mt-md-0 ms-md-3">
             <a href="<?php echo site_url('admin/roles'); ?>" class="btn btn-sm btn-alt-secondary">
                <i class="fa fa-users-cog"></i> Manage Roles
            </a>
            <a href="<?php echo site_url('admin/permissions'); ?>" class="btn btn-sm btn-alt-secondary">
                <i class="fa fa-key"></i> Manage Permissions
            </a>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Role-Permission Assignments</h3>
        </div>
        <div class="block-content">
            <?php echo form_open('admin/update_permissions_matrix'); ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th style="width: 25%;">Permission</th>
                                <?php foreach ($all_roles as $role): ?>
                                    <th class="text-center"><?php echo htmlspecialchars($role['role_name']); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($permissions_by_category as $category => $permissions): ?>
                                <tr class="table-active">
                                    <td colspan="<?php echo count($all_roles) + 1; ?>" class="fw-bold"><?php echo htmlspecialchars(ucfirst($category)); ?></td>
                                </tr>
                                <?php foreach ($permissions as $permission): ?>
                                    <tr>
                                        <td>
                                            <?php echo htmlspecialchars($permission['permission_name']); ?>
                                            <p class="fs-sm text-muted mb-0"><?php echo htmlspecialchars($permission['description']); ?></p>
                                        </td>
                                        <?php foreach ($all_roles as $role): ?>
                                            <td class="text-center">
                                                <div class="form-check d-inline-block">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="assignments[<?php echo $role['id']; ?>][<?php echo $permission['id']; ?>]" 
                                                           id="perm-<?php echo $role['id']; ?>-<?php echo $permission['id']; ?>"
                                                           <?php echo (isset($assigned_lookup[$role['id']][$permission['id']])) ? 'checked' : ''; ?>>
                                                </div>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="row items-push mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check-circle me-1"></i> Save All Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END Page Content -->
