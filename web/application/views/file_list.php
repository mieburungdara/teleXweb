<div class="row">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h1>My Files</h1>
            </div>
            <div class="card-body">
                <?php if (!empty($breadcrumbs)): ?>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo site_url('files'); ?>">Home</a></li>
                            <?php foreach ($breadcrumbs as $crumb): ?>
                                <li class="breadcrumb-item"><a href="<?php echo site_url('files/index?folder_id=' . $crumb['id']); ?>"><?php echo htmlspecialchars($crumb['folder_name']); ?></a></li>
                            <?php endforeach; ?>
                        </ol>
                    </nav>
                <?php endif; ?>

                <?php $this->load->view('files/search_form', ['filters' => $filters, 'all_mime_types' => $all_mime_types, 'user_folders' => $user_folders]); ?>

                <div class="mb-3 d-flex justify-content-between">
                    <a href="<?php echo site_url('files?is_favorited=1'); ?>" class="btn btn-warning">Show Favorites Only &#9733;</a>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="bulkActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Bulk Actions
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="bulkActionsDropdown">
                            <li><a class="dropdown-item" href="#" id="bulk-delete-btn">Delete Selected</a></li>
                        </ul>
                    </div>
                </div>

                <?php if (empty($files)): ?>
                    <p>You have not saved any file metadata yet. Send files to your Telegram bot to get started.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all-checkbox"></th>
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
                                    <tr data-file-id="<?php echo $file['id']; ?>">
                                        <td><input type="checkbox" class="file-checkbox" value="<?php echo $file['id']; ?>"></td>
                                        <td>
                                        <?php if (!empty($file['thumbnail_url'])): ?>
                                            <img src="<?php echo $file['thumbnail_url']; ?>" alt="thumbnail" style="width: 30px; height: 30px; object-fit: cover; margin-right: 5px;">
                                        <?php endif; ?>
                                        <?php echo get_file_icon($file['mime_type']); ?>
                                    </td>
                                        <td><?php echo $file['id']; ?></td>
                                        <td class="editable" data-id="<?php echo $file['id']; ?>" data-field="original_file_name"><?php echo htmlspecialchars($file['original_file_name'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($file['folder_name'] ?? 'Unfiled'); ?></td>
                                        <td><?php echo htmlspecialchars($file['mime_type'] ?? 'N/A'); ?></td>
                                        <td><?php echo isset($file['file_size']) ? number_format($file['file_size'] / 1024, 2) . ' KB' : 'N/A'; ?></td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($file['created_at'])); ?></td>
                                        <td>
                                            <a href="<?php echo site_url('files/toggle_favorite/' . $file['id']); ?>" class="<?php echo $file['is_favorited'] ? 'text-warning' : 'text-muted'; ?>" title="Toggle Favorite">
                                                <?php echo $file['is_favorited'] ? '&#9733;' : '&#9734;'; ?>
                                            </a>
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
    <div class="col-md-3">
        <?php $this->load->view('trending_widget', ['trending_files' => $trending_files, 'trending_folders' => $trending_folders]); ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Inline editing script
    const editables = document.querySelectorAll('.editable');
    editables.forEach(cell => {
        cell.addEventListener('click', function(e) {
            if (this.isContentEditable) return;
            this.setAttribute('contenteditable', true);
            this.focus();
            const originalContent = this.textContent;

            const handleBlur = () => {
                this.removeAttribute('contenteditable');
                const newContent = this.textContent;

                if (newContent !== originalContent) {
                    const formData = new FormData();
                    formData.append('file_id', this.dataset.id);
                    formData.append('field', this.dataset.field);
                    formData.append('value', newContent);
                    formData.append(window.csrfData.tokenName, window.csrfData.tokenHash);

                    fetch('<?php echo site_url('api/update_file'); ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status !== 'success') {
                            this.textContent = originalContent;
                            alert('Error: ' + data.message);
                        }
                        // Refresh CSRF token
                        window.csrfData.tokenHash = data.csrf_hash;
                    })
                    .catch(error => {
                        this.textContent = originalContent;
                        alert('An error occurred while saving.');
                    });
                }
                this.removeEventListener('blur', handleBlur);
                this.removeEventListener('keydown', handleKeydown);
            };

            const handleKeydown = (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.blur();
                } else if (e.key === 'Escape') {
                    this.textContent = originalContent;
                    this.blur();
                }
            };
            
            this.addEventListener('blur', handleBlur);
            this.addEventListener('keydown', handleKeydown);
        });
    });

    // Bulk actions script
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const fileCheckboxes = document.querySelectorAll('.file-checkbox');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            fileCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    }

    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const selectedIds = Array.from(fileCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            if (selectedIds.length === 0) {
                alert('Please select at least one file.');
                return;
            }

            if (confirm(`Are you sure you want to delete ${selectedIds.length} file(s)?`)) {
                const formData = new FormData();
                formData.append('action', 'delete');
                selectedIds.forEach(id => formData.append('file_ids[]', id));
                formData.append(window.csrfData.tokenName, window.csrfData.tokenHash);

                fetch('<?php echo site_url('api/bulk_action'); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === 'success') {
                        location.reload();
                    }
                    window.csrfData.tokenHash = data.csrf_hash;
                })
                .catch(error => {
                    alert('An error occurred during the bulk action.');
                });
            }
        });
    }
});
</script>