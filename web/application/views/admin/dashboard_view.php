<div class="card">
    <div class="card-header">
        <h1><?php echo lang('admin_dashboard'); ?></h1>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo lang('total_users'); ?></h5>
                        <p class="card-text fs-3"><?php echo $total_users; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo lang('total_bots'); ?></h5>
                        <p class="card-text fs-3"><?php echo $total_bots; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo lang('total_files'); ?></h5>
                        <p class="card-text fs-3"><?php echo $total_files; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo lang('total_folders'); ?></h5>
                        <p class="card-text fs-3"><?php echo $total_folders; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <h4><?php echo lang('trending_items'); ?></h4>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><?php echo lang('trending_files'); ?></div>
                    <ul class="list-group list-group-flush">
                        <?php if (empty($trending_files)): ?>
                            <li class="list-group-item"><?php echo lang('no_trending_files'); ?></li>
                        <?php else: ?>
                            <?php foreach ($trending_files as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="<?php echo site_url('files/details/' . $item['entity_id']); ?>">
                                        <?php echo get_file_icon($item['mime_type']); ?> <?php echo htmlspecialchars($item['original_file_name']); ?>
                                    </a>
                                    <span class="badge bg-danger rounded-pill"><?php echo $item['access_count']; ?> <?php echo lang('views'); ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><?php echo lang('trending_folders'); ?></div>
                    <ul class="list-group list-group-flush">
                        <?php if (empty($trending_folders)): ?>
                            <li class="list-group-item"><?php echo lang('no_trending_folders'); ?></li>
                        <?php else: ?>
                            <?php foreach ($trending_folders as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="<?php echo site_url('folders/view/' . $item['entity_id']); ?>">
                                        &#128193; <?php echo htmlspecialchars($item['folder_name']); ?>
                                    </a>
                                    <span class="badge bg-danger rounded-pill"><?php echo $item['access_count']; ?> <?php echo lang('views'); ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Placeholder for Charting -->
        <h4 class="mt-4"><?php echo lang('usage_statistics_placeholder'); ?></h4>
        <div class="card">
            <div class="card-body">
                <p><?php echo lang('charting_intro'); ?></p>
                <ul>
                    <li><?php echo lang('file_uploads_over_time'); ?></li>
                    <li><?php echo lang('user_activity'); ?></li>
                    <li><?php echo lang('top_mime_types'); ?></li>
                </ul>
                <div style="height: 300px; background-color: #e9ecef; display: flex; align-items: center; justify-content: center;">
                    <p class="text-muted"><?php echo lang('chart_will_be_rendered'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
