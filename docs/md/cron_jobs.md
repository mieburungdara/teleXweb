# Cron Job Setup

This project includes a command-line interface (CLI) controller for running scheduled tasks, such as cleaning up soft-deleted records.

## How to Run a Task

You can run the tasks from your project's root directory using the following command structure:

```bash
php web/index.php cli/tasks/<method_name>/<param1>/<param2>...
```

### Examples:

1.  **Cleanup Soft-Deleted Records**

    This task permanently deletes files and folders that were soft-deleted more than 30 days ago.

    ```bash
    php web/index.php cli/tasks/cleanup_soft_deletes
    ```

    To specify a different time frame (e.g., 60 days):

    ```bash
    php web/index.php cli/tasks/cleanup_soft_deletes/60
    ```

2.  **Generate Weekly Report (Placeholder)**

    This is a placeholder task.

    ```bash
    php web/index.php cli/tasks/generate_weekly_report
    ```

## Setting Up a Cron Job

To automate these tasks, you can set up a cron job on your server. Open your crontab file for editing:

```bash
crontab -e
```

Then, add lines to schedule your tasks.

### Example Cron Job Schedule:

Run the cleanup task daily at 3:00 AM:

```
0 3 * * * /usr/bin/php /path/to/your/project/web/index.php cli/tasks/cleanup_soft_deletes > /dev/null 2>&1
```

*   Make sure to replace `/path/to/your/project/` with the absolute path to your project directory.
*   The `> /dev/null 2>&1` part suppresses the output of the command, which is recommended for cron jobs that you don't need to be emailed about.
