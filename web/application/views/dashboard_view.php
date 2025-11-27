<?php $this->load->view('templates/header', ['title' => 'Dashboard']); ?>

<div class="card">
    <div class="card-header">
        <h1>Dashboard</h1>
    </div>
    <div class="card-body">
        <p>Selamat datang di dashboard Anda!</p>

        <?php if ($this->session->userdata('logged_in')): ?>
            <div class="user-info">
                <p>Halo, <strong><?php echo $this->session->userdata('username') ?? 'Pengguna'; ?></strong>!</p>
                <p>ID Telegram: <strong><?php echo $this->session->userdata('telegram_id'); ?></strong></p>
                <p>Role: <strong><?php echo $this->session->userdata('role_name'); ?> (ID: <?php echo $this->session->userdata('role_id'); ?>)</strong></p>
            </div>
            <p class="mt-3">Ini adalah halaman yang dilindungi, hanya dapat diakses setelah autentikasi berhasil.</p>

            <?php if (is_admin()): ?>
                <hr>
                <h2>Admin Actions</h2>
                <div class="list-group">
                    <a href="<?php echo site_url('admin'); ?>" class="list-group-item list-group-item-action">Manage Bots</a>
                    <a href="<?php echo site_url('admin/users'); ?>" class="list-group-item list-group-item-action">Manage Users</a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-danger">Anda tidak login.</p>
        <?php endif; ?>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>