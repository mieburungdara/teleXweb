<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">My Balance</h1>
            <div class="text-end">
                <h2 class="fs-2 fw-bold mb-0">Rp <?php echo number_format($balance, 2, ',', '.'); ?></h2>
                <span class="text-muted">Current Balance</span>
            </div>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
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

    <div class="row">
        <div class="<?php echo has_permission('manage_users') ? 'col-md-8' : 'col-md-12'; ?>">
            <!-- Transaction History Block -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Transaction History</h3>
                </div>
                <div class="block-content">
                    <?php if (empty($transactions)): ?>
                        <p>No transactions found.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-vcenter">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Amount</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transactions as $transaction): ?>
                                        <tr>
                                            <td><?php echo $transaction['id']; ?></td>
                                            <td class="fw-semibold <?php echo ($transaction['type'] == 'credit') ? 'text-success' : 'text-danger'; ?>">
                                                <?php echo ($transaction['type'] == 'credit') ? '+' : '-'; ?> Rp <?php echo number_format($transaction['amount'], 2, ',', '.'); ?>
                                            </td>
                                            <td>
                                                <span class="badge <?php echo ($transaction['type'] == 'credit') ? 'bg-success' : 'bg-danger'; ?>"><?php echo ucfirst($transaction['type']); ?></span>
                                            </td>
                                            <td><?php echo htmlspecialchars($transaction['description'] ?? 'N/A'); ?></td>
                                            <td><?php echo date('Y-m-d H:i', strtotime($transaction['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- END Transaction History Block -->
        </div>

        <?php if (has_permission('manage_users')): // Admin only functionality for adding funds ?>
            <div class="col-md-4">
                <!-- Add Funds Block -->
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Add Funds (Admin)</h3>
                    </div>
                    <div class="block-content">
                        <?php echo form_open('monetization/add_funds'); ?>
                            <div class="mb-4">
                                <label for="user_id" class="form-label">User ID</label>
                                <input type="number" class="form-control" id="user_id" name="user_id" value="<?php echo set_value('user_id', $user['id']); ?>" required>
                            </div>
                            <div class="mb-4">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="<?php echo set_value('amount'); ?>" required>
                            </div>
                            <div class="mb-4">
                                <label for="description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="description" name="description" value="<?php echo set_value('description'); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Add Funds</button>
                        </form>
                    </div>
                </div>
                <!-- END Add Funds Block -->
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- END Page Content -->
