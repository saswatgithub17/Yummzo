<?php
include '../includes/auth_check.php';
checkRole('restaurant');
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$res_stmt = $conn->prepare("SELECT id FROM restaurants WHERE user_id = ?");
$res_stmt->execute([$user_id]);
$res_id = $res_stmt->fetchColumn();

if(isset($_GET['delete'])) {
    $del_id = $_GET['delete'];
    $conn->prepare("DELETE FROM menu_items WHERE id = ? AND restaurant_id = ?")->execute([$del_id, $res_id]);
    header("Location: manage-menu.php?msg=Item Deleted");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_item'])) {
    $name = $_POST['item_name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $cat = $_POST['category'];
    $surplus = isset($_POST['is_surplus']) ? 1 : 0;
    $stmt = $conn->prepare("INSERT INTO menu_items (restaurant_id, item_name, description, price, category, is_surplus) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$res_id, $name, $desc, $price, $cat, $surplus]);
    header("Location: manage-menu.php?msg=Item Added");
    exit();
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$items_query = "SELECT * FROM menu_items WHERE restaurant_id = $res_id";
if($search) $items_query .= " AND item_name LIKE '%$search%'";
$items_query .= " ORDER BY id DESC";
$items = $conn->query($items_query)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu Management | Yummzo Partner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .menu-card { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background: white; }
        .table thead { background: #2F3542; color: white; }
        .surplus-tag { background: #e6ffed; color: #28a745; border: 1px solid #28a745; padding: 2px 8px; border-radius: 5px; font-size: 0.75rem; font-weight: bold; }
        .btn-yummzo { background: #FF4757; color: white; border-radius: 10px; border: none; font-weight: 600; }
        .btn-yummzo:hover { background: #2d3436; color: white; }
        .btn-xs { padding: 1px 10px; font-size: 0.75rem; border-radius: 50px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">YUMMZO <span class="text-danger">PARTNER</span></a>
        <a href="dashboard.php" class="btn btn-outline-light btn-sm"><i class="fa fa-arrow-left"></i> Dashboard</a>
    </div>
</nav>

<div class="container pb-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-6"><h3 class="fw-bold">Menu Inventory</h3><p class="text-muted">Total Items: <?php echo count($items); ?></p></div>
        <div class="col-md-6 text-md-end"><button class="btn btn-yummzo px-4 py-2" data-bs-toggle="modal" data-bs-target="#addItemModal"><i class="fa fa-plus-circle me-1"></i> Add New Dish</button></div>
    </div>

    <div class="card menu-card p-3 mb-4">
        <form class="row g-2">
            <div class="col-md-10"><input type="text" name="search" class="form-control" placeholder="Search dish by name..." value="<?php echo $search; ?>"></div>
            <div class="col-md-2"><button type="submit" class="btn btn-dark w-100">Search</button></div>
        </form>
    </div>

    <div class="card menu-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Item Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Inventory</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($items as $item): ?>
                    <tr>
                        <td class="ps-4"><span class="fw-bold"><?php echo $item['item_name']; ?></span><br><small class="text-muted"><?php echo $item['description']; ?></small></td>
                        <td><span class="badge bg-light text-dark border"><?php echo $item['category']; ?></span></td>
                        <td class="fw-bold text-danger">₹<?php echo $item['price']; ?></td>
                        <td>
                            <?php if($item['is_surplus']): ?> <span class="surplus-tag">SURPLUS DEAL</span>
                            <?php else: ?> <span class="text-muted small">Standard</span> <?php endif; ?>
                        </td>
                        <td>
                            <?php if($item['is_available']): ?>
                                <a href="toggle-availability.php?id=<?php echo $item['id']; ?>&status=0" class="btn btn-xs btn-success py-0">In Stock</a>
                            <?php else: ?>
                                <a href="toggle-availability.php?id=<?php echo $item['id']; ?>&status=1" class="btn btn-xs btn-secondary py-0">Sold Out</a>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="manage-menu.php?delete=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this item?')"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <form action="manage-menu.php" method="POST">
                <div class="modal-header bg-danger text-white border-0 rounded-top-4">
                    <h5 class="modal-title fw-bold">Add New Dish</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3"><label class="form-label fw-bold">Item Name</label><input type="text" name="item_name" class="form-control" required placeholder="e.g. Butter Chicken"></div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category</label>
                        <select name="category" class="form-select">
                            <option value="Main Course">Main Course</option>
                            <option value="Starters">Starters</option>
                            <option value="Desserts">Desserts</option>
                            <option value="Beverages">Beverages</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label fw-bold">Price (₹)</label><input type="number" name="price" class="form-control" required placeholder="250"></div>
                    <div class="mb-3"><label class="form-label fw-bold">Description</label><textarea name="description" class="form-control" rows="2" placeholder="Briefly describe the ingredients..."></textarea></div>
                    <div class="form-check form-switch p-3 bg-light rounded-3">
                        <input class="form-check-input" type="checkbox" name="is_surplus" id="surplusCheck">
                        <label class="form-check-label fw-bold" for="surplusCheck">Mark as "Surplus Food" (Zero Waste Initiative)</label>
                        <div class="form-text">This will highlight the item as a discounted deal for customers.</div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_item" class="btn btn-yummzo rounded-pill px-4">Add to Menu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>