<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                <?php echo isset($bot) ? 'Edit Bot' : 'Add New Bot'; ?>
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Manage a Telegram bot's details.
            </p>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title"><?php echo isset($bot) ? 'Edit Bot' : 'Bot Details'; ?></h3>
        </div>
        <div class="block-content">
            <?php if ($this->session->flashdata('errors')): ?>
                <div class="alert alert-danger">
                    <p class="mb-0"><strong>Validation Errors:</strong></p>
                    <ul class="mb-0">
                        <?php echo $this->session->flashdata('errors'); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php echo form_open('admin/save', ['class' => 'js-validation']); ?>
                <?php if (isset($bot)): ?>
                    <input type="hidden" name="id" value="<?php echo $bot['id']; ?>">
                <?php endif; ?>

                <div class="row push">
                    <div class="col-lg-8 col-xl-5">
                        <div class="mb-4">
                            <label class="form-label" for="bot_id_telegram">Telegram Bot ID <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="bot_id_telegram" name="bot_id_telegram" value="<?php echo set_value('bot_id_telegram', isset($bot) ? $bot['bot_id_telegram'] : ''); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="name">Bot Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name', isset($bot) ? $bot['name'] : ''); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="token">Bot Token <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="token" name="token" value="<?php echo set_value('token', isset($bot) ? $bot['token'] : ''); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="storage_channel_id">Storage Channel ID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="storage_channel_id" name="storage_channel_id" value="<?php echo set_value('storage_channel_id', isset($bot) ? $bot['storage_channel_id'] : ''); ?>" required>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="row items-push">
                    <div class="col-lg-8 col-xl-5">
                         <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check-circle me-1"></i> Save Bot
                        </button>
                        <a href="<?php echo site_url('admin'); ?>" class="btn btn-alt-secondary">
                            <i class="fa fa-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <!-- END Submit -->
            </form>
        </div>
    </div>
</div>
<!-- END Page Content -->