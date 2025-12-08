</div> <!-- closing .main-content -->

<footer class="text-center mt-4 py-3 bg-light">
    <div class="container">
        <p class="text-muted">&copy; <?php echo date('Y'); ?> teleXweb. All Rights Reserved.</p>
    </div>
</footer>

<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="previewModalLabel"><?php echo lang('file_preview'); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo lang('close'); ?>"></button>
      </div>
      <div class="modal-body" id="previewModalBody">
        <!-- Content will be loaded here via AJAX -->
        <p><?php echo lang('loading'); ?>...</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo lang('close'); ?></button>
      </div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 5 JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

<script>
$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const previewModal = document.getElementById('previewModal');
    if (previewModal) {
        previewModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const fileId = button.getAttribute('data-id');
            const modalBody = document.getElementById('previewModalBody');
            modalBody.innerHTML = '<p>Loading...</p>';

            fetch(`<?php echo site_url('api/file_preview_data/'); ?>${fileId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const file = data.file;
                        let content = `
                            <h3>${file.original_file_name || 'N/A'}</h3>
                            <table class="table">
                                <tr><th>ID</th><td>${file.id}</td></tr>
                                <tr><th>Type</th><td>${file.mime_type || 'N/A'}</td></tr>
                                <tr><th>Size</th><td>${file.file_size ? (file.file_size / 1024).toFixed(2) + ' KB' : 'N/A'}</td></tr>
                                <tr><th>Folder</th><td>${file.folder_name || 'Unfiled'}</td></tr>
                            </table>
                        `;
                        if (file.thumbnail_url) {
                            content = `<div class="text-center"><img src="${file.thumbnail_url}" class="img-fluid mb-3"></div>` + content;
                        }
                        modalBody.innerHTML = content;
                    } else {
                        modalBody.innerHTML = `<p class="text-danger">Error: ${data.message}</p>`;
                    }
                })
                .catch(error => {
                    modalBody.innerHTML = '<p class="text-danger">Failed to load file data.</p>';
                    console.error('Error:', error);
                });
        });
    }
});
</script>

</body>
</html>
