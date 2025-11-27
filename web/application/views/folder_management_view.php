<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h2>My Folders</h2>
            </div>
            <div class="card-body">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('folders'); ?>">Home</a></li>
                        <?php if (!empty($breadcrumbs)): ?>
                            <?php foreach ($breadcrumbs as $crumb): ?>
                                <li class="breadcrumb-item"><a href="<?php echo site_url('folders/index/' . $crumb['id']); ?>"><?php echo htmlspecialchars($crumb['folder_name']); ?></a></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ol>
                </nav>

                <?php if ($this->session->flashdata('success_message')): ?>
                    <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error_message')): ?>
                    <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
                <?php endif; ?>

                <?php if (empty($folders)): ?>
                    <p>No sub-folders here.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach ($folders as $f): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="<?php echo site_url('folders/index/' . $f['id']); ?>"><strong><?php echo htmlspecialchars($f['folder_name']); ?></strong></a>
                                    <small class="text-muted d-block"><?php echo htmlspecialchars($f['description'] ?? ''); ?></small>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('folders/toggle_favorite/' . $f['id']); ?>" class="btn btn-sm <?php echo $f['is_favorited'] ? 'btn-warning' : 'btn-outline-warning'; ?>" title="Toggle Favorite">&#9733;</a>
                                    <a href="<?php echo site_url('folders/view/' . $f['id']); ?>" class="btn btn-sm btn-outline-info">View</a>
                                    <a href="<?php echo site_url('folders/share/' . $f['id']); ?>" class="btn btn-sm btn-outline-success">Share</a>
                                    <a href="<?php echo site_url('folders/edit/' . $f['id']); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <a href="<?php echo site_url('folders/delete/' . $f['id']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?');">Delete</a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3><?php echo isset($folder) ? 'Edit Folder' : 'Create New Folder'; ?></h3>
            </div>
            <div class="card-body">
                <?php echo form_open('folders/save'); ?>
                    <?php if (isset($folder)): ?>
                        <input type="hidden" name="id" value="<?php echo $folder['id']; ?>">
                    <?php endif; ?>
                    <input type="hidden" name="parent_folder_id" value="<?php echo $parent_folder_id ?? ($folder['parent_folder_id'] ?? ''); ?>">
                    
                    <div class="mb-3">
                        <label for="folder_name" class="form-label">Folder Name</label>
                        <input type="text" class="form-control" id="folder_name" name="folder_name" value="<?php echo set_value('folder_name', $folder['folder_name'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo set_value('description', $folder['description'] ?? ''); ?></textarea>
                    </div>
                                        <div class="mb-3">
                                            <label for="tags" class="form-label">Tags (comma-separated)</label>
                                            <input type="text" class="form-control" id="tags" name="tags" value="<?php echo set_value('tags', $folder['tags'] ?? ''); ?>" placeholder="e.g., work, personal, important" list="tag-suggestions">
                                            <datalist id="tag-suggestions"></datalist>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Folder</button>
                                        <?php if (isset($folder)): ?>
                                            <a href="<?php echo site_url('folders'); ?>" class="btn btn-secondary">Cancel</a>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
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
                    