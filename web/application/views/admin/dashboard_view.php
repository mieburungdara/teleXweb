<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                <?php echo lang('admin_dashboard'); ?>
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Welcome, <?php echo $this->session->userdata('username') ?? 'Admin'; ?>.
            </p>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <?php if ($this->session->flashdata('error_message')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="row items-push">
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1">
                    <div class="item rounded-3 bg-body mx-auto my-3">
                        <i class="fa fa-users fa-lg text-primary"></i>
                    </div>
                    <div class="fs-1 fw-bold"><?php echo $total_users; ?></div>
                    <div class="text-muted mb-3"><?php echo lang('total_users'); ?></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1">
                    <div class="item rounded-3 bg-body mx-auto my-3">
                        <i class="fa fa-robot fa-lg text-primary"></i>
                    </div>
                    <div class="fs-1 fw-bold"><?php echo $total_bots; ?></div>
                    <div class="text-muted mb-3"><?php echo lang('total_bots'); ?></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1">
                    <div class="item rounded-3 bg-body mx-auto my-3">
                        <i class="fa fa-file-alt fa-lg text-primary"></i>
                    </div>
                    <div class="fs-1 fw-bold"><?php echo $total_files; ?></div>
                    <div class="text-muted mb-3"><?php echo lang('total_files'); ?></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full">
                    <div class="item rounded-3 bg-body mx-auto my-3">
                        <i class="fa fa-folder fa-lg text-primary"></i>
                    </div>
                    <div class="fs-1 fw-bold"><?php echo $total_folders; ?></div>
                    <div class="text-muted mb-3"><?php echo lang('total_folders'); ?></div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Stats -->

    <!-- Trending Items -->
    <div class="row">
        <div class="col-md-6">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title"><?php echo lang('trending_files'); ?></h3>
                </div>
                <div class="block-content">
                    <table class="table table-striped table-vcenter">
                        <tbody>
                            <?php if (empty($trending_files)): ?>
                                <tr><td><?php echo lang('no_trending_files'); ?></td></tr>
                            <?php else: ?>
                                <?php foreach ($trending_files as $item): ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo site_url('files/details/' . $item['entity_id']); ?>">
                                                <?php echo get_file_icon($item['mime_type']); ?> <?php echo htmlspecialchars($item['original_file_name']); ?>
                                            </a>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-danger"><?php echo $item['access_count']; ?> <?php echo lang('views'); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title"><?php echo lang('trending_folders'); ?></h3>
                </div>
                <div class="block-content">
                     <table class="table table-striped table-vcenter">
                        <tbody>
                            <?php if (empty($trending_folders)): ?>
                                <tr><td><?php echo lang('no_trending_folders'); ?></td></tr>
                            <?php else: ?>
                                <?php foreach ($trending_folders as $item): ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo site_url('folders/view/' . $item['entity_id']); ?>">
                                                <i class="fa fa-folder"></i> <?php echo htmlspecialchars($item['folder_name']); ?>
                                            </a>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-danger"><?php echo $item['access_count']; ?> <?php echo lang('views'); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- END Trending Items -->

    <!-- Placeholder for Charting -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title"><?php echo lang('usage_statistics_placeholder'); ?></h3>
        </div>
        <div class="block-content">
            <p><?php echo lang('charting_intro'); ?></p>
            <div style="height: 300px; background-color: #e9ecef; display: flex; align-items: center; justify-content: center; border-radius: .25rem;">
                <p class="text-muted"><?php echo lang('chart_will_be_rendered'); ?></p>
            </div>
        </div>
    </div>
    <!-- END Placeholder for Charting -->
</div>
<!-- END Page Content -->
