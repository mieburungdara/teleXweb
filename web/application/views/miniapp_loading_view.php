<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Mini App - Loading</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <style>
        :root {
            --tg-theme-bg-color: #ffffff;
            --tg-theme-text-color: #000000;
            --tg-theme-hint-color: #999999;
            --tg-theme-link-color: #2481cc;
            --tg-theme-button-color: #2481cc;
            --tg-theme-button-text-color: #ffffff;
            --tg-theme-secondary-bg-color: #f0f0f0;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            padding: 20px;
            color: var(--tg-theme-text-color);
            background-color: var(--tg-theme-bg-color);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            text-align: center;
        }
        h1, h2, p {
            color: var(--tg-theme-text-color);
        }
        .loader {
            border: 8px solid var(--tg-theme-secondary-bg-color);
            border-top: 8px solid var(--tg-theme-button-color);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 2s linear infinite;
            margin-bottom: 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        #auth-status {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            background-color: var(--tg-theme-secondary-bg-color);
            color: var(--tg-theme-text-color);
        }
    </style>
</head>
<body>

    <div class="loader"></div>
    <h1>Loading Telegram Mini App...</h1>
    <p>Please wait while we verify your identity.</p>
    <div id="auth-status"></div>

    <script>
            // Get bot_id passed from the controller
            const botId = <?php echo json_encode($bot_id); ?>;

            // Initialize the Telegram Web App
            Telegram.WebApp.ready();
            Telegram.WebApp.expand(); // Expand the Mini App to full height

            // Apply Telegram theme parameters to CSS variables
            if (Telegram.WebApp.themeParams) {
                for (let key in Telegram.WebApp.themeParams) {
                    if (Telegram.WebApp.themeParams.hasOwnProperty(key)) {
                        document.documentElement.style.setProperty(`--tg-theme-${key}`, Telegram.WebApp.themeParams[key]);
                    }
                }
            }
            // Also update the body directly for initial load
            document.body.style.backgroundColor = Telegram.WebApp.themeParams.bg_color || 'var(--tg-theme-bg-color)';
            document.body.style.color = Telegram.WebApp.themeParams.text_color || 'var(--tg-theme-text-color)';

            const initData = Telegram.WebApp.initData;
            
                        console.log('initData to:', initData);
            
            // --- Send data to backend for verification and redirection ---
            const authStatusDiv = document.getElementById('auth-status');
            const requestBody = 'init_data=' + encodeURIComponent(initData) + '&bot_id=' + botId;

            if (initData) {
                authStatusDiv.innerHTML = '<h2>Backend Verification</h2><p>Authenticating and redirecting...</p>';
                fetch('<?php echo site_url('miniapp/auth'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': 'application/json' // We expect a JSON response
                    },
                    body: requestBody
                })
                .then(response => {
                    // Check if the response is successful (2xx status) and is JSON
                    if (response.ok && response.headers.get("Content-Type")?.includes("application/json")) {
                        return response.json();
                    }
                    // Handle non-JSON or error responses
                    return response.text().then(text => {
                        throw new Error(`Server returned status ${response.status}: ${text}`);
                    });
                })
                .then(data => {
                    if (data && data.status === 'success' && data.redirect_url) {
                        if (Telegram.WebApp.HapticFeedback) {
                            Telegram.WebApp.HapticFeedback.notificationOccurred('success');
                        }
                        window.location.href = data.redirect_url;
                    } else {
                        throw new Error(data.message || 'Authentication failed: Invalid response from server.');
                    }
                })
                .catch(error => {
                    if (Telegram.WebApp.HapticFeedback) {
                        Telegram.WebApp.HapticFeedback.notificationOccurred('error');
                    }
                    authStatusDiv.style.color = 'red';
                    authStatusDiv.innerHTML = `<h2>Backend Verification</h2><p>❌ ${error.message}</p>`;
                    console.error('Authentication error:', error);
                });
            } else {
                console.log("Entering else block for initData missing. Initiating redirect.");
                if (Telegram.WebApp.HapticFeedback) {
                    Telegram.WebApp.HapticFeedback.notificationOccurred('error');
                }
                authStatusDiv.style.color = 'red';
                authStatusDiv.innerHTML = '<h2>Backend Verification</h2><p>❌ No initData found. Redirecting to unauthorized page...</p>';
                window.location.href = '<?php echo site_url('miniapp/unauthorized'); ?>';
            }
    </script>
</body>
</html>