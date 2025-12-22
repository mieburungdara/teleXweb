<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                Dashboard
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Selamat datang, <?php echo $this->session->userdata('username') ?? 'Pengguna'; ?>!
            </p>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <!-- Overview -->
    <div class="row items-push">
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1">
                    <div class="item rounded-3 bg-body mx-auto my-3">
                        <i class="fa fa-users fa-lg text-primary"></i>
                    </div>
                    <div class="fs-1 fw-bold">0</div>
                    <div class="text-muted mb-3">Total Files</div>
                </div>
                <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
                    <a class="fw-medium" href="<?php echo site_url('files'); ?>">
                        View all files
                        <i class="fa fa-arrow-right ms-1 opacity-25"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1">
                    <div class="item rounded-3 bg-body mx-auto my-3">
                        <i class="fa fa-folder fa-lg text-primary"></i>
                    </div>
                    <div class="fs-1 fw-bold">0</div>
                    <div class="text-muted mb-3">Total Folders</div>
                </div>
                <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
                     <a class="fw-medium" href="<?php echo site_url('folders'); ?>">
                        View all folders
                        <i class="fa fa-arrow-right ms-1 opacity-25"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
             <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1">
                    <div class="item rounded-3 bg-body mx-auto my-3">
                        <i class="fa fa-chart-line fa-lg text-primary"></i>
                    </div>
                    <div class="fs-1 fw-bold">0</div>
                    <div class="text-muted mb-3">Total Hits</div>
                </div>
                <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
                    <a class="fw-medium" href="javascript:void(0)">
                        Explore analytics
                        <i class="fa fa-arrow-right ms-1 opacity-25"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full">
                    <div class="item rounded-3 bg-body mx-auto my-3">
                        <i class="fa fa-wallet fa-lg text-primary"></i>
                    </div>
                    <div class="fs-1 fw-bold">$0</div>
                    <div class="text-muted mb-3">Total Earnings</div>
                </div>
                <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
                    <a class="fw-medium" href="javascript:void(0)">
                        Withdrawal options
                        <i class="fa fa-arrow-right ms-1 opacity-25"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- END Overview -->

    <!-- User Info and Admin Actions -->
    <div class="row">
        <div class="col-md-6">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">User Information</h3>
                </div>
                <div class="block-content">
                    <?php if ($this->session->userdata('logged_in')): ?>
                        <table class="table table-borderless fs-sm">
                            <tbody>
                                <tr>
                                    <td class="fw-medium" style="width: 30%;">Username</td>
                                    <td><?php echo $this->session->userdata('username') ?? 'N/A'; ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Telegram ID</td>
                                    <td><?php echo $this->session->userdata('telegram_id') ?? 'N/A'; ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">User Code</td>
                                    <td><?php echo $this->session->userdata('user_code') ?? 'N/A'; ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Role</td>
                                    <td><?php echo $this->session->userdata('role_name'); ?> (ID: <?php echo $this->session->userdata('role_id'); ?>)</td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-danger">You are not logged in.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if (is_admin()): ?>
        <div class="col-md-6">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Admin Actions</h3>
                </div>
                <div class="block-content">
                    <div class="list-group">
                        <a href="<?php echo site_url('admin/dashboard'); ?>" class="list-group-item list-group-item-action">Manage Dashboard</a>
                        <a href="<?php echo site_url('admin/users'); ?>" class="list-group-item list-group-item-action">Manage Users</a>
                        <a href="<?php echo site_url('admin/roles'); ?>" class="list-group-item list-group-item-action">Manage Roles</a>
                        <a href="<?php echo site_url('admin/tagmanagement'); ?>" class="list-group-item list-group-item-action">Manage Tags</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <!-- END User Info and Admin Actions -->
</div>
<!-- END Page Content -->