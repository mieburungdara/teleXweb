<!-- Sidebar-->
<div class="border-end" id="sidebar-wrapper">
    <div class="sidebar-heading border-bottom">teleXweb</div>
    <div class="list-group list-group-flush">
        <?php if ($this->session->userdata('logged_in')): ?>
            <a class="list-group-item list-group-item-action p-3" href="<?php echo site_url('miniapp/dashboard'); ?>">
                <i class="fas fa-tachometer-alt me-2"></i><?php echo lang('dashboard'); ?>
            </a>

            <?php if (has_permission('manage_bots') || has_permission('manage_users') || has_permission('manage_roles') || has_permission('manage_tags') || has_permission('view_admin_dashboard')): ?>
                <a class="list-group-item list-group-item-action p-3" href="#adminSubmenu" data-bs-toggle="collapse" aria-expanded="false">
                    <i class="fas fa-user-shield me-2"></i><?php echo lang('admin'); ?>
                </a>
                <div class="collapse" id="adminSubmenu">
                    <?php if (has_permission('view_admin_dashboard')): ?>
                        <a class="list-group-item list-group-item-action ps-5" href="<?php echo site_url('admin/dashboard'); ?>"><?php echo lang('admin_dashboard'); ?></a>
                    <?php endif; ?>
                    <?php if (has_permission('manage_bots')): ?>
                        <a class="list-group-item list-group-item-action ps-5" href="<?php echo site_url('admin'); ?>"><?php echo lang('manage_bots'); ?></a>
                    <?php endif; ?>
                    <?php if (has_permission('manage_users')): ?>
                        <a class="list-group-item list-group-item-action ps-5" href="<?php echo site_url('admin/users'); ?>"><?php echo lang('manage_users'); ?></a>
                    <?php endif; ?>
                    <?php if (has_permission('manage_roles')): ?>
                        <a class="list-group-item list-group-item-action ps-5" href="<?php echo site_url('admin/roles'); ?>"><?php echo lang('manage_roles'); ?></a>
                    <?php endif; ?>
                    <?php if (has_permission('manage_tags')): ?>
                        <a class="list-group-item list-group-item-action ps-5" href="<?php echo site_url('admin/tagmanagement'); ?>"><?php echo lang('manage_tags'); ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <a class="list-group-item list-group-item-action p-3" href="<?php echo site_url('files'); ?>">
                <i class="fas fa-file-alt me-2"></i><?php echo lang('my_files'); ?>
            </a>
            <a class="list-group-item list-group-item-action p-3" href="<?php echo site_url('folders'); ?>">
                <i class="fas fa-folder me-2"></i><?php echo lang('my_folders'); ?>
            </a>
            <a class="list-group-item list-group-item-action p-3" href="<?php echo site_url('smartcollections'); ?>">
                <i class="fas fa-tags me-2"></i><?php echo lang('smart_collections'); ?>
            </a>
            <a class="list-group-item list-group-item-action p-3" href="<?php echo site_url('publiccollections'); ?>">
                <i class="fas fa-globe me-2"></i><?php echo lang('public_collections'); ?>
            </a>
            <a class="list-group-item list-group-item-action p-3" href="<?php echo site_url('files/timeline'); ?>">
                <i class="fas fa-history me-2"></i><?php echo lang('timeline'); ?>
            </a>
            <a class="list-group-item list-group-item-action p-3" href="<?php echo site_url('files/gallery'); ?>">
                <i class="fas fa-images me-2"></i><?php echo lang('image_gallery'); ?>
            </a>
            <a class="list-group-item list-group-item-action p-3" href="<?php echo site_url('notifications'); ?>">
                <i class="fas fa-bell me-2"></i><?php echo lang('notifications'); ?>
            </a>
            <a class="list-group-item list-group-item-action p-3" href="<?php echo site_url('monetization/balance'); ?>">
                <i class="fas fa-money-bill-wave me-2"></i><?php echo lang('monetization'); ?>
            </a>
        <?php endif; ?>
    </div>
    <div class="list-group list-group-flush mt-auto">
        <hr class="dropdown-divider">
        <div class="dropdown p-3">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownLang" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-language me-2"></i><?php echo $this->config->item('available_languages')[$this->session->userdata('site_language') ?? $this->config->item('language')]; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdownLang">
                <?php foreach ($this->config->item('available_languages') as $lang_key => $lang_name): ?>
                    <li><a class="dropdown-item" href="<?php echo site_url('miniapp/set_language/' . $lang_key); ?>"><?php echo $lang_name; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="dropdown p-3">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTheme" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-adjust me-2"></i>Theme
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdownTheme">
                <li><button class="dropdown-item" id="theme-light">Light</button></li>
                <li><button class="dropdown-item" id="theme-dark">Dark</button></li>
            </ul>
        </div>
        <div class="dropdown p-3">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-circle me-2"></i><strong><?php echo $this->session->userdata('username'); ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                <li><a class="dropdown-item" href="<?php echo site_url('users/profile'); ?>"><?php echo lang('my_profile'); ?></a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?php echo site_url('miniapp/logout'); ?>"><?php echo lang('logout'); ?></a></li>
            </ul>
        </div>
    </div>
</div>
