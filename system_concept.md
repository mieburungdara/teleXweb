# System Concept (CodeIgniter 3)

The system is a monolithic web application built with CodeIgniter 3.

*   **Backend (CodeIgniter 3):**
    *   **Controllers:**
        *   `Files.php`: Handles web requests. It will have methods like `index()` for the main table view, `gallery()` for the image gallery view, and `details($id)`. The `index()` method will be updated to handle complex filtering from the advanced search form and folder browsing, and to group files belonging to the same `media_group_id` for album display. It will also include a method to handle incoming deep links for shared folders. Access to files will be logged via `Access_Log_model`.
        *   `Folders.php`: A new controller to handle folder management operations (create, rename, delete, move files and folders) and also manage folder ratings and reviews (submit, edit, delete). Access to folders will be logged via `Access_Log_model`. It will also handle liking/unliking public folders via `Folder_Like_model`.
        *   `Admin.php`: A new controller, accessible only to admin users, to handle user management, display the analytics dashboard, provide the System Health Dashboard, display the Audit Trail, manage the Webhook Retry Dashboard, and curate Public Collections.
        *   `Notifications.php`: A new controller for users to manage their custom notification rules, including selecting templates and configuring throttling.
        *   `SmartCollections.php`: A new controller to manage user-defined smart collection rules and display their contents.
        *   `Users.php`: A new controller to handle public user profiles, displaying shared collections and aggregated stats.
        *   `Gamification.php`: A new controller to display achievements, leaderboard, and milestone notifications.
        *   `Api.php`: Provides API endpoints for bot interactions and AJAX calls from the frontend.
            *   `/api/upload`: Receives file metadata from the Telegram bot. It will use the `file_unique_id` to check for and prevent duplicate file content metadata entries. If processing fails, it will log the failed webhook to the `failed_webhooks` table. It will also set the initial `process_status` and `webhook_reliability_status` for the file. Crucially, it will perform a `copyMessages` operation to a pre-configured Telegram storage channel and store the resulting `storage_channel_id` and `storage_message_id` in the database. It will also extract and store the `media_group_id` if the file is part of an album. This action will also trigger XP/achievement checks. After processing, it will check for matching notification rules with `trigger_type = 'file_tag_match'`, apply throttling via `Notification_Throttle_model`, and send notifications using `Notification_Template_model`.
            *   **Other Notification Triggers:** The system will also have mechanisms (e.g., in `Folder_Comment_model`, `User_Achievement_model`, or dedicated system event handlers) to check for and trigger notifications for other `trigger_type`s (e.g., 'new_comment', 'achievement_unlocked', 'system_announcement'), applying throttling and using templates as appropriate.
            *   `/api/timeline_data`: A new endpoint to provide aggregated file upload history data for the timeline view.
            *   `/api/tag_suggestions`: A new endpoint to provide tag suggestions for autocomplete based on user history.
            *   `/api/file_preview_data`: A new endpoint to fetch specific file metadata for the quick preview modal.
            *   `/api/generate_deep_link`: A new endpoint to generate Telegram deep links for shared folders.
            *   `/api/tag_consolidation_suggestions`: A new endpoint to provide suggestions for duplicate tags.
            *   `/api/merge_tags`: A new endpoint to perform tag consolidation (merging duplicate tags).
            *   `/api/add_folder_comment`: A new endpoint to add comments to shared folders.
            *   `/api/get_folder_comments`: A new endpoint to retrieve comments for a shared folder.
    *   **Models:**
        *   `User_model.php`: Handles database logic for the `users` table.
        *   `Folder_model.php`: A new model to handle database logic for the `folders` table (e.g., `create_folder`, `get_user_folders`, `update_folder`, `soft_delete_folder`, `manage_folder_tags`, `generate_unique_code`, `get_folder_by_code`, `update_folder_size`, `get_folder_hierarchy`, `move_folder`, `toggle_favorite_status`, `get_folder_stats`).
        *   `Tag_model.php`: A new model to manage the `tags` table (e.g., `get_or_create_tag`, `get_all_tags`, `get_user_tag_history`, `detect_duplicate_tags`, `merge_tags`).
        *   `Folder_Tag_model.php`: A new model to manage the `folder_tags` junction table (e.g., `add_tag_to_folder`, `remove_tag_from_folder`, `get_tags_for_folder`).
        *   `Folder_Review_model.php`: A new model to manage the `folder_reviews` table (e.g., `submit_review`, `get_reviews_for_folder`, `update_review`, `soft_delete_review`).
        *   `Smart_Collection_Rule_model.php`: A new model to manage the `smart_collection_rules` table (e.g., `create_rule`, `get_user_rules`, `evaluate_rule`).
        *   `Audit_Log_model.php`: A new model to manage the `audit_logs` table (e.g., `log_action`, `get_admin_actions`).
        *   `Failed_Webhook_model.php`: A new model to manage the `failed_webhooks` table (e.g., `log_failed_webhook`, `get_failed_webhooks`, `update_webhook_status`).
        *   `Access_Log_model.php`: A new model to manage the `access_logs` table (e.g., `log_access`, `get_trending_items`).
        *   `Folder_Like_model.php`: A new model to manage the `folder_likes` table (e.g., `add_like`, `remove_like`, `get_likes_count`).
        *   `Public_Collection_model.php`: A new model to manage the `public_collections` table (e.g., `create_collection`, `add_folder_to_collection`).
        *   `Public_Collection_Folder_model.php`: A new model to manage the `public_collection_folders` junction table.
        *   `Folder_Comment_model.php`: A new model to manage the `folder_comments` table (e.g., `add_comment`, `get_comments_for_folder`).
        *   `Achievement_model.php`: A new model to manage the `achievements` table (e.g., `get_achievement_by_criteria`).
        *   `User_Achievement_model.php`: A new model to manage the `user_achievements` table (e.g., `award_achievement`, `get_user_achievements`).
        *   `XP_Transaction_model.php`: A new model to manage the `xp_transactions` table (e.g., `add_xp`, `get_user_xp_history`).
        *   `Notification_Throttle_model.php`: A new model to manage the `notification_throttles` table (e.g., `check_and_update_throttle`).
        *   `Notification_Template_model.php`: A new model to manage the `notification_templates` table (e.g., `get_template`, `render_template`).
        *   `File_model.php`: Contains database logic for the `files` table. It will perform `JOIN` operations with the `users`, `folders`, and `folder_tags` tables to retrieve comprehensive file metadata, including tags inherited from its parent folder. It will also trigger `Folder_model->update_folder_size()` when files are added, removed, or moved between folders. It will include methods to update `process_status` and `webhook_reliability_status`. All database interactions will primarily use CodeIgniter's Query Builder. Will trigger XP/achievement checks on relevant actions.
    *   **Views:**
        *   `file_list.php`: An HTML file styled with Bootstrap 5 that displays the main file table, now with folder navigation.
        *   `gallery_view.php`: A view to display images in a grid format, also with folder navigation.
        *   `file_detail_view.php`: A dedicated page to show all details for a single file.
        *   `timeline_view.php`: A new view for displaying the file upload history timeline.
        *   `quick_preview_modal.php`: A partial view for the quick preview modal.
        *   `folder_stats_widget.php`: A partial view for the folder statistics sidebar widget.
        *   `templates/`: A directory for header, footer, and other layout partials.
