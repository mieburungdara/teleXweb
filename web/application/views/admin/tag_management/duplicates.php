<div class="card">
    <div class="card-header">
        <h1>Find Duplicate Tags</h1>
        <p>Review and merge potential duplicate tags.</p>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('success_message')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('errors')): ?>
            <div class="alert alert-danger">
                <h4>Validation Errors:</h4>
                <?php echo $this->session->flashdata('errors'); ?>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <a href="<?php echo site_url('admin/tagmanagement'); ?>" class="btn btn-secondary">Back to Tag Management</a>
        </div>

        <?php if (empty($duplicates)): ?>
            <p>No potential duplicate tags found.</p>
        <?php else: ?>
            <?php foreach ($duplicates as $tag_group_name => $tag_group): ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Potential Duplicates for "<?php echo htmlspecialchars($tag_group_name); ?>"</h5>
                    </div>
                    <div class="card-body">
                        <?php echo form_open('admin/tagmanagement/merge'); ?>
                            <div class="mb-3">
                                <label class="form-label">Tags to Merge:</label>
                                <?php foreach ($tag_group as $tag): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="source_tag_id" value="<?php echo $tag['id']; ?>" id="source_<?php echo $tag['id']; ?>">
                                        <label class="form-check-label" for="source_<?php echo $tag['id']; ?>">
                                            <?php echo htmlspecialchars($tag['tag_name']); ?> (ID: <?php echo $tag['id']; ?>)
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Merge Into (Target Tag):</label>
                                <select name="target_tag_id" class="form-select" required>
                                    <option value="">Select Target Tag</option>
                                    <?php foreach ($tag_group as $tag): ?>
                                        <option value="<?php echo $tag['id']; ?>">
                                            <?php echo htmlspecialchars($tag['tag_name']); ?> (ID: <?php echo $tag['id']; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to merge these tags? This action cannot be undone.');">Merge Tags</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
