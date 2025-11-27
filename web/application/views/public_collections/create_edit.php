<div class="card">
    <div class="card-header">
        <h1><?php echo $title; ?></h1>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('errors')): ?>
            <div class="alert alert-danger">
                <h4>Validation Errors:</h4>
                <?php echo $this->session->flashdata('errors'); ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php endif; ?>

        <?php echo form_open('publiccollections/save'); ?>
            <?php if (isset($collection['id'])): ?>
                <input type="hidden" name="id" value="<?php echo $collection['id']; ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="name" class="form-label">Collection Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name', $collection['name'] ?? ''); ?>" required>
                <?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo set_value('description', $collection['description'] ?? ''); ?></textarea>
                <?php echo form_error('description', '<div class="text-danger">', '</div>'); ?>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_private" name="is_private" value="1" <?php echo set_checkbox('is_private', '1', (isset($collection['is_private']) && $collection['is_private'] == 1)); ?>>
                <label class="form-check-label" for="is_private">Private Collection (only accessible via direct link, not listed publicly)</label>
            </div>

            <h4 class="mt-4">Folders in Collection</h4>
            <div class="mb-3">
                <p>Select folders to include in this public collection.</p>
                <div class="list-group">
                    <?php if (empty($available_folders)): ?>
                        <p class="list-group-item">No folders available to add.</p>
                    <?php else: ?>
                        <?php foreach ($available_folders as $folder): ?>
                            <label class="list-group-item">
                                <input class="form-check-input me-1" type="checkbox" name="folders[]" value="<?php echo $folder['id']; ?>"
                                    <?php
                                    // Check if this folder is already in the collection
                                    $checked = FALSE;
                                    foreach ($folders_in_collection as $f_in_c) {
                                        if ($f_in_c['id'] == $folder['id']) {
                                            $checked = TRUE;
                                            break;
                                        }
                                    }
                                    echo set_checkbox('folders[]', $folder['id'], $checked);
                                    ?>
                                >
                                <?php echo htmlspecialchars($folder['folder_name']); ?>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Collection</button>
            <a href="<?php echo site_url('publiccollections'); ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
