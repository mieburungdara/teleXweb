<div class="card">
    <div class="card-header">
        <h1>Image Gallery</h1>
    </div>
    <div class="card-body">
        <?php if (empty($files)): ?>
            <p>You have no images yet.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($files as $file): ?>
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <?php if ($file['thumbnail_url']): ?>
                                <a href="<?php echo site_url('files/details/' . $file['id']); ?>">
                                    <img src="<?php echo $file['thumbnail_url']; ?>" class="card-img-top" alt="Thumbnail" style="height: 150px; object-fit: cover;">
                                </a>
                            <?php endif; ?>
                            <div class="card-body">
                                <p class="card-text" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php echo htmlspecialchars($file['original_file_name'] ?? 'N/A'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
