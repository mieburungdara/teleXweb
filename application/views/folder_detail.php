<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folder Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Folder: <?php echo htmlspecialchars($folder->folder_name); ?></h1>
        <a href="<?php echo site_url('folders'); ?>" class="btn btn-secondary mb-3">Back to Folders</a>

        <div class="card mb-4">
            <div class="card-header">
                Folder Information
            </div>
            <div class="card-body">
                <p><strong>Description:</strong> <?php echo htmlspecialchars($folder->description); ?></p>
                <p><strong>Price:</strong> $<?php echo htmlspecialchars(number_format($folder->price, 2)); ?></p>
                <p><strong>For Sale:</strong> <?php echo $folder->is_for_sale ? 'Yes' : 'No'; ?></p>
                <p><strong>Owner ID:</strong> <?php echo htmlspecialchars($folder->user_id); ?></p>
                <!-- Add more folder details here -->

                <?php if ($is_owner): ?>
                    <h5 class="mt-4">Manage Sale Status</h5>
                    <form action="<?php echo site_url('folders/set_for_sale/' . $folder->id); ?>" method="post">
                        <div class="mb-3">
                            <label for="price" class="form-label">Set Price</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($folder->price); ?>" required>
                            <?php echo form_error('price', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        <div class="mb-3">
                            <label for="is_for_sale" class="form-label">List for Sale</label>
                            <select class="form-select" id="is_for_sale" name="is_for_sale" required>
                                <option value="0" <?php echo ($folder->is_for_sale == 0) ? 'selected' : ''; ?>>No</option>
                                <option value="1" <?php echo ($folder->is_for_sale == 1) ? 'selected' : ''; ?>>Yes</option>
                            </select>
                            <?php echo form_error('is_for_sale', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Sale Status</button>
                    </form>
                <?php elseif (!$has_purchased && $folder->is_for_sale && $folder->price > 0): ?>
                    <h5 class="mt-4">Purchase Folder</h5>
                    <p>This folder is available for purchase for $<?php echo htmlspecialchars(number_format($folder->price, 2)); ?>.</p>
                    <a href="<?php echo site_url('folders/buy_folder/' . $folder->id); ?>" class="btn btn-success" onclick="return confirm('Are you sure you want to purchase this folder for $<?php echo htmlspecialchars(number_format($folder->price, 2)); ?>?');">Buy Now</a>
                <?php elseif ($has_purchased): ?>
                    <div class="alert alert-success mt-4" role="alert">
                        You have purchased this folder.
                        <?php if ($this->session->flashdata('success_message')): ?>
                            <p><?php echo $this->session->flashdata('success_message'); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
