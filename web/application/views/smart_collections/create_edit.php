<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1"><?php echo $title; ?></h1>
            <p class="fw-medium mb-0 text-muted">Create dynamic collections based on a set of rules.</p>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <?php echo form_open('smartcollections/save'); ?>
        <?php if (isset($rule['id'])): ?>
            <input type="hidden" name="id" value="<?php echo $rule['id']; ?>">
        <?php endif; ?>

        <!-- Rule Details Block -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Collection Details & Rules</h3>
            </div>
            <div class="block-content">
                <?php if ($this->session->flashdata('error_message')): ?>
                    <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
                <?php endif; ?>
                
                <div class="row push">
                    <div class="col-lg-4">
                        <p class="text-muted">
                            Provide a name and define the rules for this collection.
                        </p>
                    </div>
                    <div class="col-lg-8">
                        <div class="mb-4">
                            <label for="name" class="form-label">Collection Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name', $rule['name'] ?? ''); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row push">
                    <div class="col-lg-4">
                         <h5 class="mb-0">Rules</h5>
                         <p class="text-muted">Files matching ALL of these conditions will be included.</p>
                    </div>
                     <div class="col-lg-8">
                        <div id="rule-conditions">
                            <?php if (isset($rule['rule_json']['conditions']) && !empty($rule['rule_json']['conditions'])): ?>
                                <?php foreach ($rule['rule_json']['conditions'] as $index => $condition): ?>
                                    <div class="row mb-3 g-2 condition-row" data-index="<?php echo $index; ?>">
                                        <div class="col-sm-4">
                                            <select name="rule_conditions[<?php echo $index; ?>][field]" class="form-select condition-field">
                                                <option value="mime_type" <?php echo ($condition['field'] ?? '') == 'mime_type' ? 'selected' : ''; ?>>File Type</option>
                                                <option value="original_file_name" <?php echo ($condition['field'] ?? '') == 'original_file_name' ? 'selected' : ''; ?>>File Name</option>
                                                <option value="file_size" <?php echo ($condition['field'] ?? '') == 'file_size' ? 'selected' : ''; ?>>File Size (KB)</option>
                                                <option value="created_at" <?php echo ($condition['field'] ?? '') == 'created_at' ? 'selected' : ''; ?>>Date Added</option>
                                                <option value="is_favorited" <?php echo ($condition['field'] ?? '') == 'is_favorited' ? 'selected' : ''; ?>>Is Favorited</option>
                                                <option value="folder_id" <?php echo ($condition['field'] ?? '') == 'folder_id' ? 'selected' : ''; ?>>Folder</option>
                                                <option value="tag" <?php echo ($condition['field'] ?? '') == 'tag' ? 'selected' : ''; ?>>Tag</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <select name="rule_conditions[<?php echo $index; ?>][operator]" class="form-select condition-operator">
                                                <option value="LIKE" <?php echo ($condition['operator'] ?? '') == 'LIKE' ? 'selected' : ''; ?>>contains</option>
                                                <option value="=" <?php echo ($condition['operator'] ?? '') == '=' ? 'selected' : ''; ?>>is</option>
                                                <option value="!=" <?php echo ($condition['operator'] ?? '') == '!=' ? 'selected' : ''; ?>>is not</option>
                                                <option value=">" <?php echo ($condition['operator'] ?? '') == '>' ? 'selected' : ''; ?>>greater than</option>
                                                <option value="<" <?php echo ($condition['operator'] ?? '') == '<' ? 'selected' : ''; ?>>less than</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4 condition-value-container">
                                            <?php 
                                                $value = $condition['value'] ?? '';
                                                $field_type = $condition['field'] ?? '';
                                            ?>
                                            <?php if ($field_type == 'mime_type'): ?>
                                                <select name="rule_conditions[<?php echo $index; ?>][value]" class="form-select condition-value">
                                                    <?php foreach ($all_mime_types as $mt): ?>
                                                        <option value="<?php echo htmlspecialchars($mt['mime_type']); ?>" <?php echo ($value == $mt['mime_type']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($mt['mime_type']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php elseif ($field_type == 'is_favorited'): ?>
                                                <select name="rule_conditions[<?php echo $index; ?>][value]" class="form-select condition-value">
                                                    <option value="1" <?php echo ($value == '1') ? 'selected' : ''; ?>>Yes</option>
                                                    <option value="0" <?php echo ($value == '0') ? 'selected' : ''; ?>>No</option>
                                                </select>
                                            <?php elseif ($field_type == 'folder_id'): ?>
                                                <select name="rule_conditions[<?php echo $index; ?>][value]" class="form-select condition-value">
                                                    <?php foreach ($user_folders as $f): ?>
                                                        <option value="<?php echo htmlspecialchars($f['id']); ?>" <?php echo ($value == $f['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($f['folder_name']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php else: ?>
                                                <input type="text" name="rule_conditions[<?php echo $index; ?>][value]" class="form-control condition-value" value="<?php echo htmlspecialchars($value); ?>">
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-sm-1">
                                            <button type="button" class="btn btn-sm btn-alt-danger remove-condition">&times;</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <button type="button" class="btn btn-sm btn-alt-secondary mt-2" id="add-condition"><i class="fa fa-plus me-1"></i> Add Condition</button>
                    </div>
                </div>
            </div>
            <div class="block-content bg-body-light">
                 <button type="submit" class="btn btn-primary">
                    <i class="fa fa-check-circle me-1"></i> Save Collection
                </button>
                <a href="<?php echo site_url('smartcollections'); ?>" class="btn btn-alt-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Cancel
                </a>
            </div>
        </div>
    </form>

    <?php if (isset($rule['id'])): ?>
    <!-- File Preview Block -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Matching Files (Preview)</h3>
        </div>
        <div class="block-content">
            <?php if (empty($files)): ?>
                <p>No files currently match these rules.</p>
            <?php else: ?>
                 <table class="table table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th>File Name</th>
                            <th>Folder</th>
                            <th>Type</th>
                            <th>Date Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($files as $file): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($file['original_file_name']); ?></td>
                                <td><?php echo htmlspecialchars($file['folder_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($file['mime_type']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($file['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                 </table>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<!-- END Page Content -->

<script>
document.addEventListener('DOMContentLoaded', () => {
    let conditionIndex = <?php echo isset($rule['rule_json']['conditions']) ? count($rule['rule_json']['conditions']) : 0; ?>;

    const addConditionBtn = document.getElementById('add-condition');
    const ruleConditionsDiv = document.getElementById('rule-conditions');
    const allMimeTypes = <?php echo json_encode(array_column($all_mime_types, 'mime_type')); ?>;
    const userFolders = <?php echo json_encode($user_folders); ?>;

    const createConditionRow = (field = 'mime_type', operator = '=', value = '') => {
        const newRow = document.createElement('div');
        newRow.classList.add('row', 'mb-3', 'g-2', 'condition-row');
        newRow.dataset.index = conditionIndex;

        const currentIndex = conditionIndex;
        newRow.innerHTML = `
            <div class="col-sm-4">
                <select name="rule_conditions[${currentIndex}][field]" class="form-select condition-field">
                    <option value="mime_type">File Type</option>
                    <option value="original_file_name">File Name</option>
                    <option value="file_size">File Size (KB)</option>
                    <option value="created_at">Date Added</option>
                    <option value="is_favorited">Is Favorited</option>
                    <option value="folder_id">Folder</option>
                    <option value="tag">Tag</option>
                </select>
            </div>
            <div class="col-sm-3">
                <select name="rule_conditions[${currentIndex}][operator]" class="form-select condition-operator">
                    <option value="LIKE">contains</option>
                    <option value="=">is</option>
                    <option value="!=">is not</option>
                    <option value=">">greater than</option>
                    <option value="<">less than</option>
                </select>
            </div>
            <div class="col-sm-4 condition-value-container">
                ${getValueInputHtml(field, value, currentIndex)}
            </div>
            <div class="col-sm-1">
                <button type="button" class="btn btn-sm btn-alt-danger remove-condition">&times;</button>
            </div>
        `;
        ruleConditionsDiv.appendChild(newRow);
        conditionIndex++;
    };

    const getValueInputHtml = (field, currentValue, index) => {
        let html = '';
        switch (field) {
            case 'mime_type':
                html = `<select name="rule_conditions[${index}][value]" class="form-select condition-value">`;
                allMimeTypes.forEach(mt => {
                    html += `<option value="${mt}" ${currentValue === mt ? 'selected' : ''}>${mt}</option>`;
                });
                html += `</select>`;
                break;
            case 'is_favorited':
                html = `
                    <select name="rule_conditions[${index}][value]" class="form-select condition-value">
                        <option value="1" ${currentValue === '1' || currentValue === true ? 'selected' : ''}>Yes</option>
                        <option value="0" ${currentValue === '0' || currentValue === false ? 'selected' : ''}>No</option>
                    </select>`;
                break;
            case 'folder_id':
                html = `<select name="rule_conditions[${index}][value]" class="form-select condition-value">`;
                userFolders.forEach(folder => {
                    html += `<option value="${folder.id}" ${currentValue == folder.id ? 'selected' : ''}>${folder.folder_name}</option>`;
                });
                html += `</select>`;
                break;
            default:
                html = `<input type="text" name="rule_conditions[${index}][value]" class="form-control condition-value" value="${currentValue}">`;
                break;
        }
        return html;
    };
    
    // Initial setup if no conditions exist
    if (conditionIndex === 0) {
        createConditionRow();
    }

    addConditionBtn.addEventListener('click', () => createConditionRow());

    ruleConditionsDiv.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-condition')) {
            e.target.closest('.condition-row').remove();
        }
    });

    ruleConditionsDiv.addEventListener('change', (e) => {
        if (e.target.classList.contains('condition-field')) {
            const row = e.target.closest('.condition-row');
            const field = e.target.value;
            const valueContainer = row.querySelector('.condition-value-container');
            const currentIndex = row.dataset.index;
            
            valueContainer.innerHTML = getValueInputHtml(field, '', currentIndex);
        }
    });
});
</script>
