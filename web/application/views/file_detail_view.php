<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">File Details</h1>
            <?php if (!empty($breadcrumbs)): ?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a href="<?php echo site_url('files'); ?>"><i class="fa fa-home"></i></a>
                        </li>
                        <?php foreach ($breadcrumbs as $crumb): ?>
                            <li class="breadcrumb-item">
                                <a href="<?php echo site_url('files/index?folder_id=' . $crumb['id']); ?>"><?php echo htmlspecialchars($crumb['folder_name']); ?></a>
                            </li>
                        <?php endforeach; ?>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($file['original_file_name'] ?? 'N/A'); ?></li>
                    </ol>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-md-5 col-lg-4">
            <!-- File Preview Block -->
            <div class="block block-rounded">
                <div class="block-content block-content-full d-flex justify-content-center align-items-center">
                    <?php if ($file['thumbnail_url']): ?>
                        <img src="<?php echo $file['thumbnail_url']; ?>" class="img-fluid rounded" alt="Thumbnail">
                    <?php else: ?>
                        <div class="text-center p-5">
                            <span style="font-size: 6rem;"><?php echo get_file_icon($file['mime_type']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- END File Preview Block -->
        </div>
        <div class="col-md-7 col-lg-8">
            <!-- File Details Block -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title"><?php echo htmlspecialchars($file['original_file_name'] ?? 'N/A'); ?></h3>
                </div>
                <div class="block-content">
                    <table class="table table-striped table-borderless">
                        <tbody>
                            <tr>
                                <td class="fw-semibold" style="width: 30%;">ID</td>
                                <td><?php echo $file['id']; ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Folder</td>
                                <td><?php echo htmlspecialchars($file['folder_name'] ?? 'Unfiled'); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Type</td>
                                <td><?php echo htmlspecialchars($file['mime_type'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Size</td>
                                <td><?php echo isset($file['file_size']) ? number_format($file['file_size'] / 1024, 2) . ' KB' : 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Date Added</td>
                                <td><?php echo date('F j, Y, g:i a', strtotime($file['created_at'])); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Favorite</td>
                                <td>
                                     <a href="<?php echo site_url('files/toggle_favorite/' . $file['id']); ?>" class="btn btn-sm <?php echo $file['is_favorited'] ? 'btn-warning' : 'btn-alt-secondary'; ?>">
                                        <i class="fa fa-star"></i> <?php echo $file['is_favorited'] ? 'Favorited' : 'Add to Favorites'; ?>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="block-content block-content-full bg-body-light text-end">
                    <a href="<?php echo site_url('files'); ?>" class="btn btn-alt-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
            <!-- END File Details Block -->
        </div>
    </div>
</div>
<!-- END Page Content -->
