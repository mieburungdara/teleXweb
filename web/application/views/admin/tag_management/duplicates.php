<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                Find Duplicate Tags
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Review and merge potential duplicate tags to keep your taxonomy clean.
            </p>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success_message')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error_message')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('errors')): ?>
        <div class="alert alert-danger">
            <strong>Validation Errors:</strong>
            <?php echo $this->session->flashdata('errors'); ?>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="<?php echo site_url('admin/tagmanagement'); ?>" class="btn btn-alt-secondary">
            <i class="fa fa-arrow-left me-1"></i> Back to Tag Management
        </a>
    </div>

    <?php if (empty($duplicates)): ?>
        <div class="block block-rounded">
            <div class="block-content">
                <p class="text-center">No potential duplicate tags found.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
        <?php foreach ($duplicates as $tag_group_name => $tag_group): ?>
            <div class="col-md-6">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Duplicates for "<?php echo htmlspecialchars($tag_group_name); ?>"</h3>
                    </div>
                    <div class="block-content">
                        <?php echo form_open('admin/tagmanagement/merge'); ?>
                            <div class="mb-4">
                                <label class="form-label">Tags to Merge (Source):</label>
                                <?php foreach ($tag_group as $tag): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="source_tag_id" value="<?php echo $tag['id']; ?>" id="source_<?php echo $tag['id']; ?>">
                                        <label class="form-check-label" for="source_<?php echo $tag['id']; ?>">
                                            <?php echo htmlspecialchars($tag['tag_name']); ?> (ID: <?php echo $tag['id']; ?>)
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="target_<?php echo $tag_group[0]['id']; ?>">Merge Into (Target Tag):</label>
                                <select name="target_tag_id" id="target_<?php echo $tag_group[0]['id']; ?>" class="form-select" required>
                                    <option value="">Select Target Tag</option>
                                    <?php foreach ($tag_group as $tag): ?>
                                        <option value="<?php echo $tag['id']; ?>">
                                            <?php echo htmlspecialchars($tag['tag_name']); ?> (ID: <?php echo $tag['id']; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to merge these tags? This action cannot be undone.');">
                                <i class="fa fa-code-merge me-1"></i> Merge Tags
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<!-- END Page Content -->
