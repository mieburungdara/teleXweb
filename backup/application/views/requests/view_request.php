<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        
        <?php if($this->session->flashdata('success_message')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success_message'); ?></div>
        <?php endif; ?>
        <?php if($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php endif; ?>

        <!-- Request Details -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Request Details</h5>
                <a href="<?php echo site_url('requests'); ?>" class="btn btn-sm btn-outline-secondary">Back to List</a>
            </div>
            <div class="card-body">
                <h2 class="card-title"><?php echo htmlspecialchars($request->title); ?></h2>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($request->description)); ?></p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Reward:</strong> <?php echo htmlspecialchars(number_format($request->reward_amount, 2)); ?> Credits</li>
                    <li class="list-group-item"><strong>Priority:</strong> <?php echo ucfirst(htmlspecialchars($request->priority)); ?></li>
                    <li class="list-group-item"><strong>Deadline:</strong> <?php echo $request->deadline_at ? date('F j, Y, g:i a', strtotime($request->deadline_at)) : 'Flexible'; ?></li>
                    <li class="list-group-item"><strong>Type:</strong> <?php echo $request->type === 'public_bounty' ? 'Public Bounty' : 'Direct Request'; ?></li>
                </ul>
            </div>
            <div class="card-footer">
                <?php
                // Logic to show "Submit" button
                // Should not be the requester
                // If direct request, must be the target creator
                $can_submit = !$is_requester && ($request->type === 'public_bounty' || $is_target_creator);
                if ($can_submit): ?>
                    <a href="<?php echo site_url('requests/submit/' . $request->id); ?>" class="btn btn-primary">Submit Your Folder</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Submissions Section -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Submissions</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($submissions)): ?>
                    <ul class="list-group">
                        <?php foreach ($submissions as $submission): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Submission by: <?php echo htmlspecialchars($submission->creator_username); ?></h6>
                                    <p class="mb-1">Folder: "<?php echo htmlspecialchars($submission->folder_name); ?>"</p>
                                    <small>Submitted on: <?php echo date('F j, Y', strtotime($submission->submitted_at)); ?></small>
                                </div>
                                <div class="text-end">
                                    <?php if ($is_requester && $submission->status === 'pending_review'): ?>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo site_url('requests/review_submission/' . $submission->id . '/accept'); ?>" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to accept this submission? The reward amount will be deducted from your balance.');">Accept</a>
                                            <a href="<?php echo site_url('requests/review_submission/' . $submission->id . '/reject'); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject this submission?');">Reject</a>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge 
                                            <?php if($submission->status === 'accepted') echo 'bg-success'; ?>
                                            <?php if($submission->status === 'rejected') echo 'bg-danger'; ?>
                                            <?php if($submission->status === 'pending_review') echo 'bg-secondary'; ?>
                                        ">
                                            <?php echo ucfirst(str_replace('_', ' ', $submission->status)); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No one has submitted a folder for this request yet.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</body>
</html>
