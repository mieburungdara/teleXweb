<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                <?php echo $title; ?>
            </h1>
            <p class="fw-medium mb-0 text-muted">
                <?php echo $bot ? 'Update the details of your bot.' : 'Register a new Telegram bot.'; ?>
            </p>
        </div>
        <div class="mt-4 mt-md-0">
            <a href="<?php echo site_url('bots'); ?>" class="btn btn-alt-secondary">
                <i class="fa fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title"><?php echo $bot ? 'Edit Bot' : 'Bot Details'; ?></h3>
        </div>
        <div class="block-content">
            <!-- Flash Messages for validation errors -->
            <?php if ($this->session->flashdata('errors')): ?>
                 <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <div class="flex-shrink-0">
                        <i class="fa fa-fw fa-exclamation-triangle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="mb-0"><strong>Please correct the following errors:</strong><br><?php echo $this->session->flashdata('errors'); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?php echo site_url('bots/save'); ?>" method="POST">
                <input type="hidden" name="id" value="<?php echo $bot ? $bot['id'] : ''; ?>">
                
                <div class="row push">
                    <div class="col-lg-8 offset-lg-2">
                        <div class="mb-4">
                            <label class="form-label" for="name">Bot Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="e.g., My Awesome Bot" value="<?php echo set_value('name', $bot ? $bot['name'] : ''); ?>" required>
                            <div class="form-text">A friendly name for you to identify the bot.</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="bot_id_telegram">Telegram Bot ID</label>
                            <input type="text" class="form-control" id="bot_id_telegram" name="bot_id_telegram" placeholder="e.g., 1234567890" value="<?php echo set_value('bot_id_telegram', $bot ? $bot['bot_id_telegram'] : ''); ?>" required>
                            <div class="form-text">The unique numeric ID provided by Telegram for your bot.</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="token">Bot Token</label>
                            <input type="text" class="form-control" id="token" name="token" placeholder="e.g., 1234567890:ABC-DEF1234ghIkl-zyx57W2v1u123" value="<?php echo set_value('token', $bot ? $bot['token'] : ''); ?>" required>
                             <div class="form-text">The secret token from BotFather. Keep this private.</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="storage_channel_id">Storage Channel ID</label>
                            <input type="text" class="form-control" id="storage_channel_id" name="storage_channel_id" placeholder="e.g., -1001234567890" value="<?php echo set_value('storage_channel_id', $bot ? $bot['storage_channel_id'] : ''); ?>" required>
                            <div class="form-text">The ID of the private channel where the bot will store files.</div>
                        </div>
                        <div class="mb-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> <?php echo $bot ? 'Update Bot' : 'Save Bot'; ?>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END Page Content -->
