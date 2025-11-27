<div class="card">
    <div class="card-header">
        Folder Statistics
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Total Files
            <span class="badge bg-primary rounded-pill"><?php echo $stats['file_count']; ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Total Size
            <span class="badge bg-info rounded-pill"><?php echo isset($folder['folder_size']) ? number_format($folder['folder_size'] / 1024, 2) . ' KB' : '0 KB'; ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Last Activity
            <span class="badge bg-secondary rounded-pill"><?php echo $stats['latest_activity'] ? date('Y-m-d', strtotime($stats['latest_activity'])) : 'N/A'; ?></span>
        </li>
    </ul>
</div>
