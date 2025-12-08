<!-- Sidebar-->
<div class="border-end bg-white" id="sidebar-wrapper">
    <div class="sidebar-heading border-bottom bg-light">teleXweb</div>
    <div class="list-group list-group-flush">
        <?php if ($this->session->userdata('logged_in')): ?>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo site_url('miniapp/dashboard'); ?>"><?php echo lang('dashboard'); ?></a>
            <?php if (has_permission('manage_bots') || has_permission('manage_users') || has_permission('manage_roles') || has_permission('manage_tags') || has_permission('view_admin_dashboard')): ?>
                <div class="list-group-item list-group-item-light p-3">
                    <a href="#adminSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><?php echo lang('admin'); ?></a>
                    <ul class="collapse list-unstyled" id="adminSubmenu">
                        <?php if (has_permission('view_admin_dashboard')): ?>
                            <li><a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo site_url('admin/dashboard'); ?>"><?php echo lang('admin_dashboard'); ?></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('manage_bots')): ?>
                            <li><a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo site_url('admin'); ?>"><?php echo lang('manage_bots'); ?></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('manage_users')): ?>
                            <li><a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo site_url('admin/users'); ?>"><?php echo lang('manage_users'); ?></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('manage_roles')): ?>
                            <li><a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo site_url('admin/roles'); ?>"><?php echo lang('manage_roles'); ?></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('manage_tags')): ?>
                            <li><a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo site_url('admin/tagmanagement'); ?>"><?php echo lang('manage_tags'); ?></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo site_url('files'); ?>"><?php echo lang('my_files'); ?></a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo site_url('folders'); ?>"><?php echo lang('my_folders'); ?></a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo site_url('smartcollections'); ?>"><?php echo lang('smart_collections'); ?></a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo site_url('publiccollections'); ?>"><?php echo lang('public_collections'); ?></a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo site_url('files/timeline'); ?>"><?php echo lang('timeline'); ?></a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo site_url('files/gallery'); ?>"><?php echo lang('image_gallery'); ?></a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo site_url('notifications'); ?>"><?php echo lang('notifications'); ?></a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo site_url('monetization/balance'); ?>"><?php echo lang('monetization'); ?></a>
        <?php endif; ?>
    </div>
    <div class="list-group list-group-flush mt-auto">
        <div class="dropdown p-3">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownLang" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo $this->config->item('available_languages')[$this->session->userdata('site_language') ?? $this->config->item('language')]; ?>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownLang">
                <?php foreach ($this->config->item('available_languages') as $lang_key => $lang_name): ?>
                    <li><a class="dropdown-item" href="<?php echo site_url('miniapp/set_language/' . $lang_key); ?>"><?php echo $lang_name; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="dropdown p-3">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTheme" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Theme
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownTheme">
                <li><button class="dropdown-item" id="theme-light">Light</button></li>
                <li><button class="dropdown-item" id="theme-dark">Dark</button></li>
            </ul>
        </div>
        <div class="dropdown p-3">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <strong><?php echo $this->session->userdata('username'); ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                <li><a class="dropdown-item" href="<?php echo site_url('users/profile'); ?>"><?php echo lang('my_profile'); ?></a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?php echo site_url('miniapp/logout'); ?>"><?php echo lang('logout'); ?></a></li>
            </ul>
        </div>
    </div>
</div>

