<div class="card">
    <div class="card-header">
        <h1>My Files</h1>
    </div>
    <div class="card-body">
        <?php $this->load->view('files/search_form', ['filters' => $filters, 'all_mime_types' => $all_mime_types, 'user_folders' => $user_folders]); ?>

        <div class="mb-3">
            <a href="<?php echo site_url('files?is_favorited=1'); ?>" class="btn btn-warning">Show Favorites Only &#9733;</a>
        </div>

        <?php if (empty($files)): ?>
            <p>You have not saved any file metadata yet. Send files to your Telegram bot to get started.</p>
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
                                        <span class="text-warning">&#9733;</span>
                                    <?php else: ?>
                                        <span class="text-muted">&#9734;</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
