<?php $this->load->view('templates/header', ['title' => 'Dashboard']); ?>

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2 text-gray-800">Dashboard</h1>
            <p class="mb-4">Selamat datang di dashboard Anda, <strong><?php echo $this->session->userdata('username') ?? 'Pengguna'; ?></strong>!</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-circle mr-2"></i>Informasi Pengguna</h6>
                    <span class="badge badge-primary"><?php echo $this->session->userdata('role_name'); ?></span>
                </div>
                <div class="card-body">
                    <?php if ($this->session->userdata('logged_in')): ?>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                Nama Pengguna
                                <strong><?php echo $this->session->userdata('username') ?? 'N/A'; ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                ID Telegram
                                <strong><?php echo $this->session->userdata('telegram_id') ?? 'N/A'; ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                Kode Pengguna
                                <strong><?php echo $this->session->userdata('user_code') ?? 'N/A'; ?></strong>
                            </li>
                             <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                Peran
                                <strong><?php echo $this->session->userdata('role_name'); ?> (ID: <?php echo $this->session->userdata('role_id'); ?>)</strong>
                            </li>
                        </ul>
                    <?php else: ?>
                        <p class="text-danger">Anda tidak login.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (is_admin()): ?>
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-cogs mr-2"></i>Tindakan Admin</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="<?php echo site_url('admin'); ?>" class="list-group-item list-group-item-action"><i class="fas fa-robot mr-2"></i>Kelola Bot</a>
                        <a href="<?php echo site_url('admin/users'); ?>" class="list-group-item list-group-item-action"><i class="fas fa-users-cog mr-2"></i>Kelola Pengguna</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                     <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-line mr-2"></i>Aktivitas Terbaru</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">Untuk saat ini, tidak ada aktivitas terbaru untuk ditampilkan.</p>
                </div>
            </div>
        </div>
    </div>

</div>

<?php $this->load->view('templates/footer'); ?>