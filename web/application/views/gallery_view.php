<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                Image Gallery
            </h1>
            <p class="fw-medium mb-0 text-muted">
                A visual overview of your images.
            </p>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-content">
            <?php if (empty($files)): ?>
                <div class="alert alert-info">
                    You have no images yet.
                </div>
            <?php else: ?>
                <div class="row items-push">
                    <?php foreach ($files as $file): ?>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <a class="block block-rounded block-link-pop text-center" href="<?php echo site_url('files/details/' . $file['id']); ?>">
                                <div class="block-content block-content-full d-flex justify-content-center align-items-center" style="height: 200px;">
                                    <?php if ($file['thumbnail_url']): ?>
                                        <img src="<?php echo $file['thumbnail_url']; ?>" class="img-fluid" alt="Thumbnail">
                                    <?php else: ?>
                                        <div class="fs-1 text-muted"><i class="fa fa-image"></i></div>
                                    <?php endif; ?>
                                </div>
                                <div class="block-content block-content-full bg-body-light">
                                    <p class="fw-semibold mb-0 text-truncate" title="<?php echo htmlspecialchars($file['original_file_name'] ?? 'N/A'); ?>">
                                        <?php echo htmlspecialchars($file['original_file_name'] ?? 'N/A'); ?>
                                    </p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- END Page Content -->
