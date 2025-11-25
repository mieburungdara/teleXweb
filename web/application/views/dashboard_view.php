<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Telegram Mini App</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            padding: 20px;
            background-color: #f0f2f5;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #007bff;
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 10px;
        }
        .user-info span {
            font-weight: bold;
        }
        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard Mini App Telegram</h1>
        <p>Selamat datang di dashboard Anda!</p>

        <?php if ($this->session->userdata('logged_in')): ?>
            <div class="user-info">
                <p>Halo, <span><?php echo $this->session->userdata('username') ?? 'Pengguna'; ?></span>!</p>
                <p>ID Telegram: <span><?php echo $this->session->userdata('telegram_id'); ?></span></p>
                <p>Role: <span><?php echo $this->session->userdata('role_name'); ?> (ID: <?php echo $this->session->userdata('role_id'); ?>)</span></p>
            </div>
            <p>Ini adalah halaman yang dilindungi, hanya dapat diakses setelah autentikasi berhasil.</p>
        <?php else: ?>
            <p class="error-message">Anda tidak login.</p>
        <?php endif; ?>
    </div>
</body>
</html>
