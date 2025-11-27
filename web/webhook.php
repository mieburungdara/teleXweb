// A simple webhook script to receive Telegram updates and forward them to the CodeIgniter API.

// --- Configuration ---
$CI_API_BASE_URL = 'https://<YOUR_DOMAIN_HERE>/web/api/'; // Base URL for CodeIgniter API

// --- Helper Function ---
/**
 * Sends a POST request using cURL.
 * @param string $url The URL to send the request to.
 * @param array $data The data to send.
 * @return array The decoded JSON response and HTTP code.
 */
function send_curl_request($url, $data) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For dev only
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['response' => $response, 'http_code' => $http_code];
}

// --- Get Update from Telegram ---
$update = json_decode(file_get_contents('php://input'), true);

if (!$update) {
    exit();
}

// Log all incoming updates for debugging
file_put_contents('webhook.log', date('Y-m-d H:i:s') . " - Received Update: " . json_encode($update) . "\n", FILE_APPEND);

// --- Get Bot ID ---
$request_uri = $_SERVER['REQUEST_URI'] ?? '';
$parts = explode('/', $request_uri);
$bot_id = end($parts);
if (!is_numeric($bot_id)) {
    file_put_contents('webhook.log', date('Y-m-d H:i:s') . " - Invalid or missing bot_id in URL: {$request_uri}\n", FILE_APPEND);
    http_response_code(400);
    exit();
}

// --- Process Update ---
$message = $update['message'] ?? null;
if (!$message) {
    exit();
}

$telegram_user = $message['from'] ?? null;
if (!$telegram_user) {
    exit();
}

$chat_id = $message['chat']['id'];
$text = $message['text'] ?? '';

// --- Command Handling ---
if (isset($text[0]) && $text[0] === '/') {
    $parts = explode(' ', $text);
    $command = strtolower(str_replace('/', '', $parts[0]));
    $args = array_slice($parts, 1);

    $response_text = '';

    switch ($command) {
        case 'start':
            $response_text = "Welcome! This bot helps you manage your files. Send me a file to get started, or use /help for more commands.";
            break;
        case 'help':
            $response_text = "Available commands:\n/start - Welcome message\n/help - Show this message\n/recent [N] - Get your N recent files\n/search [keyword] - Search your files\n\nSimply send any file (document, photo, video) to save its metadata.";
            break;
        case 'recent':
            $limit = isset($args[0]) && is_numeric($args[0]) ? (int)$args[0] : 5;
            $api_url = $CI_API_BASE_URL . 'get_recent_files';
            $post_data = ['user_id' => $telegram_user['id'], 'limit' => $limit];
            $result = send_curl_request($api_url, $post_data);
            $data = json_decode($result['response'], true);
            if (isset($data['status']) && $data['status'] === 'success' && !empty($data['files'])) {
                $response_text = "Your {$limit} most recent files:\n";
                foreach ($data['files'] as $file) {
                    $response_text .= "- " . ($file['original_file_name'] ?? 'Untitled') . "\n";
                }
            } else {
                $response_text = "No recent files found.";
            }
            break;
        case 'search':
            $keyword = implode(' ', $args);
            if (empty($keyword)) {
                $response_text = "Please provide a keyword to search for. Usage: /search [keyword]";
                break;
            }
            $api_url = $CI_API_BASE_URL . 'search_files';
            $post_data = ['user_id' => $telegram_user['id'], 'keyword' => $keyword];
            $result = send_curl_request($api_url, $post_data);
            $data = json_decode($result['response'], true);
            if (isset($data['status']) && $data['status'] === 'success' && !empty($data['files'])) {
                $response_text = "Search results for '{$keyword}':\n";
                foreach ($data['files'] as $file) {
                    $response_text .= "- " . ($file['original_file_name'] ?? 'Untitled') . "\n";
                }
            } else {
                $response_text = "No files found matching '{$keyword}'.";
            }
            break;
        case 'fav':
            $file_id = $args[0] ?? null;
            if (empty($file_id) || !is_numeric($file_id)) {
                $response_text = "Please provide a file ID to favorite. Usage: /fav [file_id]";
                break;
            }
            $api_url = $CI_API_BASE_URL . 'toggle_favorite';
            $post_data = ['user_id' => $telegram_user['id'], 'file_id' => $file_id];
            $result = send_curl_request($api_url, $post_data);
            $data = json_decode($result['response'], true);
            if (isset($data['status']) && $data['status'] === 'success') {
                $response_text = "Favorite status for file ID {$file_id} has been toggled.";
            } else {
                $response_text = "Could not toggle favorite status for file ID {$file_id}. Please check if the file ID is correct.";
            }
            break;
    }

    if (!empty($response_text)) {
        $api_url = $CI_API_BASE_URL . 'send_message';
        $post_data = [
            'bot_id' => $bot_id,
            'chat_id' => $chat_id,
            'text' => $response_text
        ];
        $result = send_curl_request($api_url, $post_data);
        file_put_contents('webhook.log', date('Y-m-d H:i:s') . " - Command '{$command}' response: " . json_encode($result) . "\n", FILE_APPEND);
    }
    
    http_response_code(200);
    exit();
}


// --- File Handling ---
$file_data = null;
if (isset($message['document'])) {
    $file_data = $message['document'];
} elseif (isset($message['photo'])) {
    $file_data = end($message['photo']);
} elseif (isset($message['video'])) {
    $file_data = $message['video'];
} elseif (isset($message['audio'])) {
    $file_data = $message['audio'];
}

if (!$file_data) {
    // No file found, and not a command, so we do nothing.
    exit();
}

// Prepare Data for CodeIgniter API
$post_data = [
    'bot_id' => $bot_id,
    'original_chat_id' => $message['chat']['id'],
    'original_message_id' => $message['message_id'],
    'telegram_user_id' => $telegram_user['id'],
    'file_unique_id' => $file_data['file_unique_id'],
    'telegram_file_id' => $file_data['file_id'],
    'media_group_id' => $message['media_group_id'] ?? null,
    'thumbnail_file_id' => $file_data['thumbnail']['file_id'] ?? null,
    'file_name' => $file_data['file_name'] ?? null,
    'original_file_name' => $file_data['file_name'] ?? ('file_from_' . $telegram_user['id']),
    'file_size' => $file_data['file_size'] ?? null,
    'mime_type' => $file_data['mime_type'] ?? null,
];

// Send Data to CodeIgniter API using cURL
$api_url = $CI_API_BASE_URL . 'upload';
$result = send_curl_request($api_url, $post_data);

// Log the interaction
$log_message = date('Y-m-d H:i:s') . " - File Upload. Forwarded to {$api_url}. Response code: {$result['http_code']}. Response: {$result['response']}\n";
file_put_contents('webhook.log', $log_message, FILE_APPEND);

// Respond to Telegram
http_response_code(200);

?>
