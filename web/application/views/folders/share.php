<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                Share Folder: <?php echo htmlspecialchars($folder['folder_name']); ?>
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Use the links below to share this folder with others.
            </p>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Sharing Options</h3>
        </div>
        <div class="block-content">
            <div class="row push">
                <div class="col-lg-8 col-xl-6">
                    <div class="mb-4">
                        <label for="shareLink" class="form-label">Shareable Web Link</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="shareLink" value="<?php echo site_url('folders/view_shared/' . $folder['code']); ?>" readonly>
                            <button type="button" class="btn btn-alt-primary" onclick="copyToClipboard('shareLink')">Copy</button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="telegramLink" class="form-label">Telegram Deep Link</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="telegramLink" value="https://t.me/<YOUR_BOT_USERNAME>?start=folder_<?php echo $folder['code']; ?>" readonly>
                            <button type="button" class="btn btn-alt-primary" onclick="copyToClipboard('telegramLink')">Copy</button>
                        </div>
                        <div class="form-text">Replace &lt;YOUR_BOT_USERNAME&gt; with your actual bot's username.</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block-content block-content-full bg-body-light text-end">
            <a href="<?php echo site_url('folders'); ?>" class="btn btn-alt-secondary">
                <i class="fa fa-arrow-left me-1"></i> Back to Folders
            </a>
        </div>
    </div>
</div>
<!-- END Page Content -->

<script>
function copyToClipboard(elementId) {
  // Get the text field
  var copyText = document.getElementById(elementId);

  // Select the text field
  copyText.select();
  copyText.setSelectionRange(0, 99999); // For mobile devices

  // Copy the text inside the text field
  navigator.clipboard.writeText(copyText.value).then(function() {
    // Optional: Alert the user that the text was copied
    alert("Copied the text: " + copyText.value);
  }, function(err) {
    console.error('Could not copy text: ', err);
  });
}
</script>
