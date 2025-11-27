<?php $this->load->view('templates/header', ['title' => 'Share Folder']); ?>

<div class="card">
    <div class="card-header">
        <h1>Share Folder: <?php echo htmlspecialchars($folder['folder_name']); ?></h1>
    </div>
    <div class="card-body">
        <p>Use the links below to share this folder.</p>

        <div class="mb-3">
            <label for="shareLink" class="form-label">Shareable Web Link</label>
            <input type="text" class="form-control" id="shareLink" value="<?php echo site_url('folders/view_shared/' . $folder['code']); ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="telegramLink" class="form-label">Telegram Deep Link</label>
            <input type="text" class="form-control" id="telegramLink" value="https://t.me/<YOUR_BOT_USERNAME>?start=folder_<?php echo $folder['code']; ?>" readonly>
            <small class="form-text text-muted">Replace &lt;YOUR_BOT_USERNAME&gt; with your actual bot's username.</small>
        </div>
        
        <a href="<?php echo site_url('folders'); ?>" class="btn btn-secondary mt-3">Back to Folders</a>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
