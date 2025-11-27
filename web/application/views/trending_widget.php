<div class="card mt-4">
    <div class="card-header">
        <h4>Trending This Week</h4>
    </div>
    <ul class="list-group list-group-flush">
        <?php if (empty($trending_files) && empty($trending_folders)): ?>
            <li class="list-group-item">No trending items yet.</li>
        <?php endif; ?>
        
        <?php if (!empty($trending_folders)): ?>
            <?php foreach ($trending_folders as $item): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="<?php echo site_url('folders/view/' . $item['entity_id']); ?>">
                        &#128193; <?php echo htmlspecialchars($item['folder_name']); ?>
                    </a>
                    <span class="badge bg-danger rounded-pill"><?php echo $item['access_count']; ?> views</span>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($trending_files)): ?>
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
