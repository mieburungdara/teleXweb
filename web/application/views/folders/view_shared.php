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
                <h3>Comments</h3>
            </div>
            <div class="card-body">
                <?php if ($this->session->flashdata('error_message')): ?>
                    <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message'); ?></div>
                <?php endif; ?>

                <?php if (empty($comments)): ?>
                    <p>No comments yet. Be the first to leave a comment!</p>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="mb-3 border-bottom pb-2">
                            <strong><?php echo htmlspecialchars($comment['username'] ?? 'Anonymous'); ?></strong>
                            <p class="mt-1"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                            <small class="text-muted"><?php echo date('Y-m-d H:i', strtotime($comment['created_at'])); ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if ($this->session->userdata('logged_in')): ?>
                    <div class="mt-4">
                        <h4>Post a Comment</h4>
                        <?php echo form_open('folders/submit_comment'); ?>
                            <input type="hidden" name="folder_id" value="<?php echo $collection['id'] ?? $folder['id']; ?>">
                            <div class="mb-3">
                                <textarea name="comment_text" class="form-control" rows="3" placeholder="Write your comment here..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Comment</button>
                        </form>
                    </div>
                <?php else: ?>
                    <p class="mt-4">Please log in to leave a comment.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
