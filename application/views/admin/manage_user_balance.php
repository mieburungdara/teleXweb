<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User Balance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-5">
        <h1>Manage User Balance</h1>
        <a href="<?php echo site_url('admin'); ?>" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <div class="card mb-4">
            <div class="card-header">
                Update Balance
            </div>
            <div class="card-body">
                <form action="<?php echo site_url('admin/manage_user_balance'); ?>" method="post">
                    <div class="mb-3">
                        <label for="user_id_select2" class="form-label">Select User</label>
                        <select class="form-select" id="user_id_select2" name="user_id" required>
                            <?php if (isset($user)): ?>
                                <option value="<?php echo $user->id; ?>" selected>
                                    <?php echo htmlspecialchars($user->codename . ' (ID: ' . $user->id . ')' . ($user->username ? ' (@' . $user->username . ')' : '')); ?>
                                </option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <?php if (isset($user)): ?>
                        <div class="mb-3">
                            <p><strong>Current Balance:</strong> $<?php echo htmlspecialchars(number_format($user->balance, 2)); ?></p>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="transaction_type" class="form-label">Transaction Type</label>
                            <select class="form-select" id="transaction_type" name="transaction_type" required>
                                <option value="top_up">Top-up</option>
                                <option value="deduction">Deduction</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                        <button type="submit" class="btn btn-primary">Update Balance</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <?php if (isset($user)): ?>
            <div class="card">
                <div class="card-header">
                    Transaction History for <?php echo htmlspecialchars($user->codename); ?>
                </div>
                <div class="card-body">
                    <?php if (!empty($transactions)): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Admin ID</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $tx): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($tx->id); ?></td>
                                        <td><?php echo htmlspecialchars(ucfirst($tx->transaction_type)); ?></td>
                                        <td><?php echo htmlspecialchars(number_format($tx->amount, 2)); ?></td>
                                        <td><?php echo htmlspecialchars($tx->description); ?></td>
                                        <td><?php echo htmlspecialchars($tx->admin_id); ?></td>
                                        <td><?php echo htmlspecialchars($tx->created_at); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <nav aria-label="Page navigation">
                            <?php echo $pagination_links; ?>
                        </nav>
                    <?php else: ?>
                        <p>No transactions found for this user.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#user_id_select2').select2({
                theme: 'bootstrap-5',
                placeholder: 'Search for a user...',
                minimumInputLength: 2,
                ajax: {
                    url: '<?php echo site_url('admin/search_users_ajax'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term // search term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            $('#user_id_select2').on('select2:select', function (e) {
                var data = e.params.data;
                window.location.href = '<?php echo site_url('admin/manage_user_balance/'); ?>' + data.id;
            });
        });
    </script>
</body>
</html>
