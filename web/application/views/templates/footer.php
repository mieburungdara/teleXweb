</div>
</div>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script>
window.addEventListener('DOMContentLoaded', event => {

    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    const sidebarWrapper = document.getElementById('sidebar-wrapper'); // Get sidebar element

    // Function to set the icon based on the sidebar state
    // Defined here to be accessible for both toggle button and click-outside logic
    const updateIcon = () => {
        if (!sidebarToggle) return; // Ensure sidebarToggle exists before updating its content
        // The sb-sidenav-toggled class has different meanings on mobile and desktop
        const isToggled = document.body.classList.contains('sb-sidenav-toggled');
        const isDesktop = window.matchMedia('(min-width: 768px)').matches;
        
        let isVisible;
        if (isDesktop) {
            // On desktop, "not toggled" means visible
            isVisible = !isToggled;
        } else {
            // On mobile, "toggled" means visible
            isVisible = isToggled;
        }

        if (isVisible) {
            sidebarToggle.innerHTML = '&times;'; // 'X' close icon
        } else {
            sidebarToggle.innerHTML = '&#9776;'; // Hamburger menu icon
        }
    };

    // Toggle the side navigation (existing logic, slightly refactored)
    if (sidebarToggle) {
        // Set the initial icon on page load
        updateIcon();

        // Update the icon if the window is resized, as the logic changes
        window.addEventListener('resize', updateIcon);
        
        // Logic to remember sidebar state is commented out, but if enabled,
        // we would need to update the icon after loading the state.
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        //     updateIcon();
        // }

        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
            updateIcon(); // Update icon after every click
        });
    }

    // Click outside to collapse sidebar
    document.addEventListener('click', event => {
        const isDesktop = window.matchMedia('(min-width: 768px)').matches;

        // Check if the clicked target is outside the sidebar and not the toggle button itself
        // Also ensure sidebarWrapper exists (it might not on some pages)
        const isClickOutsideSidebar = sidebarWrapper && !sidebarWrapper.contains(event.target);
        const isClickOnToggleButton = sidebarToggle && sidebarToggle.contains(event.target);

        // Determine if sidebar is currently open
        let isSidebarCurrentlyOpen;
        if (isDesktop) {
            isSidebarCurrentlyOpen = !document.body.classList.contains('sb-sidenav-toggled');
        } else {
            isSidebarCurrentlyOpen = document.body.classList.contains('sb-sidenav-toggled');
        }

        // Only collapse if sidebar is open, click was outside, and not on the toggle button
        if (isClickOutsideSidebar && !isClickOnToggleButton && isSidebarCurrentlyOpen) {
            event.preventDefault(); // Prevent any default action on the outside click
            
            // Collapse the sidebar: toggle the class based on desktop/mobile logic
            if (isDesktop) {
                // On desktop, add sb-sidenav-toggled to hide it
                document.body.classList.add('sb-sidenav-toggled');
            } else {
                // On mobile, remove sb-sidenav-toggled to hide it
                document.body.classList.remove('sb-sidenav-toggled');
            }
            
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
            updateIcon(); // Update icon after collapsing
        }
    });

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
