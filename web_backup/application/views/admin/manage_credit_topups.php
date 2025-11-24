<div class="container my-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Manage User Credits (Manual Top-up)</h4>
                </div>
                <div class="card-body">
                    <?php if ($this->session->flashdata('success_message')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $this->session->flashdata('success_message'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error_message')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $this->session->flashdata('error_message'); ?>
                        </div>
                    <?php endif; ?>

                    <p class="lead">Manually add credits to a user's account after a verified payment.</p>
                    <hr>

                    <form action="<?php echo site_url('admin/manage_credit_topups'); ?>" method="post">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Select User:</label>
                            <select class="form-control" id="user_id" name="user_id" required>
                                <option value="">-- Select User --</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?php echo $user->id; ?>">
                                        <?php echo html_escape($user->codename); ?> (ID: <?php echo $user->id; ?>)
                                        <?php echo $user->username ? '(@' . html_escape($user->username) . ')' : ''; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="credits_to_add" class="form-label">Credits to Add:</label>
                            <input type="number" class="form-control" id="credits_to_add" name="credits_to_add" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (e.g., Payment Reference):</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="e.g., Manual top-up via bank transfer - Ref: TXN12345"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Credits</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
