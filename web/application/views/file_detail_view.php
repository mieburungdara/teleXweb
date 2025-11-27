<div class="card">
    <div class="card-header">
        <h1>File Details</h1>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <?php if ($file['thumbnail_url']): ?>
                    <img src="<?php echo $file['thumbnail_url']; ?>" class="img-fluid rounded" alt="Thumbnail">
                <?php else: ?>
                    <div class="text-center p-5 bg-light">
                        <span style="font-size: 5rem;"><?php echo get_file_icon($file['mime_type']); ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-8">
                <h3><?php echo htmlspecialchars($file['original_file_name'] ?? 'N/A'); ?></h3>
                <table class="table">
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <td><?php echo $file['id']; ?></td>
                        </tr>
                        <tr>
                            <th>Folder</th>
                            <td><?php echo htmlspecialchars($file['folder_name'] ?? 'Unfiled'); ?></td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td><?php echo htmlspecialchars($file['mime_type'] ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <th>Size</th>
                            <td><?php echo isset($file['file_size']) ? number_format($file['file_size'] / 1024, 2) . ' KB' : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <th>Date Added</th>
                            <td><?php echo date('Y-m-d H:i:s', strtotime($file['created_at'])); ?></td>
                        </tr>
                        <tr>
                            <th>Favorite</th>
                            <td><?php echo $file['is_favorited'] ? 'Yes' : 'No'; ?></td>
                        </tr>
                    </tbody>
                </table>
                <a href="<?php echo site_url('files'); ?>" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</div>
