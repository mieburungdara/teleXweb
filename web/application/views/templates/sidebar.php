<!-- Sidebar-->
<div class="border-end" id="sidebar-wrapper">
    <nav class="sidebar-nav">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo site_url(); ?>">
            <div class="sidebar-brand-icon">
                <i class="fas fa-paper-plane"></i>
            </div>
            <div class="sidebar-brand-text mx-3">teleXweb</div>
        </a>

        <hr class="sidebar-divider my-0">

        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo site_url('miniapp/dashboard'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span><?php echo lang('dashboard'); ?></span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Core
            </div>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo site_url('files'); ?>">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span><?php echo lang('my_files'); ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo site_url('folders'); ?>">
                    <i class="fas fa-fw fa-folder"></i>
                    <span><?php echo lang('my_folders'); ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo site_url('smartcollections'); ?>">
                    <i class="fas fa-fw fa-tags"></i>
                    <span><?php echo lang('smart_collections'); ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo site_url('publiccollections'); ?>">
                    <i class="fas fa-fw fa-globe"></i>
                    <span><?php echo lang('public_collections'); ?></span>
                </a>
            </li>

            <?php if (has_permission('manage_bots') || has_permission('manage_users') || has_permission('manage_roles') || has_permission('manage_tags') || has_permission('view_admin_dashboard')): ?>
                <hr class="sidebar-divider">
                <div class="sidebar-heading">
                    <?php echo lang('admin'); ?>
                </div>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAdmin" aria-expanded="false" aria-controls="collapseAdmin">
                        <i class="fas fa-fw fa-user-shield"></i>
                        <span><?php echo lang('admin_tools'); ?></span>
                    </a>
                    <div id="collapseAdmin" class="collapse" aria-labelledby="headingAdmin">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <?php if (has_permission('view_admin_dashboard')): ?>
                                <a class="collapse-item" href="<?php echo site_url('admin/dashboard'); ?>"><?php echo lang('admin_dashboard'); ?></a>
                            <?php endif; ?>
                            <?php if (has_permission('manage_bots')): ?>
                                <a class="collapse-item" href="<?php echo site_url('admin'); ?>"><?php echo lang('manage_bots'); ?></a>
                            <?php endif; ?>
                            <?php if (has_permission('manage_users')): ?>
                                <a class="collapse-item" href="<?php echo site_url('admin/users'); ?>"><?php echo lang('manage_users'); ?></a>
                            <?php endif; ?>
                            <?php if (has_permission('manage_roles')): ?>
                                <a class="collapse-item" href="<?php echo site_url('admin/roles'); ?>"><?php echo lang('manage_roles'); ?></a>
                            <?php endif; ?>
                             <?php if (has_permission('manage_tags')): ?>
                                <a class="collapse-item" href="<?php echo site_url('admin/tagmanagement'); ?>"><?php echo lang('manage_tags'); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
            <?php endif; ?>
        </ul>

        <div class="sidebar-bottom-controls mt-auto">
            <hr class="sidebar-divider">
            <div class="dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownLang" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-language me-2"></i><span><?php echo $this->config->item('available_languages')[$this->session->userdata('site_language') ?? $this->config->item('language')]; ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdownLang">
                    <?php foreach ($this->config->item('available_languages') as $lang_key => $lang_name): ?>
                        <li><a class="dropdown-item" href="<?php echo site_url('miniapp/set_language/' . $lang_key); ?>"><?php echo $lang_name; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTheme" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-adjust me-2"></i><span>Theme</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdownTheme">
                    <li><button class="dropdown-item" id="theme-light">Light</button></li>
                    <li><button class="dropdown-item" id="theme-dark">Dark</button></li>
                </ul>
            </div>
        </div>
    </nav>
</div>
