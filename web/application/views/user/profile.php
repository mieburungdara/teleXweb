<div class="card">
    <div class="card-header">
        <h1>User Profile: <?php echo htmlspecialchars($user['username'] ?? $user['first_name']); ?></h1>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('success_message')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <p><strong>Telegram ID:</strong> <?php echo htmlspecialchars($user['telegram_id']); ?></p>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?></p>
                <p><strong>First Name:</strong> <?php echo htmlspecialchars($user['first_name']); ?></p>
                <p><strong>Last Name:</strong> <?php echo htmlspecialchars($user['last_name'] ?? 'N/A'); ?></p>
                <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role_name']); ?></p>
                <p><strong>XP:</strong> <?php echo $user['xp']; ?></p>
                <p><strong>Level:</strong> <?php echo $user['level']; ?></p>
                <p><strong>Member Since:</strong> <?php echo date('Y-m-d H:i', strtotime($user['created_at'])); ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Total Files:</strong> <?php echo $user['total_files']; ?></p>
                <p><strong>Total Folders:</strong> <?php echo $user['total_folders']; ?></p>
                <p><strong>Balance:</strong> Rp <?php echo number_format($user['balance'], 2, ',', '.'); ?></p>
                <!-- Add more user statistics here -->
            </div>
        </div>

        <div class="mt-4">
            <a href="<?php echo site_url('users/edit_profile'); ?>" class="btn btn-primary">Edit Profile</a>
            <a href="<?php echo site_url('miniapp/dashboard'); ?>" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</div>
