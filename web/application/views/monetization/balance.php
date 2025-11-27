<div class="card">
    <div class="card-header">
        <h1>My Balance</h1>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('success_message')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('errors')): ?>
            <div class="alert alert-danger">
                <h4>Validation Errors:</h4>
                <?php echo $this->session->flashdata('errors'); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Current Balance</h5>
                        <p class="card-text fs-2">Rp <?php echo number_format($balance, 2, ',', '.'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <?php if (has_permission('manage_users')): // Admin only functionality for adding funds ?>
                    <div class="card mb-3">
                        <div class="card-header">Add Funds (Admin Only)</div>
                        <div class="card-body">
                            <?php echo form_open('monetization/add_funds'); ?>
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User ID</label>
                                    <input type="number" class="form-control" id="user_id" name="user_id" value="<?php echo set_value('user_id', $user['id']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="<?php echo set_value('amount'); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <input type="text" class="form-control" id="description" name="description" value="<?php echo set_value('description'); ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">Add Funds</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <h4 class="mt-4">Transaction History</h4>
        <?php if (empty($transactions)): ?>
            <p>No transactions found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
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
                            <tr class="<?php echo ($transaction['type'] == 'credit') ? 'table-success' : 'table-danger'; ?>">
                                <td><?php echo $transaction['id']; ?></td>
                                <td>Rp <?php echo number_format($transaction['amount'], 2, ',', '.'); ?></td>
                                <td><?php echo ucfirst($transaction['type']); ?></td>
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
