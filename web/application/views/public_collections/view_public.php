<div class="card">
    <div class="card-header">
        <h1>Public Collection: <?php echo htmlspecialchars($collection['name']); ?></h1>
    </div>
    <div class="card-body">
        <p class="lead"><?php echo htmlspecialchars($collection['description'] ?? 'No description provided.'); ?></p>
        <p>Created by: <?php echo htmlspecialchars($collection['username'] ?? 'Anonymous'); ?></p>
        <p>Access Code: <strong><?php echo htmlspecialchars($collection['access_code']); ?></strong></p>

        <?php if (empty($folders)): ?>
            <p>This collection currently contains no folders.</p>
        <?php else: ?>
            <h4 class="mt-4">Folders in this Collection:</h4>
            <div class="list-group">
                <?php foreach ($folders as $folder): ?>
                    <a href="<?php echo site_url('folders/view_shared/' . $folder['code']); ?>" class="list-group-item list-group-item-action">
                        <h5><?php echo htmlspecialchars($folder['folder_name']); ?></h5>
                        <p class="mb-1"><?php echo htmlspecialchars($folder['description'] ?? 'No description.'); ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
