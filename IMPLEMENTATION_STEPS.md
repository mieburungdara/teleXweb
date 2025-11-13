# Implementation Steps

This document outlines the planned sequence of development for the teleXweb project.

### Phase 1: Project & Database Setup

1.  **Install CodeIgniter 3:** Download and set up the basic framework.
2.  **Install Dependencies:** Use Composer to install third-party libraries (`phpdotenv`, Telegram Bot SDK).
3.  **Environment Configuration:** Create the `.env` file from a `.env.example` and configure `application/config/config.php` and `database.php` to use environment variables.
4.  **Database Creation:** Create the MySQL database.
5.  **Run Schema:** Execute the `schema.sql` file to create the `users` and `files` tables.

### Phase 2: Backend API & Bot Logic

1.  **Create Models:** Develop `User_model.php`, `Folder_model.php`, `Tag_model.php`, and `Folder_Tag_model.php` with methods to interact with the database using the Query Builder. Update `File_model.php` to use the new tagging system (inheriting tags from folders).
2.  **Create API Controller:** Develop `application/controllers/api/Upload.php` to handle incoming metadata from the bot, validate data, and use the models to save it, including using `file_unique_id` for duplication checks. Ensure `Folder_model->update_folder_size()` is called when a file is added to a folder. It will also set the initial `process_status` and `webhook_reliability_status` for the file. Crucially, this controller will perform the `copyMessages` operation to a pre-configured Telegram storage channel and store the resulting `storage_channel_id` and `storage_message_id` in the database. It will also extract and store the `media_group_id` if the file is part of an album.
3.  **Implement Webhook Script:** Create the `webhook.php` script that receives updates from Telegram, extracts metadata, and forwards it to the CodeIgniter API endpoint.
4.  **Implement Bot Commands Logic:** Add logic to the webhook (or a dedicated bot controller) to handle commands like `/start`, `/help`, `/recent`, and `/search`, now utilizing folder-based tagging for search.

### Phase 3: Frontend Web Interface

1.  **Create Base Templates:** Set up header, footer, and main layout templates in `application/views/templates/`.
2.  **Create Files Controller:** Develop `application/controllers/Files.php` to fetch file metadata using `File_model` (with JOINs to get user, folder, and folder tag data).
3.  **Develop Files View:** Create `application/views/file_list.php` to display the data in a table, now with folder navigation, inherited tag display, and proper grouping for media albums.
4.  **Implement Folder Management:**
    *   Create `Folder_model.php` and `Folders.php` controller.
    *   Develop `application/views/folder_management_view.php`.
    *   Implement functionalities for creating, renaming, soft-deleting folders.
    *   Implement managing tags for folders using the new tagging system.
    *   Implement moving files and folders, ensuring `Folder_model->update_folder_size()` is called for affected folders.
    *   Implement folder rating and review functionalities (submit, edit, delete reviews).
    *   Implement folder sharing: generate unique codes, create shareable links, and manage access.
    *   Implement Telegram Deep Links for shared folders, including bot handling of the `start` parameter and web interface routing.
    *   Implement nested folder hierarchy: allow creating subfolders, moving folders, and displaying breadcrumbs for navigation.
    *   Implement folder quick actions: allow users to pin/favorite folders for quick access.
    *   Implement "Folder Stars/Hearts" for public folders, including `Folder_Like_model` and UI for liking/unliking.
5.  **Implement UI/UX Features:**
    *   Add logic to display file-type icons and thumbnails.
    *   Develop the Gallery and File Detail pages, ensuring proper grouping and display for media albums.
    *   Implement the Advanced Search form and the corresponding filtering logic in the `Files.php` controller, now supporting tag-based filtering via folder tags.
    *   Implement the Favorites system (toggling favorite status, filtering by favorites).
    *   Develop the backend API endpoint (`/api/update_file`) for AJAX calls.
    *   Implement the frontend JavaScript for Inline Editing (for `original_file_name`) and Favorites. Tag management is now exclusively at the folder level.
    *   Implement the Quick Preview Modal functionality, including fetching data via `/api/file_preview_data`.
    *   Implement Breadcrumb Navigation for folder hierarchy.
    *   Implement the Folder Stats Widget, displaying folder size, file count, and latest activity.
    *   Implement "Trending This Week" feature, including logging access via `Access_Log_model` and displaying aggregated trending data.
    *   Add JavaScript for bulk actions (checkboxes, action buttons).
    *   Implement soft-delete functionality.
6.  **Implement Smart Collections:**
    *   Create `Smart_Collection_Rule_model.php` and `SmartCollections.php` controller.
    *   Develop views for managing smart collection rules.
    *   Implement logic to dynamically evaluate smart collection rules and display results alongside regular folders.
7.  **Implement File Timeline:**
    *   Develop the `timeline_view.php` and corresponding controller method to display file upload history.
    *   Create API endpoint (`/api/timeline_data`) to provide data for the timeline.
