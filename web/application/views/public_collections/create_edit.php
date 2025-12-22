<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1"><?php echo $title; ?></h1>
            <p class="fw-medium mb-0 text-muted">Organize your folders into a shareable collection.</p>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <?php echo form_open('publiccollections/save'); ?>
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Collection Details</h3>
            </div>
            <div class="block-content">
                <?php if ($this->session->flashdata('errors')): ?>
                    <div class="alert alert-danger"><?php echo $this->session->flashdata('errors'); ?></div>
                <?php endif; ?>
                <?php if (isset($collection['id'])): ?>
                    <input type="hidden" name="id" value="<?php echo $collection['id']; ?>">
                <?php endif; ?>

                <div class="row push">
                    <div class="col-lg-4">
                        <p class="text-muted">
                            Enter the basic details for your collection.
                        </p>
                    </div>
                    <div class="col-lg-8 col-xl-5">
                        <div class="mb-4">
                            <label class="form-label" for="name">Collection Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name', $collection['name'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo set_value('description', $collection['description'] ?? ''); ?></textarea>
                        </div>
                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_private" name="is_private" value="1" <?php echo set_checkbox('is_private', '1', (isset($collection['is_private']) && $collection['is_private'] == 1)); ?>>
                                <label class="form-check-label" for="is_private">Private Collection</label>
                            </div>
                             <div class="form-text">Private collections are not listed publicly and are only accessible via direct link.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Select and Order Folders</h3>
            </div>
            <div class="block-content">
                <div class="row mb-4">
                    <!-- Available Folders -->
                    <div class="col-md-5">
                        <h6>Available Folders</h6>
                        <select id="available-folders" class="form-control" multiple size="10">
                            <?php
                            $folders_in_collection_ids = array_column($folders_in_collection, 'id');
                            foreach ($available_folders as $folder) {
                                if (!in_array($folder['id'], $folders_in_collection_ids)) {
                                    echo '<option value="' . $folder['id'] . '">' . htmlspecialchars($folder['folder_name']) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Buttons -->
                    <div class="col-md-2 text-center align-self-center">
                        <button type="button" id="add-folder" class="btn btn-alt-secondary mb-2 w-100"><i class="fa fa-arrow-right"></i></button>
                        <button type="button" id="remove-folder" class="btn btn-alt-secondary mt-2 w-100"><i class="fa fa-arrow-left"></i></button>
                    </div>
                    <!-- Selected Folders -->
                    <div class="col-md-5">
                        <h6>Selected Folders (in order)</h6>
                        <select id="selected-folders" class="form-control" multiple size="10">
                             <?php foreach ($folders_in_collection as $folder): ?>
                                <option value="<?php echo $folder['id']; ?>"><?php echo htmlspecialchars($folder['folder_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <!-- Hidden inputs will be populated by JS -->
                <div id="selected-folders-hidden"></div>
            </div>
        </div>

        <div class="block block-rounded">
            <div class="block-content bg-body-light">
                 <button type="submit" id="save-collection" class="btn btn-primary">
                    <i class="fa fa-check-circle me-1"></i> Save Collection
                </button>
                <a href="<?php echo site_url('publiccollections'); ?>" class="btn btn-alt-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Cancel
                </a>
            </div>
        </div>
    </form>
</div>
<!-- END Page Content -->

<script>
document.addEventListener('DOMContentLoaded', () => {
    const available = document.getElementById('available-folders');
    const selected = document.getElementById('selected-folders');
    const addBtn = document.getElementById('add-folder');
    const removeBtn = document.getElementById('remove-folder');
    const hiddenContainer = document.getElementById('selected-folders-hidden');
    const form = document.querySelector('form');

    addBtn.addEventListener('click', () => {
        Array.from(available.selectedOptions).forEach(option => {
            selected.appendChild(option);
        });
    });

    removeBtn.addEventListener('click', () => {
        Array.from(selected.selectedOptions).forEach(option => {
            available.appendChild(option);
        });
    });

    form.addEventListener('submit', () => {
        hiddenContainer.innerHTML = '';
        Array.from(selected.options).forEach((option, index) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `folders[${index}]`;
            input.value = option.value;
            hiddenContainer.appendChild(input);
        });
    });
});
</script>
