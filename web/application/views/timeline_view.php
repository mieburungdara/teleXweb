<div class="card">
    <div class="card-header">
        <h1>File Timeline</h1>
    </div>
    <div class="card-body">
        <?php if (empty($timeline_data)): ?>
            <p>No files to display in the timeline.</p>
        <?php else: ?>
            <div class="timeline">
                <?php foreach ($timeline_data as $date => $files): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h4 class="timeline-title"><?php echo date('F j, Y', strtotime($date)); ?></h4>
                            <div class="row">
                                <?php foreach ($files as $file): ?>
                                    <div class="col-md-2 mb-2">
                                        <a href="<?php echo site_url('files/details/' . $file['id']); ?>" title="<?php echo htmlspecialchars($file['original_file_name']); ?>">
                                            <?php echo get_file_icon($file['mime_type']); ?>
                                            <span class="d-block small text-truncate"><?php echo htmlspecialchars($file['original_file_name']); ?></span>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}
.timeline::before {
    content: '';
    position: absolute;
    top: 0;
    left: 10px;
    height: 100%;
    width: 4px;
    background: #f1f1f1;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
    padding-left: 40px;
}
.timeline-marker {
    position: absolute;
    top: 5px;
    left: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #0d6efd;
    border: 3px solid #fff;
}
.timeline-content {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
}
</style>