8.  **Implement Tag Autocomplete:**
    *   Create API endpoint (`/api/tag_suggestions`) to provide tag suggestions.
    *   Implement frontend JavaScript for autocomplete functionality when managing folder tags.

### Phase 4: Advanced Features & Deployment

1.  **Implement Cron Jobs:** Create a CLI controller (`application/controllers/cli/Tasks.php`) for cleanup and reporting tasks. Configure server cron jobs.
2.  **Implement Caching:** Enable and configure database and page caching where appropriate.
3.  **Implement Web Security:** Set up authentication for the web interface.
4.  **Testing:** Write unit tests for models and perform manual testing of the end-to-end flow.
5.  **Deployment:** Plan and execute deployment to the production server.

### Phase 5: Admin & Advanced User Features

1.  **Implement Roles & Permissions:** Update models and controllers to enforce the access control defined by user roles and manage user status (active, blocked, deleted).
2.  **Build Admin Dashboard:** Create the controllers and views for the user management and analytics dashboards. This will likely involve a charting library (e.g., Chart.js).
3.  **Build Notifications System:**
    *   Create `Notification_Throttle_model.php` to manage the `notification_throttles` table.
    *   Implement methods to check current throttle status for a user/notification type.
    *   Implement methods to update `last_sent_at` and `send_count`, and reset `send_count` based on `reset_at`.
    *   Create `Notification_Template_model.php` to manage the `notification_templates` table.
    *   Implement methods to retrieve templates and perform variable substitution.
    *   Modify the `Api.php` controller's `/api/upload` endpoint to:
        *   Fetch relevant `notification_rules` for the user.
        *   For each rule, check `Notification_Throttle_model` to see if a notification can be sent.
        *   If allowed, retrieve the `template_content` from `Notification_Template_model` using `template_id`.
        *   Perform variable substitution in the template using file/folder/tag data.
        *   Send the customized notification via the Telegram bot.
        *   Update the `notification_throttles` table after sending.
    *   Create views and methods in `Notifications.php` controller for users to:
        *   Create, view, edit, and delete notification rules.
        *   Select from available notification templates.
        *   Configure throttling settings (e.g., "send at most 1 notification per hour for this rule").
4.  **Update Core Logic:** Modify the file upload processing logic to check for and trigger custom notifications.
5.  **Implement Audit Trail:**
    *   Create `Audit_Log_model.php`.
    *   Integrate logging into relevant controllers and models for admin actions and critical system events.
6.  **Build System Health Dashboard:**
    *   Develop views and controller methods for displaying system health metrics (error rates, webhook success, DB performance).
7.  **Implement Webhook Retry Dashboard:**
    *   Create `Failed_Webhook_model.php`.
    *   Update `Api.php` to log failed webhooks to the `failed_webhooks` table.
    *   Develop admin views and controller methods to display failed webhooks and allow manual retries.
    *   Integrate the retry mechanism with the existing "Error Handling & Retry Queue" cron job.
8.  **Implement Tag Consolidation & Duplicate Detection:**
    *   Update `Tag_model.php` with methods for detecting and merging tags.
    *   Create new API endpoints (`/api/tag_consolidation_suggestions`, `/api/merge_tags`).
    *   Develop admin views and controller methods for displaying suggestions and performing merges.
9.  **Implement Public Collections:**
    *   Create `Public_Collection_model.php` and `Public_Collection_Folder_model.php`.
    *   Develop admin views and controller methods for curating public collections.
    *   Develop frontend views for users to browse public collections.
10. **Implement User Profiles:**
    *   Create `Users.php` controller.
    *   Develop `application/views/user_profile_view.php` to display shared collections and aggregated stats.
11. **Implement Comments on Shared Folders:**
    *   Create `Folder_Comment_model.php`.
    *   Update `Folders.php` controller to handle comment submission and retrieval.
    *   Create new API endpoints (`/api/add_folder_comment`, `/api/get_folder_comments`).
    *   Develop frontend UI for displaying and adding threaded comments on shared folder views.
12. **Implement Gamification System:**
    *   Create `Achievement_model.php`, `User_Achievement_model.php`, and `XP_Transaction_model.php`.
    *   Update `User_model.php` to manage `user_level` and `achievement_points`.
    *   Integrate XP and achievement checks into relevant actions across `Files.php`, `Folders.php`, `Api.php`, `Folder_Like_model.php`, `Public_Collection_model.php`, `Public_Collection_Folder_model.php`, `Folder_Comment_model.php`, and `File_model.php`.
    *   Develop `Gamification.php` controller and views for displaying achievements, leaderboard, and milestone notifications.

### Phase 6: Polish & User Experience

1.  **Implement i18n:** Create language files and integrate CodeIgniter's Language Class throughout the web UI and bot responses. Set up a translation management tool if decided.
2.  **Implement Theming:** Develop the CSS and JavaScript for the light/dark theme switcher.
3.  **Build Onboarding Flow:** Create the tutorial for new users (either via bot messages or a web UI tour).
4.  **Develop User Documentation:** Create a user manual or FAQ section within the web interface.


