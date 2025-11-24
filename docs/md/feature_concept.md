# Feature Concept

## Core Features

1.  **File Metadata Reception:** Users send files to a dedicated Telegram bot. The bot will then use `copyMessages` to forward the file to a designated Telegram storage channel. The system captures and stores the associated metadata (not the file itself), including the `storage_channel_id`, `storage_message_id`, and `media_group_id` (if part of an album). The `file_unique_id` from Telegram will be used to prevent storing duplicate file content metadata.
2.  **File Distribution via Storage Channels:** The system will leverage the `storage_channel_id` and `storage_message_id` to allow other bots or authorized users to access and distribute the stored files directly from the Telegram storage channel.
2.  **Metadata Storage:** The system saves file metadata (like `telegram_file_id`, `thumbnail_file_id`, original name, type, size, tags, etc.) into a structured MySQL database.
3.  **Soft Deletion:** Metadata records can be "soft-deleted" by populating a `deleted_at` timestamp, allowing for potential recovery.
4.  **File Processing Status Tracking:** Track the lifecycle of file metadata (e.g., 'pending', 'processed', 'indexed', 'failed') with an indicator for webhook delivery reliability.

## Bot Features

1.  **Interactive Tagging:** The bot can prompt the user to add descriptive tags to folders. Tags will be selected from existing unique tags or new unique tags can be created.

## Bot Features

1.  **Interactive Tagging:** The bot can prompt the user to add descriptive tags to folders. Tags will be selected from existing unique tags or new unique tags can be created.
2.  **`/start` Command:** Welcomes the user and introduces the bot.
3.  **`/help` Command:** Explains all available features and commands.
4.  **`/recent [N]` Command:** Fetches and resends the user's last N submitted files.
5.  **`/search [keyword]` Command:** Searches for files by name or by tags associated with their parent folders, and returns the results.
6.  **`/fav` Command:** Allows a user to mark a file as a favorite by providing its ID.

## Web Interface Features

1.  **Metadata Listing:** A web page lists all captured file metadata in a clear, tabular format, with special handling for displaying files that are part of a media album.
2.  **File Type Icons:** The interface will use distinct icons for different file types.
3.  **Image Previews:** The interface will display Telegram-generated thumbnails for image files.
4.  **Bulk Actions:** Users can select multiple entries to perform mass actions.
5.  **Gallery View for Images:** A separate view mode that displays all image files in a grid format, with proper grouping and display for media albums.
6.  **Comprehensive Detail Page:** A dedicated page showing all metadata for a single file.
7.  **Inline Metadata Editing:** Users can double-click on `original_file_name` directly in the main table, with changes saved via AJAX. Tag management is handled at the folder level.
8.  **Advanced Search Form:** A collapsible form allowing users to filter files by multiple criteria (type, date range, user, size, tags associated with parent folders).
9.  **Favorites System:** Users can mark/unmark files as favorites. A dedicated "Favorites" tab or filter will be available for quick access.
10. **File Timeline View:** A visual timeline displaying the user's file upload history, ordered chronologically, allowing for easy browsing of past uploads.
11. **Tag Autocomplete:** When editing tags for folders, the system will suggest existing tags based on the user's historical usage and available tags in the system.
12. **Quick Preview Modal:** Allows users to view a file's metadata details (and thumbnail for images) in a modal window without leaving the current page.
13. **Breadcrumb Navigation:** Displays the current folder's hierarchy, allowing users to easily navigate up to parent folders.
14. **Folder Stats Widget:** A sidebar widget that displays aggregated statistics for the currently viewed folder, such as total size, number of files, and latest activity.
15. **Trending This Week:** A dedicated section or widget displaying the most accessed files and folders over the past week, to inspire discovery.

## Folder Management (Web Interface)

1.  **Create/Rename/Delete Folders:** Users can create new folders, rename existing ones, and soft-delete folders.
2.  **Move Files/Folders:** Users can move individual files, multiple files (via bulk actions), or entire folders (and their contents) to a chosen destination within the folder hierarchy.
3.  **Browse by Folder:** The web interface will allow users to browse their files by navigating through their custom folders, displaying the aggregated `folder_size`.
4.  **"Unfiled" Section:** A default section for files that have not been assigned to any folder.

## Smart Collections (Web Interface)

1.  **Create/Manage Smart Collections:** Users can define rules (e.g., "Last 7 days", "Files with Tag 'Urgent'") to create virtual, auto-generated collections.
2.  **Display Smart Collections:** These collections will appear alongside regular folders in the navigation, providing dynamic views of files.
3.  **Rule Types:** Support for various rule types, such as date ranges, specific tags, file types, or sender.
5.  **Manage Folder Tags:** Users can add, edit, or remove tags for their folders, utilizing the shared tagging system.
6.  **Pin/Favorite Folders:** Users can mark folders as favorites or 'pinned' for quick access in the navigation or a dedicated section.

