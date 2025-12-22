<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">My Folders</h1>
            <?php if (!empty($breadcrumbs)): ?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a href="<?php echo site_url('folders'); ?>"><i class="fa fa-home"></i></a>
                        </li>
                        <?php foreach ($breadcrumbs as $crumb): ?>
                            <li class="breadcrumb-item">
                                <a href="<?php echo site_url('folders/index/' . $crumb['id']); ?>"><?php echo htmlspecialchars($crumb['folder_name']); ?></a>
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
    <div class="row">
        <div class="col-md-7 col-xl-8">
            <!-- Folder List Block -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Folders</h3>
                </div>
                <div class="block-content">
                    <?php if ($this->session->flashdata('success_message')): ?>
                        <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error_message')): ?>
                        <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
                    <?php endif; ?>

                    <?php if (empty($folders)): ?>
                        <p>No sub-folders here. You can add one using the form.</p>
                    <?php else: ?>
                        <table class="table table-vcenter">
                            <tbody>
                                <?php foreach ($folders as $f): ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo site_url('folders/index/' . $f['id']); ?>">
                                                <i class="fa fa-folder me-2"></i><strong><?php echo htmlspecialchars($f['folder_name']); ?></strong>
                                            </a>
                                            <small class="text-muted d-block mt-1"><?php echo htmlspecialchars($f['description'] ?? ''); ?></small>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <a href="<?php echo site_url('folders/view/' . $f['id']); ?>" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>
                                                <a href="<?php echo site_url('folders/share/' . $f['id']); ?>" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Share"><i class="fa fa-share-alt"></i></a>
                                                <a href="<?php echo site_url('folders/edit/' . $f['id']); ?>" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Edit"><i class="fa fa-pencil-alt"></i></a>
                                                <a href="<?php echo site_url('folders/delete/' . $f['id']); ?>" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure?');"><i class="fa fa-times"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
            <!-- END Folder List Block -->
        </div>
        <div class="col-md-5 col-xl-4">
            <!-- Add/Edit Folder Block -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title"><?php echo isset($folder) ? 'Edit Folder' : 'Create New Folder'; ?></h3>
                </div>
                <div class="block-content">
                    <?php echo form_open('folders/save'); ?>
                        <?php if (isset($folder)): ?>
                            <input type="hidden" name="id" value="<?php echo $folder['id']; ?>">
                        <?php endif; ?>
                        <input type="hidden" name="parent_folder_id" value="<?php echo $parent_folder_id ?? ($folder['parent_folder_id'] ?? ''); ?>">
                        
                        <div class="mb-4">
                            <label for="folder_name" class="form-label">Folder Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="folder_name" name="folder_name" value="<?php echo set_value('folder_name', $folder['folder_name'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo set_value('description', $folder['description'] ?? ''); ?></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="tags" class="form-label">Tags (comma-separated)</label>
                            <input type="text" class="form-control" id="tags" name="tags" value="<?php echo set_value('tags', $folder['tags'] ?? ''); ?>" placeholder="e.g., work, personal" list="tag-suggestions">
                            <datalist id="tag-suggestions"></datalist>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check-circle me-1"></i> Save Folder
                        </button>
                        <?php if (isset($folder)): ?>
                            <a href="<?php echo site_url('folders'); ?>" class="btn btn-alt-secondary">Cancel</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <!-- END Add/Edit Folder Block -->
        </div>
    </div>
</div>
<!-- END Page Content -->

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tagsInput = document.getElementById('tags');
    const tagSuggestions = document.getElementById('tag-suggestions');

    if (tagsInput) {
        tagsInput.addEventListener('keyup', function(e) {
            const query = this.value;
            const lastComma = query.lastIndexOf(',');
            const currentTag = lastComma === -1 ? query : query.substring(lastComma + 1).trim();

            if (currentTag.length < 2) {
                tagSuggestions.innerHTML = '';
                return;
            }

            fetch(`<?php echo site_url('api/tag_suggestions'); ?>?term=${currentTag}`)
                .then(response => response.json())
                .then(data => {
                    tagSuggestions.innerHTML = '';
                    data.forEach(tag => {
                        const option = document.createElement('option');
                        option.value = tag;
                        tagSuggestions.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching tag suggestions:', error);
                });
        });
    }
});
</script>