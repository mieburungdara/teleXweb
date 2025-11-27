<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h2><?php echo htmlspecialchars($folder['folder_name']); ?></h2>
            </div>
            <div class="card-body">
                <p><?php echo htmlspecialchars($folder['description']); ?></p>
                <p class="text-muted">Tags: <?php echo htmlspecialchars($folder['tags']); ?></p>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h3>Reviews</h3>
            </div>
            <div class="card-body">
                <?php if (empty($reviews)): ?>
                    <p>No reviews yet. Be the first to leave a review!</p>
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
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3>Leave a Review</h3>
            </div>
            <div class="card-body">
                <?php if ($this->session->flashdata('error_message')): ?>
                    <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
                <?php endif; ?>

                <?php echo form_open('folders/submit_review'); ?>
                    <input type="hidden" name="folder_id" value="<?php echo $folder['id']; ?>">
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating (1-5)</label>
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
        <a href="<?php echo site_url('folders'); ?>" class="btn btn-secondary mt-3">Back to Folders</a>
    </div>
</div>
