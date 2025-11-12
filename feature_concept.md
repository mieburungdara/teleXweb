# Feature Concept

## Core Features

1.  **File Metadata Reception:** Users send files to a dedicated Telegram bot, and the system captures and stores the associated metadata (not the file itself).
2.  **Metadata Storage:** The system saves file metadata (like `telegram_file_id`, `thumbnail_file_id`, original name, type, size, tags, etc.) into a structured MySQL database.
3.  **Soft Deletion:** Metadata records can be "soft-deleted" by populating a `deleted_at` timestamp, allowing for potential recovery.

## Bot Features

1.  **Interactive Tagging:** The bot can prompt the user to add descriptive tags after a file is sent.
2.  **`/start` Command:** Welcomes the user and introduces the bot.
3.  **`/help` Command:** Explains all available features and commands.
4.  **`/recent [N]` Command:** Fetches and resends the user's last N submitted files.
5.  **`/search [keyword]` Command:** Searches for files by name or tag and returns the results.
6.  **`/fav` Command:** Allows a user to mark a file as a favorite by providing its ID.

## Web Interface Features

1.  **Metadata Listing:** A web page lists all captured file metadata in a clear, tabular format.
2.  **File Type Icons:** The interface will use distinct icons for different file types.
3.  **Image Previews:** The interface will display Telegram-generated thumbnails for image files.
4.  **Bulk Actions:** Users can select multiple entries to perform mass actions.
5.  **Gallery View for Images:** A separate view mode that displays all image files in a grid format.
6.  **Comprehensive Detail Page:** A dedicated page showing all metadata for a single file.
7.  **Inline Metadata Editing:** Users can double-click on `tags` or `original_file_name` in the main table to edit them directly, with changes saved via AJAX.
8.  **Advanced Search Form:** A collapsible form allowing users to filter files by multiple criteria (type, date range, user, size, tags).
9.  **Favorites System:** Users can mark/unmark files as favorites. A dedicated "Favorites" tab or filter will be available for quick access.

## Admin Features (Web Interface)

1.  **User Management Dashboard:** An admin-only page to list all registered users, view their roles, block/unblock them, and see basic usage stats per user.
2.  **Role Management:** Admins can assign roles (`admin`, `editor`, `viewer`) to users, controlling their access level within the web UI.
3.  **Advanced Analytics Dashboard:** A dashboard with interactive charts and graphs visualizing system-wide data, such as file uploads over time, distribution of file types, and most active users.

## User-Specific Features

1.  **Custom Notifications (Web UI):** Users can create and manage rules to receive a Telegram notification when a file with a specific tag is uploaded.


