<!-- Sidebar -->
<nav id="sidebar" aria-label="Main Navigation">
    <!-- Side Header -->
    <div class="bg-header-dark">
        <div class="content-header bg-white-5">
            <!-- Logo -->
            <a class="fw-semibold text-white tracking-wide" href="<?php echo site_url(); ?>">
                <span class="smini-visible">
                    T<span class="opacity-75">x</span>
                </span>
                <span class="smini-hidden">
                    tele<span class="opacity-75">Xweb</span>
                </span>
            </a>
            <!-- END Logo -->

            <!-- Options -->
            <div class="d-flex align-items-center gap-1">
                <!-- Close Sidebar, Visible only on mobile screens -->
                <button type="button" class="btn btn-sm btn-alt-secondary d-lg-none" data-toggle="layout" data-action="sidebar_close">
                    <i class="fa fa-times-circle"></i>
                </button>
                <!-- END Close Sidebar -->
            </div>
            <!-- END Options -->
        </div>
    </div>
    <!-- END Side Header -->

    <!-- Sidebar Scrolling -->
    <div class="js-sidebar-scroll">
        <!-- Side Navigation -->
        <div class="content-side">
            <ul class="nav-main">
                <li class="nav-main-item">
                    <a class="nav-main-link <?php echo ($this->uri->segment(1) == 'dashboard' || $this->uri->segment(1) == '') ? 'active' : ''; ?>" href="<?php echo site_url('dashboard'); ?>">
                        <i class="nav-main-link-icon fa fa-location-arrow"></i>
                        <span class="nav-main-link-name"><?php echo lang('dashboard'); ?></span>
                    </a>
                </li>
                
                <li class="nav-main-heading">Features</li>

                <?php if (has_permission('view_files')): ?>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="<?php echo site_url('files'); ?>">
                        <i class="nav-main-link-icon fa fa-file-alt"></i>
                        <span class="nav-main-link-name"><?php echo lang('my_files'); ?></span>
                    </a>
                </li>
                <?php endif; ?>
                
                <?php if (has_permission('view_folders')): ?>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="<?php echo site_url('folders'); ?>">
                        <i class="nav-main-link-icon fa fa-folder"></i>
                        <span class="nav-main-link-name"><?php echo lang('my_folders'); ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (has_permission('manage_smart_collections')): ?>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="<?php echo site_url('smartcollections'); ?>">
                        <i class="nav-main-link-icon fa fa-tags"></i>
                        <span class="nav-main-link-name"><?php echo lang('smart_collections'); ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (has_permission('manage_public_collections')): ?>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="<?php echo site_url('publiccollections'); ?>">
                        <i class="nav-main-link-icon fa fa-globe"></i>
                        <span class="nav-main-link-name"><?php echo lang('public_collections'); ?></span>
                    </a>
                </li>
                <?php endif; ?>
                
                <?php if (has_permission('view_admin_dashboard')): ?>
                <li class="nav-main-heading"><?php echo lang('admin'); ?></li>
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <i class="nav-main-link-icon fa fa-user-shield"></i>
                        <span class="nav-main-link-name"><?php echo lang('admin_tools'); ?></span>
                    </a>
                    <ul class="nav-main-submenu">
                        <?php if (has_permission('view_admin_dashboard')): ?>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="<?php echo site_url('admin/dashboard'); ?>">
                                <span class="nav-main-link-name"><?php echo lang('admin_dashboard'); ?></span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (has_permission('manage_bots')): ?>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="<?php echo site_url('admin/bots'); ?>">
                                <span class="nav-main-link-name"><?php echo lang('manage_bots'); ?></span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (has_permission('manage_users')): ?>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="<?php echo site_url('admin/users'); ?>">
                                <span class="nav-main-link-name"><?php echo lang('manage_users'); ?></span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (has_permission('manage_roles')): ?>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="<?php echo site_url('admin/roles'); ?>">
                                <span class="nav-main-link-name"><?php echo lang('manage_roles'); ?></span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (has_permission('manage_tags')): ?>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="<?php echo site_url('admin/tagmanagement'); ?>">
                                <span class="nav-main-link-name"><?php echo lang('manage_tags'); ?></span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

            </ul>
        </div>
        <!-- END Side Navigation -->
    </div>
    <!-- END Sidebar Scrolling -->
</nav>
<!-- END Sidebar -->
