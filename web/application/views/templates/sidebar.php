<main class="d-flex flex-nowrap">
  <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 280px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
      <span class="fs-4">teleXweb</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <?php if ($this->session->userdata('logged_in')): ?>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo site_url('miniapp/dashboard'); ?>"><?php echo lang('dashboard'); ?></a>
            </li>
            <?php if (has_permission('manage_bots') || has_permission('manage_users') || has_permission('manage_roles') || has_permission('manage_tags') || has_permission('view_admin_dashboard')): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
            <li>
                <a class="nav-link text-white" href="<?php echo site_url('files'); ?>"><?php echo lang('my_files'); ?></a>
            </li>
            <li>
                <a class="nav-link text-white" href="<?php echo site_url('folders'); ?>"><?php echo lang('my_folders'); ?></a>
            </li>
            <li>
                <a class="nav-link text-white" href="<?php echo site_url('smartcollections'); ?>"><?php echo lang('smart_collections'); ?></a>
            </li>
            <li>
                <a class="nav-link text-white" href="<?php echo site_url('publiccollections'); ?>"><?php echo lang('public_collections'); ?></a>
            </li>
            <li>
                <a class="nav-link text-white" href="<?php echo site_url('files/timeline'); ?>"><?php echo lang('timeline'); ?></a>
            </li>
            <li>
                <a class="nav-link text-white" href="<?php echo site_url('files/gallery'); ?>"><?php echo lang('image_gallery'); ?></a>
            </li>
            <li>
                <a class="nav-link text-white" href="<?php echo site_url('notifications'); ?>"><?php echo lang('notifications'); ?></a>
            </li>
            <li>
                <a class="nav-link text-white" href="<?php echo site_url('monetization/balance'); ?>"><?php echo lang('monetization'); ?></a>
            </li>
        <?php endif; ?>
    </ul>
    <hr>
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <strong><?php echo $this->session->userdata('username'); ?></strong>
      </a>
      <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
        <li><a class="dropdown-item" href="<?php echo site_url('users/profile'); ?>"><?php echo lang('my_profile'); ?></a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="<?php echo site_url('miniapp/logout'); ?>"><?php echo lang('logout'); ?></a></li>
      </ul>
    </div>
  </div>
</main>
