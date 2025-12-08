<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : lang('teleXweb'); ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        window.csrfData = {
            tokenName: '<?php echo $this->security->get_csrf_token_name(); ?>',
            tokenHash: '<?php echo $this->security->get_csrf_hash(); ?>'
        };
    </script>
    <link href="<?php echo base_url('assets/css/sidebar.css'); ?>" rel="stylesheet">
</head>
<body>

<button id="sidebar-toggle-btn" class="btn btn-dark d-md-none"><i class="fas fa-bars"></i></button>

<?php $this->load->view('templates/sidebar'); ?>

<div id="main-content" class="main-content">
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

