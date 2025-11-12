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

## Web Interface Features

1.  **Metadata Listing:** A web page lists all captured file metadata in a clear, tabular format.
2.  **File Type Icons:** The interface will use distinct icons (e.g., for documents, images, videos) to provide a quick visual reference for the file type.
3.  **Image Previews:** For image files, the interface will display the Telegram-generated thumbnail, providing a visual preview without needing the full file.
4.  **Bulk Actions:** Users can select multiple metadata entries via checkboxes to perform mass actions, such as soft-deleting or adding/removing tags.
5.  **Detailed View:** An option to view all metadata associated with a file in a detailed view.
6.  **Gallery View for Images:** A separate view mode that displays all image files in a grid format, using their thumbnails for a visual-first experience.
7.  **Comprehensive Detail Page:** Clicking on a file entry will navigate to a dedicated page showing a larger thumbnail/icon and a clean presentation of all its metadata.

