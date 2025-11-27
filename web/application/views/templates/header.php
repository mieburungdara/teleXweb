<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : lang('teleXweb'); ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        window.csrfData = {
            tokenName: '<?php echo $this->security->get_csrf_token_name(); ?>',
            tokenHash: '<?php echo $this->security->get_csrf_hash(); ?>'
        };
    </script>
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529; /* Default text color */
        }
        .main-container {
            margin-top: 20px;
        }

        /* Dark Theme Styles */
        body.dark-theme {
            background-color: #212529; /* Dark background */
            color: #f8f9fa; /* Light text color */
        }
        body.dark-theme .navbar {
            background-color: #343a40 !important;
        }
        body.dark-theme .card {
            background-color: #343a40;
            color: #f8f9fa;
            border-color: #454d55;
        }
        body.dark-theme .card-header {
            background-color: #454d55;
            color: #f8f9fa;
            border-color: #343a40;
        }
        body.dark-theme .table {
            color: #f8f9fa;
        }
        body.dark-theme .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: rgba(255, 255, 255, 0.05);
        }
        body.dark-theme .list-group-item {
            background-color: #343a40;
            color: #f8f9fa;
            border-color: #454d55;
        }
        body.dark-theme .breadcrumb-item a {
            color: #adb5bd;
        }
        body.dark-theme .breadcrumb-item.active {
            color: #e9ecef;
        }
        body.dark-theme .btn-secondary {
            background-color: #5a6268;
            border-color: #545b62;
        }
        body.dark-theme .text-muted {
            color: #adb5bd !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo site_url(); ?>"><?php echo lang('teleXweb'); ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if ($this->session->userdata('logged_in')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('miniapp/dashboard'); ?>"><?php echo lang('dashboard'); ?></a>
                    </li>
                    <?php if (has_permission('manage_bots') || has_permission('manage_users') || has_permission('manage_roles') || has_permission('manage_tags') || has_permission('view_admin_dashboard')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo lang('admin'); ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                            <?php if (has_permission('view_admin_dashboard')): ?>
                                <li><a class="dropdown-item" href="<?php echo site_url('admin/dashboard'); ?>"><?php echo lang('admin_dashboard'); ?></a></li>
                            <?php endif; ?>
                            <?php if (has_permission('manage_bots')): ?>
                                <li><a class="dropdown-item" href="<?php echo site_url('admin'); ?>"><?php echo lang('manage_bots'); ?></a></li>
                            <?php endif; ?>
                            <?php if (has_permission('manage_users')): ?>
                                <li><a class="dropdown-item" href="<?php echo site_url('admin/users'); ?>"><?php echo lang('manage_users'); ?></a></li>
                            <?php endif; ?>
                            <?php if (has_permission('manage_roles')): ?>
                                <li><a class="dropdown-item" href="<?php echo site_url('admin/roles'); ?>"><?php echo lang('manage_roles'); ?></a></li>
                            <?php endif; ?>
                            <?php if (has_permission('manage_tags')): ?>
                                <li><a class="dropdown-item" href="<?php echo site_url('admin/tagmanagement'); ?>"><?php echo lang('manage_tags'); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('files'); ?>"><?php echo lang('my_files'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('folders'); ?>"><?php echo lang('my_folders'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('smartcollections'); ?>"><?php echo lang('smart_collections'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('publiccollections'); ?>"><?php echo lang('public_collections'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('files/timeline'); ?>"><?php echo lang('timeline'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('files/gallery'); ?>"><?php echo lang('image_gallery'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('notifications'); ?>"><?php echo lang('notifications'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('monetization/balance'); ?>"><?php echo lang('monetization'); ?></a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownLang" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $this->config->item('available_languages')[$this->session->userdata('site_language') ?? $this->config->item('language')]; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownLang">
                        <?php foreach ($this->config->item('available_languages') as $lang_key => $lang_name): ?>
                            <li><a class="dropdown-item" href="<?php echo site_url('miniapp/set_language/' . $lang_key); ?>"><?php echo $lang_name; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTheme" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Theme
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownTheme">
                        <li><button class="dropdown-item" id="theme-light">Light</button></li>
                        <li><button class="dropdown-item" id="theme-dark">Dark</button></li>
                    </ul>
                </li>
                <?php if ($this->session->userdata('logged_in')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo lang('welcome'); ?>, <?php echo $this->session->userdata('username'); ?>!
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                            <li><a class="dropdown-item" href="<?php echo site_url('users/profile'); ?>"><?php echo lang('my_profile'); ?></a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo site_url('miniapp/logout'); ?>"><?php echo lang('logout'); ?></a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?php echo lang('login'); ?></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container main-container">

<script>
document.addEventListener('DOMContentLoaded', () => {
    const themeLightBtn = document.getElementById('theme-light');
    const themeDarkBtn = document.getElementById('theme-dark');
    const body = document.body;

    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'dark') {
        body.classList.add('dark-theme');
    }

    themeLightBtn.addEventListener('click', () => {
        body.classList.remove('dark-theme');
        localStorage.setItem('theme', 'light');
    });

    themeDarkBtn.addEventListener('click', () => {
        body.classList.add('dark-theme');
        localStorage.setItem('theme', 'dark');
    });
});
</script>
