<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1><?php echo htmlspecialchars($title); ?></h1>

        <!-- Section 1: Requests I Posted -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Requests I Posted</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($my_posted_requests)): ?>
                    <ul class="list-group">
                        <?php foreach ($my_posted_requests as $request): ?>
                            <li class="list-group-item">
                                <a href="<?php echo site_url('requests/view/' . $request->id); ?>"><?php echo htmlspecialchars($request->title); ?></a>
                                <span class="badge bg-info float-end"><?php echo ucfirst($request->status); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>You have not posted any requests. <a href="<?php echo site_url('requests/create'); ?>">Create one now!</a></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section 2: Direct Requests Sent TO Me -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Direct Requests For Me</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($direct_requests_to_me)): ?>
                    <ul class="list-group">
                        <?php foreach ($direct_requests_to_me as $request): ?>
                            <li class="list-group-item">
                                <a href="<?php echo site_url('requests/view/' . $request->id); ?>"><?php echo htmlspecialchars($request->title); ?></a>
                                <span class="badge bg-primary float-end">From: <?php echo htmlspecialchars($request->requester_username); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>You have no direct requests.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Section 3: My Submissions to Others' Requests -->
        <div class="card">
            <div class="card-header">
                <h3>My Submissions</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($my_submissions)): ?>
                    <ul class="list-group">
                        <?php foreach ($my_submissions as $submission): ?>
                            <li class="list-group-item">
                                <a href="<?php echo site_url('requests/view/' . $submission->request_id); ?>">Submission for Request ID #<?php echo $submission->request_id; ?></a>
                                <span class="badge 
                                    <?php if($submission->status === 'accepted') echo 'bg-success'; ?>
                                    <?php if($submission->status === 'rejected') echo 'bg-danger'; ?>
                                    <?php if($submission->status === 'pending_review') echo 'bg-secondary'; ?>
                                    float-end
                                ">
                                    <?php echo ucfirst(str_replace('_', ' ', $submission->status)); ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>You have not submitted content for any requests.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
