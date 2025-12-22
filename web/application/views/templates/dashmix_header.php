<!doctype html>
<html lang="en" class="remember-theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title><?php echo isset($title) ? $title . ' | teleXweb' : 'teleXweb'; ?></title>

    <meta name="description" content="teleXweb - File Management System">
    <meta name="author" content="Your Name">
    <meta name="robots" content="noindex, nofollow">

    <!-- Icons -->
    <link rel="shortcut icon" href="<?php echo base_url('assets/dashmix/media/favicons/favicon.png'); ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo base_url('assets/dashmix/media/favicons/favicon-192x192.png'); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url('assets/dashmix/media/favicons/apple-touch-icon-180x180.png'); ?>">
    <!-- END Icons -->

    <!-- Stylesheets -->
    <!-- Dashmix framework -->
    <link rel="stylesheet" id="css-main" href="<?php echo base_url('assets/dashmix/css/dashmix.min.css'); ?>">

    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="<?php echo base_url('assets/dashmix/css/themes/xwork.min.css'); ?>"> -->
    <!-- END Stylesheets -->
    
    <script>
        window.csrfData = {
            tokenName: '<?php echo $this->security->get_csrf_token_name(); ?>',
            tokenHash: '<?php echo $this->security->get_csrf_hash(); ?>'
        };
        window.site_url = '<?php echo site_url(); ?>';
        window.base_url = '<?php echo base_url(); ?>';
    </script>
</head>

<body>
    <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">
        <!-- Side Overlay-->
        <aside id="side-overlay">
            <!-- Side Header -->
            <div class="bg-image" style="background-image: url('<?php echo base_url('assets/dashmix/media/various/bg_side_overlay_header.jpg'); ?>');">
                <div class="bg-primary-op">
                    <div class="content-header">
                        <!-- User Avatar -->
                        <a class="img-link me-1" href="<?php echo site_url('users/profile'); ?>">
                            <img class="img-avatar img-avatar48" src="<?php echo base_url('assets/dashmix/media/avatars/avatar10.jpg'); ?>" alt="">
                        </a>
                        <!-- END User Avatar -->

                        <!-- User Info -->
                        <div class="ms-2">
                            <a class="text-white fw-semibold" href="<?php echo site_url('users/profile'); ?>"><?php echo $this->session->userdata('username') ?? 'Guest'; ?></a>
                            <div class="text-white-75 fs-sm"><?php echo $this->session->userdata('role_name') ?? 'User'; ?></div>
                        </div>
                        <!-- END User Info -->

                        <!-- Close Side Overlay -->
                        <a class="ms-auto text-white" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_close">
                            <i class="fa fa-times-circle"></i>
                        </a>
                        <!-- END Close Side Overlay -->
                    </div>
                </div>
            </div>
            <!-- END Side Header -->

            <!-- Side Content -->
            <div class="content-side">
                <p>
                    Content..
                </p>
            </div>
            <!-- END Side Content -->
        </aside>
        <!-- END Side Overlay -->
        
        <?php $this->load->view('templates/dashmix_sidebar'); ?>

        <!-- Header -->
        <header id="page-header">
            <!-- Header Content -->
            <div class="content-header">
                <!-- Left Section -->
                <div class="space-x-1">
                    <!-- Toggle Sidebar -->
                    <button type="button" class="btn btn-alt-secondary" data-toggle="layout" data-action="sidebar_toggle">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                    <!-- END Toggle Sidebar -->
                </div>
                <!-- END Left Section -->

                <!-- Right Section -->
                <div class="space-x-1">
                    <!-- User Dropdown -->
                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn btn-alt-secondary" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-fw fa-user d-sm-none"></i>
                            <span class="d-none d-sm-inline-block"><?php echo $this->session->userdata('username') ?? 'Guest'; ?></span>
                            <i class="fa fa-fw fa-angle-down opacity-50 ms-1 d-none d-sm-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="page-header-user-dropdown">
                            <div class="bg-primary-dark rounded-top fw-semibold text-white text-center p-3">
                                User Options
                            </div>
                            <div class="p-2">
                                <a class="dropdown-item" href="<?php echo site_url('users/profile'); ?>">
                                    <i class="far fa-fw fa-user me-1"></i> Profile
                                </a>
                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="<?php echo site_url('notifications'); ?>">
                                    <span><i class="far fa-fw fa-envelope me-1"></i> Notifications</span>
                                    <!-- Optional Badge -->
                                    <!-- <span class="badge bg-primary rounded-pill">3</span> -->
                                </a>
                                <div role="separator" class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_toggle">
                                    <i class="far fa-fw fa-building me-1"></i> Settings
                                </a>
                                <div role="separator" class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo site_url('auth/logout'); ?>">
                                    <i class="far fa-fw fa-arrow-alt-circle-left me-1"></i> Sign Out
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- END User Dropdown -->

                    <!-- Notifications Dropdown -->
                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn btn-alt-secondary" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-fw fa-bell"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                            <div class="bg-primary-dark rounded-top fw-semibold text-white text-center p-3">
                                Notifications
                            </div>
                            <ul class="nav-items my-2">
                                <li>
                                    <a class="d-flex text-dark py-2" href="javascript:void(0)">
                                        <div class="flex-shrink-0 mx-3">
                                            <i class="fa fa-fw fa-check-circle text-success"></i>
                                        </div>
                                        <div class="flex-grow-1 fs-sm pe-2">
                                            <div class="fw-semibold">No new notifications</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                            <div class="p-2 border-top">
                                <a class="btn btn-alt-primary w-100 text-center" href="<?php echo site_url('notifications'); ?>">
                                    <i class="fa fa-fw fa-eye opacity-50 me-1"></i> View All
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- END Notifications Dropdown -->

                    <!-- Toggle Side Overlay -->
                    <button type="button" class="btn btn-alt-secondary" data-toggle="layout" data-action="side_overlay_toggle">
                        <i class="far fa-fw fa-list-alt"></i>
                    </button>
                    <!-- END Toggle Side Overlay -->
                </div>
                <!-- END Right Section -->
            </div>
            <!-- END Header Content -->

            <!-- Header Search -->
            <div id="page-header-search" class="overlay-header bg-header-dark">
                <div class="bg-white-10">
                    <div class="content-header">
                        <form class="w-100" onsubmit="return false;">
                            <div class="input-group">
                                <button type="button" class="btn btn-alt-primary" data-toggle="layout" data-action="header_search_off">
                                    <i class="fa fa-fw fa-times-circle"></i>
                                </button>
                                <input type="text" class="form-control border-0" placeholder="Search or hit ESC.." id="page-header-search-input" name="page-header-search-input">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- END Header Search -->

            <!-- Header Loader -->
            <div id="page-header-loader" class="overlay-header bg-header-dark">
                <div class="bg-white-10">
                    <div class="content-header">
                        <div class="w-100 text-center">
                            <i class="fa fa-fw fa-sun fa-spin text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Header Loader -->
        </header>
        <!-- END Header -->

        <!-- Main Container -->
        <main id="main-container">
