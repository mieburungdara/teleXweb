<div class="card">
    <div class="card-header">
        <h1><?php echo $title; ?></h1>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php endif; ?>

        <?php echo form_open('smartcollections/save'); ?>
            <?php if (isset($rule['id'])): ?>
                <input type="hidden" name="id" value="<?php echo $rule['id']; ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="name" class="form-label">Collection Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name', $rule['name'] ?? ''); ?>" required>
            </div>

            <hr>
            <h4>Rules</h4>
            <div id="rule-conditions">
                <?php if (isset($rule['rule_json']['conditions']) && !empty($rule['rule_json']['conditions'])): ?>
                    <?php foreach ($rule['rule_json']['conditions'] as $index => $condition): ?>
                        <div class="row mb-2 condition-row" data-index="<?php echo $index; ?>">
                            <div class="col-md-4">
                                <select name="rule_conditions[<?php echo $index; ?>][field]" class="form-select condition-field">
                                    <option value="mime_type" <?php echo ($condition['field'] ?? '') == 'mime_type' ? 'selected' : ''; ?>>File Type</option>
                                    <option value="original_file_name" <?php echo ($condition['field'] ?? '') == 'original_file_name' ? 'selected' : ''; ?>>File Name</option>
                                    <option value="file_size" <?php echo ($condition['field'] ?? '') == 'file_size' ? 'selected' : ''; ?>>File Size</option>
                                    <option value="created_at" <?php echo ($condition['field'] ?? '') == 'created_at' ? 'selected' : ''; ?>>Date Added</option>
                                    <option value="is_favorited" <?php echo ($condition['field'] ?? '') == 'is_favorited' ? 'selected' : ''; ?>>Is Favorited</option>
                                    <option value="folder_id" <?php echo ($condition['field'] ?? '') == 'folder_id' ? 'selected' : ''; ?>>Folder</option>
                                    <option value="tag" <?php echo ($condition['field'] ?? '') == 'tag' ? 'selected' : ''; ?>>Tag</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="rule_conditions[<?php echo $index; ?>][operator]" class="form-select condition-operator">
                                    <option value="LIKE" <?php echo ($condition['operator'] ?? '') == 'LIKE' ? 'selected' : ''; ?>>contains</option>
                                    <option value="=" <?php echo ($condition['operator'] ?? '') == '=' ? 'selected' : ''; ?>>is</option>
                                    <option value="!=" <?php echo ($condition['operator'] ?? '') == '!=' ? 'selected' : ''; ?>>is not</option>
                                    <option value=">" <?php echo ($condition['operator'] ?? '') == '>' ? 'selected' : ''; ?>>greater than</option>
                                    <option value="<" <?php echo ($condition['operator'] ?? '') == '<' ? 'selected' : ''; ?>>less than</option>
                                </select>
                            </div>
                            <div class="col-md-4">
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
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm remove-condition">&times;</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button type="button" class="btn btn-info btn-sm mt-3" id="add-condition">Add Condition</button>
            <hr>
            <button type="submit" class="btn btn-primary">Save Smart Collection</button>
            <a href="<?php echo site_url('smartcollections'); ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let conditionIndex = <?php echo isset($rule['rule_json']['conditions']) ? count($rule['rule_json']['conditions']) : 0; ?>;

    const addConditionBtn = document.getElementById('add-condition');
    const ruleConditionsDiv = document.getElementById('rule-conditions');
    const allMimeTypes = <?php echo json_encode(array_column($all_mime_types, 'mime_type')); ?>;
    const userFolders = <?php echo json_encode($user_folders); ?>;

    const createConditionRow = (field = 'mime_type', operator = '=', value = '') => {
        const newRow = document.createElement('div');
        newRow.classList.add('row', 'mb-2', 'condition-row');
        newRow.dataset.index = conditionIndex;

        newRow.innerHTML = `
            <div class="col-md-4">
                <select name="rule_conditions[${conditionIndex}][field]" class="form-select condition-field">
                    <option value="mime_type" ${field === 'mime_type' ? 'selected' : ''}>File Type</option>
                    <option value="original_file_name" ${field === 'original_file_name' ? 'selected' : ''}>File Name</option>
                    <option value="file_size" ${field === 'file_size' ? 'selected' : ''}>File Size</option>
                    <option value="created_at" ${field === 'created_at' ? 'selected' : ''}>Date Added</option>
                    <option value="is_favorited" ${field === 'is_favorited' ? 'selected' : ''}>Is Favorited</option>
                    <option value="folder_id" ${field === 'folder_id' ? 'selected' : ''}>Folder</option>
                    <option value="tag" ${field === 'tag' ? 'selected' : ''}>Tag</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="rule_conditions[${conditionIndex}][operator]" class="form-select condition-operator">
                    <option value="LIKE" ${operator === 'LIKE' ? 'selected' : ''}>contains</option>
                    <option value="=" ${operator === '=' ? 'selected' : ''}>is</option>
                    <option value="!=" ${operator === '!=' ? 'selected' : ''}>is not</option>
                    <option value=">" ${operator === '>' ? 'selected' : ''}>greater than</option>
                    <option value="<" ${operator === '<' ? 'selected' : ''}>less than</option>
                </select>
            </div>
            <div class="col-md-4">
                <div class="condition-value-container">
                    ${getValueInputHtml(field, value)}
                </div>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-condition">&times;</button>
            </div>
        `;
        ruleConditionsDiv.appendChild(newRow);
        conditionIndex++;
    };

    const getValueInputHtml = (field, currentValue) => {
        let html = '';
        switch (field) {
            case 'mime_type':
                html = `<select name="rule_conditions[${conditionIndex}][value]" class="form-select condition-value">`;
                allMimeTypes.forEach(mt => {
                    html += `<option value="${mt}" ${currentValue === mt ? 'selected' : ''}>${mt}</option>`;
                });
                html += `</select>`;
                break;
            case 'is_favorited':
                html = `
                    <select name="rule_conditions[${conditionIndex}][value]" class="form-select condition-value">
                        <option value="1" ${currentValue === '1' || currentValue === true ? 'selected' : ''}>Yes</option>
                        <option value="0" ${currentValue === '0' || currentValue === false ? 'selected' : ''}>No</option>
                    </select>`;
                break;
            case 'folder_id':
                html = `<select name="rule_conditions[${conditionIndex}][value]" class="form-select condition-value">`;
                userFolders.forEach(folder => {
                    html += `<option value="${folder.id}" ${currentValue == folder.id ? 'selected' : ''}>${folder.folder_name}</option>`;
                });
                html += `</select>`;
                break;
            default:
                html = `<input type="text" name="rule_conditions[${conditionIndex}][value]" class="form-control condition-value" value="${currentValue}">`;
                break;
        }
        return html;
    };

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
            const currentIndex = row.dataset.index; // Use the existing index for name attribute
            
            let html = '';
            switch (field) {
                case 'mime_type':
                    html = `<select name="rule_conditions[${currentIndex}][value]" class="form-select condition-value">`;
                    allMimeTypes.forEach(mt => {
                        html += `<option value="${mt}">${mt}</option>`;
                    });
                    html += `</select>`;
                    break;
                case 'is_favorited':
                    html = `
                        <select name="rule_conditions[${currentIndex}][value]" class="form-select condition-value">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>`;
                    break;
                case 'folder_id':
                    html = `<select name="rule_conditions[${currentIndex}][value]" class="form-select condition-value">`;
                    userFolders.forEach(folder => {
                        html += `<option value="${folder.id}">${folder.folder_name}</option>`;
                    });
                    html += `</select>`;
                    break;
                default:
                    html = `<input type="text" name="rule_conditions[${currentIndex}][value]" class="form-control condition-value" value="">`;
                    break;
            }
            valueContainer.innerHTML = html;
        }
    });
});
</script>
