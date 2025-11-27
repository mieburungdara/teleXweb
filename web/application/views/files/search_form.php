<div class="card mb-4">
    <div class="card-header">
        Advanced Search
    </div>
    <div class="card-body">
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
                        <option value="">All Favorites</option>
                        <option value="1" <?php echo (isset($filters['is_favorited']) && $filters['is_favorited'] == '1') ? 'selected' : ''; ?>>Favorited</option>
                        <option value="0" <?php echo (isset($filters['is_favorited']) && $filters['is_favorited'] == '0') ? 'selected' : ''; ?>>Not Favorited</option>
                    </select>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="<?php echo site_url('files'); ?>" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>
