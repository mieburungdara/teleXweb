<!-- Sidebar -->
<nav id="sidebar">
    <div class="sidebar-header">
        <h3>teleXweb</h3>
    </div>

    <ul class="list-unstyled components">
        <?php if ($this->session->userdata('logged_in')): ?>
            <li>
                <a href="<?php echo site_url('miniapp/dashboard'); ?>"><?php echo lang('dashboard'); ?></a>
            </li>
            <?php if (has_permission('manage_bots') || has_permission('manage_users') || has_permission('manage_roles') || has_permission('manage_tags') || has_permission('view_admin_dashboard')): ?>
            <li>
                <a href="#adminSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><?php echo lang('admin'); ?></a>
                <ul class="collapse list-unstyled" id="adminSubmenu">
                    <?php if (has_permission('view_admin_dashboard')): ?>
                        <li><a href="<?php echo site_url('admin/dashboard'); ?>"><?php echo lang('admin_dashboard'); ?></a></li>
                    <?php endif; ?>
                    <?php if (has_permission('manage_bots')): ?>
                        <li><a href="<?php echo site_url('admin'); ?>"><?php echo lang('manage_bots'); ?></a></li>
                    <?php endif; ?>
                    <?php if (has_permission('manage_users')): ?>
                        <li><a href="<?php echo site_url('admin/users'); ?>"><?php echo lang('manage_users'); ?></a></li>
                    <?php endif; ?>
                    <?php if (has_permission('manage_roles')): ?>
                        <li><a href="<?php echo site_url('admin/roles'); ?>"><?php echo lang('manage_roles'); ?></a></li>
                    <?php endif; ?>
                    <?php if (has_permission('manage_tags')): ?>
                        <li><a href="<?php echo site_url('admin/tagmanagement'); ?>"><?php echo lang('manage_tags'); ?></a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>
            <li>
                <a href="<?php echo site_url('files'); ?>"><?php echo lang('my_files'); ?></a>
            </li>
            <li>
                <a href="<?php echo site_url('folders'); ?>"><?php echo lang('my_folders'); ?></a>
            </li>
            <li>
                <a href="<?php echo site_url('smartcollections'); ?>"><?php echo lang('smart_collections'); ?></a>
            </li>
            <li>
                <a href="<?php echo site_url('publiccollections'); ?>"><?php echo lang('public_collections'); ?></a>
            </li>
            <li>
                <a href="<?php echo site_url('files/timeline'); ?>"><?php echo lang('timeline'); ?></a>
            </li>
            <li>
                <a href="<?php echo site_url('files/gallery'); ?>"><?php echo lang('image_gallery'); ?></a>
            </li>
            <li>
                <a href="<?php echo site_url('notifications'); ?>"><?php echo lang('notifications'); ?></a>
            </li>
            <li>
                <a href="<?php echo site_url('monetization/balance'); ?>"><?php echo lang('monetization'); ?></a>
            </li>
        <?php endif; ?>
    </ul>

    <ul class="list-unstyled CTAs">
        <li>
            <a href="<?php echo site_url('users/profile'); ?>" class="download"><?php echo lang('my_profile'); ?></a>
        </li>
        <li>
            <a href="<?php echo site_url('miniapp/logout'); ?>" class="article"><?php echo lang('logout'); ?></a>
        </li>
    </ul>
</nav>
