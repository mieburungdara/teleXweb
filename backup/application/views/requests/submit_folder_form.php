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
        <p class="lead">You are submitting a folder for the request: <strong>"<?php echo htmlspecialchars($request->title); ?>"</strong></p>

        <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <?php if (!empty($my_folders)): ?>
            <form action="<?php echo site_url('requests/submit/' . $request->id); ?>" method="post">
                <div class="mb-3">
                    <label for="folder_id" class="form-label">Select a folder to submit</label>
                    <select class="form-select" id="folder_id" name="folder_id" required>
                        <option value="">-- Choose a Folder --</option>
                        <?php foreach ($my_folders as $folder): ?>
                            <option value="<?php echo $folder->id; ?>"><?php echo htmlspecialchars($folder->folder_name); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">
                        The content of this folder will be submitted for review. If accepted, the requester will gain access to it.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Submit Folder</button>
                <a href="<?php echo site_url('requests/view/' . $request->id); ?>" class="btn btn-secondary">Cancel</a>
            </form>
        <?php else: ?>
            <div class="alert alert-warning">
                You do not have any folders to submit. <a href="<?php echo site_url('folders/create'); // Assuming this is the link to create a folder ?>">Create a folder first</a>.
            </div>
        <?php endif; ?>

    </div>
</body>
</html>
