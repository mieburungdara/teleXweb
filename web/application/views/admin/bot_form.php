<div class="card">
    <div class="card-header">
        <h1><?php echo isset($bot) ? 'Edit Bot' : 'Add New Bot'; ?></h1>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('errors')): ?>
            <div class="alert alert-danger">
                <h4>Validation Errors:</h4>
                <?php echo $this->session->flashdata('errors'); ?>
            </div>
        <?php endif; ?>

        <?php echo form_open('admin/save'); ?>
            <?php if (isset($bot)): ?>
                <input type="hidden" name="id" value="<?php echo $bot['id']; ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="bot_id_telegram" class="form-label">Telegram Bot ID (e.g., 1234567890)</label>
                <input type="number" class="form-control" id="bot_id_telegram" name="bot_id_telegram" value="<?php echo set_value('bot_id_telegram', isset($bot) ? $bot['bot_id_telegram'] : ''); ?>" required>
                <?php echo form_error('bot_id_telegram', '<div class="text-danger">', '</div>'); ?>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Bot Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name', isset($bot) ? $bot['name'] : ''); ?>" required>
                <?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
            </div>

            <div class="mb-3">
                <label for="token" class="form-label">Bot Token</label>
                <input type="text" class="form-control" id="token" name="token" value="<?php echo set_value('token', isset($bot) ? $bot['token'] : ''); ?>" required>
                <?php echo form_error('token', '<div class="text-danger">', '</div>'); ?>
            </div>

            <div class="mb-3">
                <label for="storage_channel_id" class="form-label">Storage Channel ID</label>
                <input type="text" class="form-control" id="storage_channel_id" name="storage_channel_id" value="<?php echo set_value('storage_channel_id', isset($bot) ? $bot['storage_channel_id'] : ''); ?>" required>
                <?php echo form_error('storage_channel_id', '<div class="text-danger">', '</div>'); ?>
            </div>

            <button type="submit" class="btn btn-primary">Save Bot</button>
            <a href="<?php echo site_url('admin'); ?>" class="btn btn-secondary">Back to Bot List</a>
        </form>
    </div>
</div>