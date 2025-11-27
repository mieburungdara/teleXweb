<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1>Smart Collection: <?php echo htmlspecialchars($rule_name); ?></h1>
            </div>
            <div class="card-body">
                <?php if (empty($files)): ?>
                    <p>No files match this smart collection's rules yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>#</th>
                                    <th>Original Filename</th>
                                    <th>Folder</th>
                                    <th>Type</th>
                                    <th>Size</th>
                                    <th>Date Added</th>
                                    <th>Favorite</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($files as $file): ?>
                                    <tr>
                                        <td>
                                        <?php if (!empty($file['thumbnail_url'])): ?>
                                            <img src="<?php echo $file['thumbnail_url']; ?>" alt="thumbnail" style="width: 30px; height: 30px; object-fit: cover; margin-right: 5px;">
                                        <?php endif; ?>
                                        <?php echo get_file_icon($file['mime_type']); ?>
                                    </td>
                                        <td><?php echo $file['id']; ?></td>
                                        <td><?php echo htmlspecialchars($file['original_file_name'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($file['folder_name'] ?? 'Unfiled'); ?></td>
                                        <td><?php echo htmlspecialchars($file['mime_type'] ?? 'N/A'); ?></td>
                                        <td><?php echo isset($file['file_size']) ? number_format($file['file_size'] / 1024, 2) . ' KB' : 'N/A'; ?></td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($file['created_at'])); ?></td>
                                        <td>
                                            <?php if ($file['is_favorited']): ?>
                                                <a href="<?php echo site_url('files/toggle_favorite/' . $file['id']); ?>" class="text-warning" title="Toggle Favorite">
                                                    &#9733;
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php echo site_url('files/toggle_favorite/' . $file['id']); ?>" class="text-muted" title="Toggle Favorite">
                                                    &#9734;
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-secondary preview-btn" data-id="<?php echo $file['id']; ?>" data-bs-toggle="modal" data-bs-target="#previewModal">Preview</button>
                                            <a href="<?php echo site_url('files/details/' . $file['id']); ?>" class="btn btn-sm btn-outline-info">Details</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
