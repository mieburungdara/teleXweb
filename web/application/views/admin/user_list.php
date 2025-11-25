<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User List</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; padding: 20px; background-color: #f4f7f6; color: #333; }
        .container { max-width: 900px; margin: 20px auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1, h2 { color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #e9ecef; font-weight: bold; }
        .actions a { margin-right: 10px; text-decoration: none; color: #007bff; }
        .actions a.delete { color: #dc3545; }
        .actions a:hover { text-decoration: underline; }
        .message { padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .success-message { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error-message { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .warning-message { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .back-link { display: inline-block; margin-top: 20px; text-decoration: none; color: #007bff; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Management</h1>

        <?php if ($this->session->flashdata('success_message')): ?>
            <div class="message success-message"><?php echo $this->session->flashdata('success_message'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="message error-message"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php endif; ?>

        <?php if (empty($users)): ?>
            <p>No users registered yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Telegram ID</th>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['telegram_id']; ?></td>
                            <td><?php echo $user['username'] ?? 'N/A'; ?></td>
                            <td><?php echo $user['first_name']; ?></td>
                            <td><?php echo $user['role_name']; ?> (ID: <?php echo $user['role_id']; ?>)</td>
                            <td class="actions">
                                <a href="<?php echo site_url('admin/edit_user_role/' . $user['id']); ?>">Edit Role</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <a href="<?php echo site_url('admin'); ?>" class="back-link">Back to Admin Dashboard</a>
    </div>
</body>
</html>
