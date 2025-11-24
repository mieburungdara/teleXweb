<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Marketplace</h1>
        <p>Browse and purchase folders from other users.</p>
        <a href="<?php echo site_url('users'); ?>" class="btn btn-secondary mb-3">Back to Profile</a>

        <div class="card mb-4">
            <div class="card-header">
                Search and Filter
            </div>
            <div class="card-body">
                <form action="<?php echo site_url('marketplace/index'); ?>" method="get">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" placeholder="Search by name or description" value="<?php echo htmlspecialchars($filters['search']); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="min_price" class="form-label">Min Price</label>
                            <input type="number" step="0.01" class="form-control" id="min_price" name="min_price" placeholder="e.g., 5.00" value="<?php echo htmlspecialchars($filters['min_price']); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="max_price" class="form-label">Max Price</label>
                            <input type="number" step="0.01" class="form-control" id="max_price" name="max_price" placeholder="e.g., 50.00" value="<?php echo htmlspecialchars($filters['max_price']); ?>">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="<?php echo site_url('marketplace/index'); ?>" class="btn btn-outline-secondary">Clear Filters</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if (!empty($folders)): ?>
            <div class="row">
                <?php foreach ($folders as $folder): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($folder->folder_name); ?></h5>
                                <p class="card-text">Seller: <?php echo htmlspecialchars($folder->seller_name); ?></p>
                                <p class="card-text text-success fw-bold">Price: $<?php echo htmlspecialchars(number_format($folder->price, 2)); ?></p>
                                <p class="card-text"><?php echo htmlspecialchars(substr($folder->description, 0, 100)) . (strlen($folder->description) > 100 ? '...' : ''); ?></p>
                                <a href="<?php echo site_url('folders/detail/' . $folder->id); ?>" class="btn btn-primary">View Details & Buy</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <nav aria-label="Page navigation">
                <?php echo $pagination_links; ?>
            </nav>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                No folders found for sale with the selected filters.
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
