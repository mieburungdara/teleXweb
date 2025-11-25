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

    <script>
        // Initialize the Telegram Web App
        Telegram.WebApp.ready();
        Telegram.WebApp.expand();

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

        // --- Send data to backend for verification ---
        const authStatusDiv = document.getElementById('auth-status');
        if (initData) {
            fetch('<?php echo site_url('miniapp/auth'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'init_data=' + initData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    authStatusDiv.style.color = 'green';
                    authStatusDiv.innerHTML = '<h2>Backend Verification</h2><p>✅ User data verified successfully by the backend.</p>';
                } else {
                    authStatusDiv.style.color = 'red';
                    authStatusDiv.innerHTML = `<h2>Backend Verification</h2><p>❌ ${data.message}</p>`;
                }
            })
            .catch(error => {
                authStatusDiv.style.color = 'red';
                authStatusDiv.innerHTML = '<h2>Backend Verification</h2><p>❌ An error occurred during verification.</p>';
                console.error('Error:', error);
            });
        } else {
            authStatusDiv.style.color = 'red';
            authStatusDiv.innerHTML = '<h2>Backend Verification</h2><p>❌ No initData found. Please open this app inside Telegram.</p>';
        }

    </script>

</body>
</html>
