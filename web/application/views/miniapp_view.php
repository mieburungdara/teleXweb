<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Mini App</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            padding: 20px;
            color: var(--tg-theme-text-color);
            background-color: var(--tg-theme-bg-color);
        }
        #user-data {
            border: 1px solid var(--tg-theme-hint-color);
            padding: 10px;
            border-radius: 5px;
            word-wrap: break-word;
        }
    </style>
</head>
<body>

    <h1>Welcome to the Telegram Mini App!</h1>

    <p>This is a simple web application running inside Telegram.</p>

    <div id="user-data">
        <h2>User Information (from frontend)</h2>
        <p>Loading user data...</p>
    </div>

    <div id="auth-status">
        <h2>Backend Verification</h2>
        <p>Verifying with backend...</p>
    </div>

    <button id="close-button" style="background-color: var(--tg-theme-button-color); color: var(--tg-theme-button-text-color); border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Close Mini App</button>

    <script>
        // Get bot_id passed from the controller
        const botId = <?php echo json_encode($bot_id); ?>;

        // Initialize the Telegram Web App
        Telegram.WebApp.ready();
        Telegram.WebApp.expand(); // Expand the Mini App to full height

        // Set body background and text color based on Telegram theme
        document.body.style.backgroundColor = Telegram.WebApp.themeParams.bg_color || '#ffffff';
        document.body.style.color = Telegram.WebApp.themeParams.text_color || '#000000';

        const initData = Telegram.WebApp.initData;
        const initDataUnsafe = Telegram.WebApp.initDataUnsafe;

        // --- Display unsafe data on the frontend ---
        const user = initDataUnsafe.user;
        const userDataDiv = document.getElementById('user-data');
        if (user) {
            userDataDiv.innerHTML = `
                <h2>User Information (from frontend)</h2>
                <p><strong>ID:</strong> ${user.id}</p>
                <p><strong>First Name:</strong> ${user.first_name}</p>
                <p><strong>Last Name:</strong> ${user.last_name || 'N/A'}</p>
                <p><strong>Username:</strong> @${user.username || 'N/A'}</p>
            `;
        } else {
            userDataDiv.innerHTML = '<h2>User Information (from frontend)</h2><p>Could not retrieve user information.</p>';
        }

        // --- Handle Close Button ---
        const closeButton = document.getElementById('close-button');
        closeButton.addEventListener('click', () => {
            Telegram.WebApp.close();
        });

        // --- Send data to backend for verification and redirection ---
        const authStatusDiv = document.getElementById('auth-status');
        if (initData) {
            authStatusDiv.innerHTML = '<h2>Backend Verification</h2><p>Authenticating and redirecting...</p>';
            fetch('<?php echo site_url('miniapp/auth'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'init_data=' + initData + '&bot_id=' + botId
            })
            .then(response => {
                // The browser will handle the redirect if the backend sends a 3xx status
                // If there's no redirect, it means something unexpected happened or a non-redirect response was sent.
                if (response.redirected) {
                    window.location.href = response.url; // Manually ensure redirect in case fetch doesn't
                } else {
                    // This case might happen if the backend sends a non-redirect error (e.g., 200 with an error page content)
                    // Or if the initial auth failed for some reason before redirect
                    return response.text(); // Read as text to see content
                }
            })
            .then(text => {
                if (text) { // If there was some non-redirect content
                    authStatusDiv.style.color = 'red';
                    authStatusDiv.innerHTML = `<h2>Backend Verification</h2><p>❌ Authentication failed, no redirect: ${text.substring(0, 200)}...</p>`;
                    console.error('Authentication fetch did not redirect, received:', text);
                }
            })
            .catch(error => {
                authStatusDiv.style.color = 'red';
                authStatusDiv.innerHTML = '<h2>Backend Verification</h2><p>❌ A network error occurred during authentication.</p>';
                console.error('Network error during authentication:', error);
            });
        } else {
            authStatusDiv.style.color = 'red';
            authStatusDiv.innerHTML = '<h2>Backend Verification</h2><p>❌ No initData found. Please open this app inside Telegram.</p>';
        }

    </script>

</body>
</html>
