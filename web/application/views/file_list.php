<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">My Files</h1>
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
                    </ol>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <!-- Search Form -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Advanced Search</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
            </div>
        </div>
        <div class="block-content">
            <?php echo form_open('files', ['method' => 'get']); ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="keyword" class="form-control" placeholder="Search by name or tag" value="<?php echo htmlspecialchars($filters['keyword'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="mime_type" class="form-select">
                            <option value="">All Types</option>
                            <?php foreach ($all_mime_types as $mime_type): ?>
                                <option value="<?php echo htmlspecialchars($mime_type['mime_type']); ?>" <?php echo (isset($filters['mime_type']) && $filters['mime_type'] == $mime_type['mime_type']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($mime_type['mime_type']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="folder_id" class="form-select">
                            <option value="">All Folders</option>
                            <?php foreach ($user_folders as $folder): ?>
                                <option value="<?php echo htmlspecialchars($folder['id']); ?>" <?php echo (isset($filters['folder_id']) && $filters['folder_id'] == $folder['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($folder['folder_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="is_favorited" class="form-select">
                            <option value="">All</option>
                            <option value="1" <?php echo (isset($filters['is_favorited']) && $filters['is_favorited'] == '1') ? 'selected' : ''; ?>>Favorited</option>
                            <option value="0" <?php echo (isset($filters['is_favorited']) && $filters['is_favorited'] == '0') ? 'selected' : ''; ?>>Not Favorited</option>
                        </select>
                    </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="<?php echo site_url('files'); ?>" class="btn btn-alt-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- END Search Form -->

    <div class="row">
        <div class="col-md-8">
            <div class="block block-rounded">
                 <div class="block-header block-header-default">
                    <h3 class="block-title">File List</h3>
                    <div class="block-options">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                            <label class="form-check-label" for="select-all-checkbox">Select All</label>
                        </div>
                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn btn-sm btn-alt-secondary" id="bulkActionsDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Bulk Actions <i class="fa fa-angle-down ms-1"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="bulkActionsDropdown">
                                <a class="dropdown-item" href="#" id="bulk-delete-btn">
                                    <i class="fa fa-fw fa-trash-alt me-1"></i> Delete Selected
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-content">
                     <?php if (empty($files)): ?>
                        <div class="alert alert-info">You have not saved any file metadata yet. Send files to your Telegram bot to get started.</div>
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
                                        <div class="block-content block-content-full d-flex justify-content-between align-items-center">
                                            <input type="checkbox" class="form-check-input file-checkbox" value="<?php echo $file['id']; ?>">
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
        <div class="col-md-4">
            <!-- Trending Widget -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Trending This Week</h3>
                </div>
                <div class="block-content">
                    <ul class="list-group list-group-flush">
                        <?php if (empty($trending_files) && empty($trending_folders)): ?>
                            <li class="list-group-item">No trending items yet.</li>
                        <?php endif; ?>
                        
                        <?php if (!empty($trending_folders)): ?>
                            <?php foreach ($trending_folders as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="<?php echo site_url('folders/view/' . $item['entity_id']); ?>">
                                        <i class="fa fa-folder me-1"></i> <?php echo htmlspecialchars($item['folder_name']); ?>
                                    </a>
                                    <span class="badge rounded-pill bg-danger"><?php echo $item['access_count']; ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if (!empty($trending_files)): ?>
                            <?php foreach ($trending_files as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="<?php echo site_url('files/details/' . $item['entity_id']); ?>">
                                        <?php echo get_file_icon($item['mime_type']); ?> <?php echo htmlspecialchars($item['original_file_name']); ?>
                                    </a>
                                     <span class="badge rounded-pill bg-danger"><?php echo $item['access_count']; ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <!-- END Trending Widget -->
        </div>
    </div>
</div>
<!-- END Page Content -->

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Bulk actions script for new grid layout
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
                    // Refresh CSRF token if available in response, for subsequent requests.
                    if(data.csrf_hash) {
                       window.csrfData.tokenHash = data.csrf_hash;
                    }
                })
                .catch(error => {
                    alert('An error occurred during the bulk action.');
                });
            }
        });
    }
});
</script>