<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                Bot Management
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Manage all registered Telegram bots.
            </p>
        </div>
        <div class="mt-4 mt-md-0">
            <?php if (has_permission('manage_bots')): ?>
                <a href="<?php echo site_url('admin/form'); ?>" class="btn btn-primary">
                    <i class="fa fa-plus me-1"></i> Add New Bot
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <!-- Bot List Block -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">All Bots</h3>
        </div>
        <div class="block-content">
            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success_message')): ?>
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <div class="flex-shrink-0">
                        <i class="fa fa-fw fa-check"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="mb-0"><?php echo $this->session->flashdata('success_message'); ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error_message')): ?>
                 <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <div class="flex-shrink-0">
                        <i class="fa fa-fw fa-times"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="mb-0"><?php echo $this->session->flashdata('error_message'); ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('errors')): ?>
                 <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <div class="flex-shrink-0">
                        <i class="fa fa-fw fa-exclamation-triangle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="mb-0"><strong>Validation Errors:</strong> <?php echo $this->session->flashdata('errors'); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (empty($bots)): ?>
                <p>No bots registered yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 80px;">ID</th>
                                <th>Name</th>
                                <th>Telegram Bot ID</th>
                                <th>Token (Partial)</th>
                                <?php if (has_permission('manage_bots')): ?>
                                    <th class="text-center" style="width: 100px;">Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bots as $bot): ?>
                                <tr>
                                    <td class="text-center fs-sm"><?php echo $bot['id']; ?></td>
                                    <td class="fw-semibold fs-sm"><?php echo htmlspecialchars($bot['name']); ?></td>
                                    <td class="fs-sm"><?php echo $bot['bot_id_telegram']; ?></td>
                                    <td class="fs-sm"><code><?php echo substr($bot['token'], 0, 10); ?>...</code></td>
                                    <?php if (has_permission('manage_bots')): ?>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="<?php echo site_url('admin/form/' . $bot['id']); ?>" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="fa fa-pencil-alt"></i>
                                                </a>
                                                <a href="<?php echo site_url('admin/delete/' . $bot['id']); ?>" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this bot?');">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- END Bot List Block -->
</div>
<!-- END Page Content -->