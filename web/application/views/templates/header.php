<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : lang('teleXweb'); ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        window.csrfData = {
            tokenName: '<?php echo $this->security->get_csrf_token_name(); ?>',
            tokenHash: '<?php echo $this->security->get_csrf_hash(); ?>'
        };
    </script>
<style>
    body {
        background-color: #f8f9fa;
        color: #212529; /* Default text color */
    }

    .main-content {
        margin-left: 280px; /* Same as sidebar width */
        padding: 20px;
    }

    /* Dark Theme Styles */
    body.dark-theme {
        background-color: #212529; /* Dark background */
        color: #f8f9fa; /* Light text color */
    }
    body.dark-theme .card {
        background-color: #343a40;
        color: #f8f9fa;
        border-color: #454d55;
    }
    body.dark-theme .card-header {
        background-color: #454d55;
        color: #f8f9fa;
        border-color: #343a40;
    }
    body.dark-theme .table {
        color: #f8f9fa;
    }
    body.dark-theme .table-striped > tbody > tr:nth-of-type(odd) > * {
        background-color: rgba(255, 255, 255, 0.05);
    }
    body.dark-theme .list-group-item {
        background-color: #343a40;
        color: #f8f9fa;
        border-color: #454d55;
    }
    body.dark-theme .breadcrumb-item a {
        color: #adb5bd;
    }
    body.dark-theme .breadcrumb-item.active {
        color: #e9ecef;
    }
    body.dark-theme .btn-secondary {
        background-color: #5a6268;
        border-color: #545b62;
    }
    body.dark-theme .text-muted {
        color: #adb5bd !important;
    }
</style>
</head>
<body>

<?php $this->load->view('templates/sidebar'); ?>

<div class="main-content">
<script>
document.addEventListener('DOMContentLoaded', () => {
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
});
</script>

