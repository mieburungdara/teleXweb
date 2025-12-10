<!-- User Profile Dashboard -->
<div class="container-fluid">
    <h1 class="mb-4">My Dashboard</h1>

    <?php if ($this->session->flashdata('success_message')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error_message')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
    <?php endif; ?>

    <!-- Stat Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Folders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $user['total_folders']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Files</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $user['total_files']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">XP</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $user['xp']; ?> (Level: <?php echo $user['level']; ?>)</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Balance</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?php echo number_format($user['balance'], 2, ',', '.'); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Folders</h6>
                </div>
                <div class="card-body">
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
                        <p class="text-center text-muted">No folders created yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Files</h6>
                </div>
                <div class="card-body">
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
                        <p class="text-center text-muted">No files uploaded yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Info and Actions -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Information</h6>
                </div>
                <div class="card-body">
                    <p><strong>Telegram ID:</strong> <?php echo htmlspecialchars($user['telegram_id']); ?></p>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?></p>
                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
                    <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role_name']); ?></p>
                    <p><strong>Member Since:</strong> <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                    
                    <hr>

                    <a href="<?php echo site_url('users/edit_profile'); ?>" class="btn btn-primary">Edit Profile</a>
                    <a href="<?php echo site_url('folders'); ?>" class="btn btn-secondary">Go to My Folders</a>
                </div>
            </div>
        </div>
    </div>
</div>