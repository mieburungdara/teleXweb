<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Telegram Mini App</title>
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
            text-align: center;
        }
        h1 {
            color: #dc3545;
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 15px;
            color: #6c757d;
        }
        .error-message {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Kesalahan Otentikasi</h1>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php else: ?>
            <p>Terjadi kesalahan yang tidak terduga. Silakan coba lagi.</p>
        <?php endif; ?>
        <p>Silakan tutup Mini App ini dan coba buka lagi dari bot Telegram.</p>
    </div>
</body>
</html>
