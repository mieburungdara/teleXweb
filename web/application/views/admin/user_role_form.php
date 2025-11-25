<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit User Role</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; padding: 20px; background-color: #f4f7f6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-group input[type="submit"]:hover {
            background-color: #218838;
        }
        .error-message { color: #dc3545; margin-top: 5px; font-size: 0.9em; }
        .back-link { display: block; margin-top: 20px; text-decoration: none; color: #007bff; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit User Role: <?php echo htmlspecialchars($user['username'] ?? $user['first_name']); ?></h1>

        <?php if ($this->session->flashdata('errors')): ?>
            <div class="error-message">
                <h4>Validation Errors:</h4>
                <?php echo $this->session->flashdata('errors'); ?>
            </div>
        <?php endif; ?>

        <?php echo form_open('admin/update_user_role'); ?>
            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

            <div class="form-group">
                <label for="current_role">Current Role:</label>
                <p id="current_role"><?php echo htmlspecialchars($user['role_name']); ?> (ID: <?php echo htmlspecialchars($user['role_id']); ?>)</p>
            </div>

            <div class="form-group">
                <label for="role_id">Select New Role:</label>
                <select id="role_id" name="role_id" required>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?php echo $role['id']; ?>" <?php echo set_select('role_id', $role['id'], $user['role_id'] == $role['id']); ?>>
                            <?php echo htmlspecialchars($role['name']); ?> (ID: <?php echo htmlspecialchars($role['id']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php echo form_error('role_id', '<div class="error-message">', '</div>'); ?>
            </div>

            <div class="form-group">
                <input type="submit" value="Update Role">
            </div>
        </form>
        <a href="<?php echo site_url('admin/users'); ?>" class="back-link">Back to User List</a>
    </div>
</body>
</html>
