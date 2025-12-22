<!-- Hero -->
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
        <div>
            <h1 class="h3 mb-1">
                <?php echo htmlspecialchars($folder['folder_name']); ?>
            </h1>
            <p class="fw-medium mb-0 text-muted">
                Viewing folder details and reviews.
            </p>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-md-7 col-xl-8">
            <!-- Reviews Block -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Reviews</h3>
                </div>
                <div class="block-content">
                    <?php if (empty($reviews)): ?>
                        <p>No reviews yet. Be the first to leave a review!</p>
                    <?php else: ?>
                        <?php foreach ($reviews as $review): ?>
                            <div class="d-flex mb-4">
                                <div class="flex-shrink-0">
                                    <i class="fa fa-user-circle fa-2x text-muted"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="fw-semibold mb-0"><?php echo htmlspecialchars($review['username'] ?? 'Anonymous'); ?></p>
                                    <p class="fs-sm text-muted mb-1">
                                        <?php for($i = 0; $i < 5; $i++): ?>
                                            <i class="fa fa-star <?php echo $i < $review['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                        <?php endfor; ?>
                                        <span class="ms-1">(<?php echo $review['rating']; ?>/5)</span>
                                    </p>
                                    <p class="mb-1"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                                    <p class="fs-sm text-muted mb-0"><?php echo date('F j, Y', strtotime($review['created_at'])); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <!-- END Reviews Block -->
        </div>
        <div class="col-md-5 col-xl-4">
            <!-- Folder Details Block -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Details</h3>
                </div>
                <div class="block-content">
                    <p><?php echo htmlspecialchars($folder['description']); ?></p>
                    <p class="fs-sm text-muted">Tags: <?php echo htmlspecialchars($folder['tags']); ?></p>
                </div>
            </div>
            <!-- END Folder Details Block -->
            
            <!-- Stats Block -->
            <div class="block block-rounded">
                 <div class="block-header block-header-default">
                    <h3 class="block-title">Statistics</h3>
                </div>
                <div class="block-content">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Files
                            <span class="badge rounded-pill bg-primary"><?php echo $stats['file_count']; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Size
                            <span class="badge rounded-pill bg-info"><?php echo isset($folder['folder_size']) ? number_format($folder['folder_size'] / 1024, 2) . ' KB' : '0 KB'; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Last Activity
                            <span class="badge rounded-pill bg-secondary"><?php echo $stats['latest_activity'] ? date('Y-m-d', strtotime($stats['latest_activity'])) : 'N/A'; ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- END Stats Block -->

            <!-- Leave a Review Block -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Leave a Review</h3>
                </div>
                <div class="block-content">
                     <?php if ($this->session->flashdata('error_message')): ?>
                        <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
                    <?php endif; ?>
                     <?php echo form_open('folders/submit_review'); ?>
                        <input type="hidden" name="folder_id" value="<?php echo $folder['id']; ?>">
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <select class="form-select" id="rating" name="rating" required>
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="review_text" class="form-label">Your Review</label>
                            <textarea class="form-control" id="review_text" name="review_text" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                </div>
            </div>
            <!-- END Leave a Review Block -->
        </div>
    </div>
</div>
<!-- END Page Content -->
