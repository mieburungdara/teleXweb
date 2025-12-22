<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                File Timeline
            </h1>
            <p class="fw-medium mb-0 text-muted">
                A chronological view of your file uploads.
            </p>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <?php if (empty($timeline_data)): ?>
        <div class="block block-rounded">
            <div class="block-content">
                <p>No files to display in the timeline.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="block block-rounded">
            <div class="block-content">
                <ul class="list-activity">
                    <?php foreach ($timeline_data as $date => $files): ?>
                        <li>
                            <i class="fa fa-calendar-alt text-gray-dark"></i>
                            <div class="fw-semibold"><?php echo date('F j, Y', strtotime($date)); ?></div>
                             <div class="mt-2">
                                <div class="d-flex flex-wrap">
                                <?php foreach ($files as $file): ?>
                                     <a class="me-4 mb-2" href="<?php echo site_url('files/details/' . $file['id']); ?>" title="<?php echo htmlspecialchars($file['original_file_name']); ?>">
                                        <div class="d-flex align-items-center">
                                            <?php echo get_file_icon($file['mime_type']); ?>
                                            <span class="ms-2 text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($file['original_file_name']); ?></span>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
</div>
<!-- END Page Content -->
