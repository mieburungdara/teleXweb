<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                Smart Collection: <?php echo htmlspecialchars($rule_name); ?>
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Files matching your custom rules.
            </p>
        </div>
        <div class="mt-4 mt-md-0">
             <a href="<?php echo site_url('smartcollections'); ?>" class="btn btn-alt-secondary">
                <i class="fa fa-arrow-left me-1"></i> Back to Collections
            </a>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Matching Files</h3>
        </div>
        <div class="block-content">
            <?php if (empty($files)): ?>
                <div class="alert alert-info">No files match this smart collection's rules yet.</div>
            <?php else: ?>
                <div class="row items-push">
                    <?php foreach ($files as $file): ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="block block-rounded text-center">
                                <div class="block-content block-content-full d-flex justify-content-center align-items-center" style="height: 150px;">
                                    <?php if (!empty($file['thumbnail_url'])): ?>
                                        <img src="<?php echo $file['thumbnail_url']; ?>" alt="thumbnail" class="img-fluid">
                                    <?php else: ?>
                                        <div class="fs-1 text-muted"><?php echo get_file_icon($file['mime_type']); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="block-content block-content-full bg-body-light">
                                    <div class="fw-semibold mb-1 text-truncate" title="<?php echo htmlspecialchars($file['original_file_name'] ?? 'N/A'); ?>"><?php echo htmlspecialchars($file['original_file_name'] ?? 'N/A'); ?></div>
                                    <div class="fs-sm text-muted"><?php echo isset($file['file_size']) ? number_format($file['file_size'] / 1024, 2) . ' KB' : 'N/A'; ?></div>
                                </div>
                                <div class="block-content block-content-full d-flex justify-content-end align-items-center">
                                    <div>
                                        <a href="<?php echo site_url('files/toggle_favorite/' . $file['id']); ?>" class="btn btn-sm <?php echo $file['is_favorited'] ? 'btn-alt-warning' : 'btn-alt-secondary'; ?>" data-bs-toggle="tooltip" title="Toggle Favorite">
                                            <i class="fa fa-star"></i>
                                        </a>
                                        <a href="<?php echo site_url('files/details/' . $file['id']); ?>" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Details">
                                            <i class="fa fa-info-circle"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- END Page Content -->
