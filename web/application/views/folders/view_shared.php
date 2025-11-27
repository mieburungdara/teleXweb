<?php $this->load->view('templates/header', ['title' => 'Shared Folder']); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2><?php echo htmlspecialchars($folder['folder_name']); ?></h2>
            </div>
            <div class="card-body">
                <p><?php echo htmlspecialchars($folder['description']); ?></p>
                <p class="text-muted">Tags: <?php echo htmlspecialchars($folder['tags']); ?></p>
                <div class="mt-3">
                    <strong>Likes: <?php echo $like_count; ?></strong>
                    <?php if ($this->session->userdata('logged_in')): ?>
                        <a href="<?php echo site_url('folders/toggle_like/' . $folder['id']); ?>" class="btn btn-sm <?php echo $user_has_liked ? 'btn-primary' : 'btn-outline-primary'; ?>">
                            <?php echo $user_has_liked ? 'Unlike' : 'Like'; ?> &#10084;
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h3>Reviews</h3>
            </div>
            <div class="card-body">
                <?php if (empty($reviews)): ?>
                    <p>No reviews yet.</p>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="mb-3 border-bottom pb-2">
                            <strong><?php echo htmlspecialchars($review['username'] ?? 'Anonymous'); ?></strong>
                            <small class="text-muted">(Rating: <?php echo $review['rating']; ?>/5)</small>
                            <p class="mt-1"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                            <small class="text-muted"><?php echo date('F j, Y', strtotime($review['created_at'])); ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
