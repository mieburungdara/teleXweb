<div class="card">
    <div class="card-header">
        <h1>Admin Dashboard</h1>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text fs-3"><?php echo $total_users; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Bots</h5>
                        <p class="card-text fs-3"><?php echo $total_bots; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Files</h5>
                        <p class="card-text fs-3"><?php echo $total_files; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Folders</h5>
                        <p class="card-text fs-3"><?php echo $total_folders; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <h4>Trending Items</h4>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Trending Files</div>
                    <ul class="list-group list-group-flush">
                        <?php if (empty($trending_files)): ?>
                            <li class="list-group-item">No trending files this week.</li>
                        <?php else: ?>
                            <?php foreach ($trending_files as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="<?php echo site_url('files/details/' . $item['entity_id']); ?>">
                                        <?php echo get_file_icon($item['mime_type']); ?> <?php echo htmlspecialchars($item['original_file_name']); ?>
                                    </a>
                                    <span class="badge bg-danger rounded-pill"><?php echo $item['access_count']; ?> views</span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Trending Folders</div>
                    <ul class="list-group list-group-flush">
                        <?php if (empty($trending_folders)): ?>
                            <li class="list-group-item">No trending folders this week.</li>
                        <?php else: ?>
                            <?php foreach ($trending_folders as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="<?php echo site_url('folders/view/' . $item['entity_id']); ?>">
                                        &#128193; <?php echo htmlspecialchars($item['folder_name']); ?>
                                    </a>
                                    <span class="badge bg-danger rounded-pill"><?php echo $item['access_count']; ?> views</span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Placeholder for Charting -->
        <h4 class="mt-4">Usage Statistics (Placeholder)</h4>
        <div class="card">
            <div class="card-body">
                <p>Integrate a charting library here (e.g., Chart.js, D3.js) to visualize data like:</p>
                <ul>
                    <li>File uploads over time</li>
                    <li>User activity</li>
                    <li>Top MIME types</li>
                </ul>
                <div style="height: 300px; background-color: #e9ecef; display: flex; align-items: center; justify-content: center;">
                    <p class="text-muted">Chart will be rendered here</p>
                </div>
            </div>
        </div>
    </div>
</div>