*   **Frontend (Bootstrap 5):**
    *   The frontend is not a separate SPA but is rendered by CodeIgniter's view engine.
    *   It will consist of a single page displaying the file list in a responsive table.
    *   JavaScript (potentially with jQuery, as it's common with CI3) will be used for any dynamic interactions if needed, but the primary goal is a server-rendered page.

## System Robustness and Performance

To ensure the application is reliable and efficient, the following concepts will be implemented:

*   **Scheduled Tasks (Cron Jobs):**
    *   A CodeIgniter controller will be created specifically to be run from the command line (CLI).
    *   This controller will contain methods for `cleanup_soft_deletes()` and `generate_reports()`.
    *   A server cron job will be configured to periodically call these controller methods.
*   **Error Handling & Retry Queue:**
    *   The webhook will have robust error handling. For transient errors (like a temporary database outage), the failed request's payload will be pushed into a simple queue (e.g., a dedicated database table).
    *   A separate cron job will process this queue, attempting to re-process the failed requests.
*   **Caching:**
    *   **Database Caching:** CodeIgniter's database caching driver will be enabled to cache results of frequent, non-critical queries (e.g., user list, statistics).
    *   **Page Caching:** For static or public-facing pages, CodeIgniter's web page caching will be used to serve pre-rendered HTML files for maximum speed.

## Personalization

*   **Internationalization (i18n):** CodeIgniter's built-in Language Class will be used. Language files will be created in `application/language/` for each supported language (e.g., `english`, `indonesian`). The system will load the appropriate language file based on the user's `language_code` preference stored in the database. For managing translations, a dedicated translation management tool (e.g., POEditor, Lokalise) will be considered to streamline the translation process and collaboration.


