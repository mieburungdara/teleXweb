<!-- Hero -->
<div class="bg-image" style="background-image: url('<?php echo base_url('assets/dashmix/media/various/bg_profile.jpg'); ?>');">
    <div class="bg-primary-dark-op">
        <div class="content content-full text-center">
            <div class="my-3">
                <img class="img-avatar img-avatar-thumb" src="<?php echo base_url('assets/dashmix/media/avatars/avatar13.jpg'); ?>" alt="">
            </div>
            <h1 class="h2 text-white mb-0"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h1>
            <h2 class="h4 fw-normal text-white-75">
                <?php if (!empty($user['roles'])): ?>
                    <?php foreach ($user['roles'] as $role): ?>
                        <span class="badge rounded-pill bg-primary"><?php echo htmlspecialchars($role['role_name']); ?></span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </h2>
            <a class="btn btn-alt-secondary" href="<?php echo site_url('users/edit_profile'); ?>">
                <i class="fa fa-fw fa-pencil-alt me-1"></i> Edit Profile
            </a>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content content-full">
    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success_message')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error_message')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="row items-push">
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1">
                    <div class="item rounded-3 bg-body mx-auto my-3">
                        <i class="fa fa-folder fa-lg text-primary"></i>
                    </div>
                    <div class="fs-1 fw-bold"><?php echo $user['total_folders']; ?></div>
                    <div class="text-muted mb-3">Total Folders</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1">
                    <div class="item rounded-3 bg-body mx-auto my-3">
                        <i class="fa fa-file fa-lg text-primary"></i>
                    </div>
                    <div class="fs-1 fw-bold"><?php echo $user['total_files']; ?></div>
                    <div class="text-muted mb-3">Total Files</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1">
                    <div class="item rounded-3 bg-body mx-auto my-3">
                        <i class="fa fa-star fa-lg text-primary"></i>
                    </div>
                    <div class="fs-1 fw-bold"><?php echo $user['xp']; ?></div>
                    <div class="text-muted mb-3">XP (Level <?php echo $user['level']; ?>)</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full">
                    <div class="item rounded-3 bg-body mx-auto my-3">
                        <i class="fa fa-wallet fa-lg text-primary"></i>
                    </div>
                    <div class="fs-1 fw-bold">Rp <?php echo number_format($user['balance'], 2, ',', '.'); ?></div>
                    <div class="text-muted mb-3">Balance</div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Stats -->

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Recent Folders</h3>
                </div>
                <div class="block-content">
                    <?php if (!empty($recent_folders)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($recent_folders as $folder): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo htmlspecialchars($folder['folder_name']); ?>
                                    <small class="text-muted"><?php echo date('Y-m-d', strtotime($folder['created_at'])); ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-center p-3 text-muted">No folders created yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Recent Files</h3>
                </div>
                <div class="block-content">
                     <?php if (!empty($recent_files)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($recent_files as $file): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="text-truncate" style="max-width: 300px;"><?php echo htmlspecialchars($file['original_file_name']); ?></span>
                                    <small class="text-muted"><?php echo date('Y-m-d', strtotime($file['created_at'])); ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-center p-3 text-muted">No files uploaded yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- END Recent Activity -->
</div>
<!-- END Page Content -->