## Nested Folders (Web Interface)

1.  **Create Subfolders:** Users can create folders within other folders, building a hierarchical structure.
2.  **Navigate Folder Hierarchy:** The web interface will provide intuitive navigation (e.g., breadcrumbs) to move through the folder tree.
3.  **Move Folders:** Users can move entire folders (and their contents) within the hierarchy.
4.  **Display Hierarchy:** Folder listings will clearly indicate parent-child relationships.

## Folder Sharing (Web Interface)

1.  **Generate Shareable Link:** Users can generate a unique, short link for any folder using its `code`.
2.  **Access Control:** Shared folders can be set to 'public' (anyone with the link can view) or 'private' (only specific users can view, requiring a separate access management system).
3.  **View Shared Folder:** Other users can access the shared folder via the link, viewing its contents (files and subfolders, if implemented).
4.  **Telegram Deep Links:** Generate Telegram deep links (e.g., `https://t.me/your_bot?start=folder_code`) that, when clicked, can auto-open the shared folder directly in the web interface.
5.  **Folder Stars/Hearts:** Users can "like" or "favorite" public folders shared by other users, without modifying the original folder. This allows for social interaction and discovery.
6.  **Comments on Shared Folders:** Users can post threaded comments on shared folders, facilitating discussion and collaboration among authorized users.

## User Profiles (Web Interface)

1.  **Public User Profile Page:** A dedicated public profile page for each user, showcasing their shared collections, average folder rating, and other public contributions.

## Folder Rating & Review (Web Interface)

1.  **Submit Rating & Review:** Users can submit a 1-5 star rating and a descriptive text review for any folder.
2.  **Edit/Delete Review:** Users can edit or soft-delete their own submitted reviews.
3.  **Display Ratings & Reviews:** Folder listings and detail pages will display average ratings and a list of reviews.
4.  **Validation:** Input for ratings and reviews will be validated (e.g., rating between 1-5, text length).

## Admin Features (Web Interface)

1.  **User Management Dashboard:** An admin-only page to list all registered users, view their roles, manage their status (active, blocked, deleted), and see basic usage stats per user. The `codename` will be used to protect user privacy in public-facing displays or reports.
2.  **Role Management:** Admins can assign roles (`admin`, `editor`, `viewer`) to users, controlling their access level within the web UI.
3.  **Advanced Analytics Dashboard:** A dashboard with interactive charts and graphs visualizing system-wide data, such as file uploads over time, distribution of file types, and most active users.
4.  **System Health Dashboard:** A dedicated dashboard for administrators to monitor key system metrics like error rates, webhook success/failure rates, and database performance.
5.  **Audit Trail:** A comprehensive log of all significant admin actions (e.g., user role changes, folder deletions) and critical system events, including who changed what, when, and from where.
6.  **Webhook Retry Dashboard:** An admin interface to view failed webhook attempts, inspect their payloads and error messages, and manually trigger retries for 'pending' webhooks.
7.  **Tag Consolidation & Duplicate Detection:** Admin interface to detect similar/duplicate tags and suggest consolidation, allowing for merging multiple tags into a single canonical tag.
8.  **Public Collections:** Admin users can curate and manage public collections of interesting folders, making them discoverable by all users.

## Folder Monetization / Selling Folders

1.  **Folder Pricing:** Users (sellers) can set a price for their folders.
2.  **Folder Listing:** Users can mark their folders as "for sale".
3.  **Folder Purchase:** Other users (buyers) can purchase these folders using their balance.
4.  **Content Access:** Upon successful purchase, the buyer gains access to the folder's content (metadata).
5.  **Revenue Transfer:** The purchase amount is deducted from the buyer's balance and added to the seller's balance.
6.  **Purchase History:** A `folder_purchases` table will record all purchase transactions.

## Personalization & Onboarding

1.  **Multi-Language Support (i18n):** Both the web interface and the Telegram bot will support multiple languages. Users can set their preference, and the system will serve content in the chosen language.
2.  **UI Theming:** The web interface will offer at least a 'light' and a 'dark' theme. Users can select their preferred theme, which will be saved to their profile.
3.  **Interactive Onboarding:** New users will be greeted with a simple tutorial, either via a series of bot messages or a guided tour on the web UI, to help them understand the core features quickly.

## Gamification System

1.  **Badges/Achievements:** Users can earn various badges (e.g., "File Organizer", "Tag Master", "Community Helper") for completing specific tasks or reaching milestones.
2.  **XP System:** Users gain experience points (XP) for performing actions like organizing files, sharing folders, tagging content, and contributing to the community.
3.  **User Levels:** XP contributes to user levels, providing a sense of progression.
4.  **Leaderboard:** A public (opt-in) leaderboard showcasing top users based on their achievements and contributions.
5.  **Milestone Celebrations:** Notifications and visual cues to celebrate user milestones (e.g., "You've reached 1000 files!", "First shared folder!").



