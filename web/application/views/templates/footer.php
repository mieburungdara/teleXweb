</div>
</div>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script>
window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            console.log('body classList:', document.body.classList); // Add this log
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

    // Theme switching logic
    const themeLightBtn = document.getElementById('theme-light');
    const themeDarkBtn = document.getElementById('theme-dark');
    const body = document.body;

    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'dark') {
        body.classList.add('dark-theme');
    }

    if(themeLightBtn) {
        themeLightBtn.addEventListener('click', () => {
            body.classList.remove('dark-theme');
            localStorage.setItem('theme', 'light');
        });
    }

    if(themeDarkBtn) {
        themeDarkBtn.addEventListener('click', () => {
            body.classList.add('dark-theme');
            localStorage.setItem('theme', 'dark');
        });
    }

    // Modal logic (remains the same)
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
