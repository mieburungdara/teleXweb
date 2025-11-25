<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Mini App</title>
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
        }
        h1, h2 {
            color: var(--tg-theme-text-color);
        }
        a {
            color: var(--tg-theme-link-color);
        }
        #user-data {
            border: 1px solid var(--tg-theme-hint-color);
            padding: 10px;
            border-radius: 5px;
            word-wrap: break-word;
            background-color: var(--tg-theme-secondary-bg-color);
        }
        #auth-status {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            background-color: var(--tg-theme-secondary-bg-color);
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

        <button id="haptic-button" style="background-color: var(--tg-theme-secondary-bg-color); color: var(--tg-theme-text-color); border: 1px solid var(--tg-theme-hint-color); padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top: 20px; margin-left: 10px;">Test Haptic Feedback</button>

        <button id="popup-button" style="background-color: var(--tg-theme-link-color); color: var(--tg-theme-button-text-color); border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top: 20px; margin-left: 10px;">Trigger Popup</button>

    

        <script>

            // Get bot_id passed from the controller

            const botId = <?php echo json_encode($bot_id); ?>;

    

            // Initialize the Telegram Web App

            Telegram.WebApp.ready();

            Telegram.WebApp.expand(); // Expand the Mini App to full height

    

            // --- Main Button ---

            if (Telegram.WebApp.MainButton) {

                Telegram.WebApp.MainButton.setText('Go to Dashboard');

                Telegram.WebApp.MainButton.onClick(() => {

                    // Manually trigger a redirect, or define a specific action

                    Telegram.WebApp.close(); // For example, close the app

                });

                Telegram.WebApp.MainButton.show();

            }

    

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

    

            // --- Handle Haptic Button ---

            const hapticButton = document.getElementById('haptic-button');

            if (hapticButton) { // Ensure button exists

                hapticButton.addEventListener('click', () => {

                    if (Telegram.WebApp.HapticFeedback) {

                        Telegram.WebApp.HapticFeedback.impactOccurred('medium');

                    }

                    if (Telegram.WebApp.showAlert) {

                        Telegram.WebApp.showAlert('Haptic feedback triggered!');

                    }

                });

            }

    

            // --- Handle Popup Button ---

            const popupButton = document.getElementById('popup-button');

            if (popupButton) {

                popupButton.addEventListener('click', () => {

                    if (Telegram.WebApp.showPopup) {

                        Telegram.WebApp.showPopup(

                            {

                                title: 'Confirmation',

                                message: 'Are you sure you want to perform this action?',

                                buttons: [

                                    {id: 'yes', type: 'destructive', text: 'Yes!'},

                                    {id: 'no', type: 'cancel'},

                                ]

                            },

                            (buttonId) => {

                                if (buttonId === 'yes') {

                                    Telegram.WebApp.showAlert('You confirmed the action!');

                                } else {

                                    Telegram.WebApp.showAlert('You cancelled the action.');

                                }

                            }

                        );

                    } else {

                        alert('Popup not supported in this Telegram version.');

                    }

                });

            }

    

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

                    if (Telegram.WebApp.HapticFeedback) {

                        if (response.redirected) {

                            Telegram.WebApp.HapticFeedback.notificationOccurred('success');

                        } else {

                            // Assume non-redirect means some form of error or non-success

                            Telegram.WebApp.HapticFeedback.notificationOccurred('error');

                        }

                    }

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

                    if (Telegram.WebApp.HapticFeedback) {

                        Telegram.WebApp.HapticFeedback.notificationOccurred('error');

                    }

                    authStatusDiv.style.color = 'red';

                    authStatusDiv.innerHTML = '<h2>Backend Verification</h2><p>❌ A network error occurred during authentication.</p>';

                    console.error('Network error during authentication:', error);

                });

            } else {

                if (Telegram.WebApp.HapticFeedback) {

                    Telegram.WebApp.HapticFeedback.notificationOccurred('error');

                }

                authStatusDiv.style.color = 'red';

                authStatusDiv.innerHTML = '<h2>Backend Verification</h2><p>❌ No initData found. Please open this app inside Telegram.</p>';

            }

    </script>

</body>
</html>
