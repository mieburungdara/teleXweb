<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Bot Form</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; padding: 20px; background-color: #f4f7f6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"], .form-group input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Include padding in width */
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
        <h1><?php echo isset($bot) ? 'Edit Bot' : 'Add New Bot'; ?></h1>

        <?php if ($this->session->flashdata('errors')): ?>
            <div class="error-message">
                <h4>Validation Errors:</h4>
                <?php echo $this->session->flashdata('errors'); ?>
            </div>
        <?php endif; ?>

        <?php echo form_open('admin/save'); ?>
            <?php if (isset($bot)): ?>
                <input type="hidden" name="id" value="<?php echo $bot['id']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="bot_id_telegram">Telegram Bot ID (e.g., 1234567890)</label>
                <input type="number" id="bot_id_telegram" name="bot_id_telegram" value="<?php echo set_value('bot_id_telegram', isset($bot) ? $bot['bot_id_telegram'] : ''); ?>" required>
                <?php echo form_error('bot_id_telegram', '<div class="error-message">', '</div>'); ?>
            </div>

            <div class="form-group">
                <label for="name">Bot Name</label>
                <input type="text" id="name" name="name" value="<?php echo set_value('name', isset($bot) ? $bot['name'] : ''); ?>" required>
                <?php echo form_error('name', '<div class="error-message">', '</div>'); ?>
            </div>

            <div class="form-group">
                <label for="token">Bot Token</label>
                <input type="text" id="token" name="token" value="<?php echo set_value('token', isset($bot) ? $bot['token'] : ''); ?>" required>
                <?php echo form_error('token', '<div class="error-message">', '</div>'); ?>
            </div>

            <div class="form-group">
                <input type="submit" value="Save Bot">
            </div>
        </form>
        <a href="<?php echo site_url('admin'); ?>" class="back-link">Back to Bot List</a>
    </div>
</body>
</html>
