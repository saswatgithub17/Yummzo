<?php
include '../includes/auth_check.php';
checkRole('restaurant');
include '../config/db.php';

$user_id = $_SESSION['user_id'];

// Get Restaurant Profile
$res_stmt = $conn->prepare("SELECT * FROM restaurants WHERE user_id = ?");
$res_stmt->execute([$user_id]);
$restaurant = $res_stmt->fetch();
$res_id = $restaurant['id'];

// Stats Logic
$total_orders = $conn->query("SELECT COUNT(*) FROM orders WHERE restaurant_id = $res_id")->fetchColumn();
$total_revenue = $conn->query("SELECT SUM(total_amount) FROM orders WHERE restaurant_id = $res_id AND status = 'delivered'")->fetchColumn() ?? 0;
$surplus_saved = $conn->query("SELECT COUNT(*) FROM menu_items WHERE restaurant_id = $res_id AND is_surplus = 1")->fetchColumn();

// Fetch Live Orders (Excluding Delivered/Cancelled)
$stmt = $conn->prepare("
    SELECT o.*, u.full_name as c_name, u.phone as c_phone, u.address as c_address 
    FROM orders o 
    JOIN users u ON o.customer_id = u.id 
    WHERE o.restaurant_id = ? AND o.status NOT IN ('delivered', 'cancelled')
    ORDER BY o.order_date ASC
");
$stmt->execute([$res_id]);
$live_orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Yummzo Partner | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; }
        .nav-partner { background: white; border-bottom: 2px solid #FF4757; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .hero-partner { background: #2F3542; color: white; padding: 50px 0; border-radius: 0 0 30px 30px; }
        .stat-card { background: white; border: none; border-radius: 20px; transition: 0.3s; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .stat-card:hover { transform: translateY(-5px); }
        .order-card { background: white; border: none; border-radius: 20px; border-left: 8px solid #FF4757; transition: 0.3s; }
        .status-pill { font-size: 0.75rem; padding: 5px 15px; border-radius: 50px; font-weight: 700; }
        .bg-pending { background: #fff4e6; color: #fd7e14; }
        .bg-preparing { background: #e7f5ff; color: #228be6; }
        .bg-out_for_delivery { background: #f3f0ff; color: #7950f2; }
        .btn-update { background: #FF4757; color: white; border-radius: 10px; border: none; font-weight: 600; transition: 0.3s; }
        .btn-update:hover { background: #2d3436; }
        .profile-link { color: #2d3436; text-decoration: none; font-weight: 600; transition: 0.3s; }
        .profile-link:hover { color: #FF4757; }
    </style>
</head>
<body>

<audio id="orderAudio"><source src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" type="audio/mpeg"></audio>

<script>
    <?php if(!empty($live_orders)): ?>
        document.getElementById('orderAudio').play();
    <?php endif; ?>
    setTimeout(function(){ location.reload(); }, 30000);
</script>

<nav class="navbar navbar-expand-lg navbar-light nav-partner sticky-top py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-danger fs-3" href="dashboard.php">YUMMZO <span class="text-dark">PARTNER</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#partnerNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="partnerNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link profile-link px-3 active" href="dashboard.php"><i class="fas fa-columns me-1"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link profile-link px-3" href="manage-menu.php"><i class="fas fa-utensils me-1"></i> My Menu</a></li>
                <li class="nav-item"><a class="nav-link profile-link px-3" href="profile.php"><i class="fas fa-store me-1"></i> Store Profile</a></li>
                <li class="nav-item">
                    <a class="nav-link profile-link px-3" href="../restaurant/analytics.php">
                        <i class="fas fa-chart-line me-1"></i> Analytics
                    </a>
                </li>
                <li class="nav-item ms-lg-3"><a href="../auth/logout.php" class="btn btn-danger rounded-pill px-4"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="hero-partner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold mb-1">Welcome back, <?php echo $restaurant['restaurant_name']; ?>!</h2>
                <p class="mb-0 opacity-75"><i class="fas fa-utensils me-1"></i> <?php echo $restaurant['cuisine_type']; ?> <span class="mx-2">|</span> <i class="fas fa-star text-warning me-1"></i> Health Rating: <strong><?php echo $restaurant['health_rating']; ?>/5.0</strong></p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="d-inline-block bg-success p-2 px-3 rounded-pill text-white small fw-bold"><i class="fas fa-circle me-1 animate-pulse"></i> KITCHEN ONLINE</div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-n4">
    <div class="row g-4 mb-5">
        <div class="col-md-4"><div class="card stat-card p-4 border-bottom border-success border-4"><div class="d-flex justify-content-between align-items-center"><div><h6 class="text-muted small fw-bold">LIFETIME REVENUE</h6><h2 class="fw-bold mb-0">₹<?php echo number_format($total_revenue, 2); ?></h2></div><div class="bg-light p-3 rounded-circle text-success"><i class="fas fa-wallet fa-xl"></i></div></div></div></div>
        <div class="col-md-4"><div class="card stat-card p-4 border-bottom border-danger border-4"><div class="d-flex justify-content-between align-items-center"><div><h6 class="text-muted small fw-bold">TOTAL ORDERS</h6><h2 class="fw-bold mb-0"><?php echo $total_orders; ?></h2></div><div class="bg-light p-3 rounded-circle text-danger"><i class="fas fa-shopping-bag fa-xl"></i></div></div></div></div>
        <div class="col-md-4"><div class="card stat-card p-4 border-bottom border-primary border-4"><div class="d-flex justify-content-between align-items-center"><div><h6 class="text-muted small fw-bold text-primary">SURPLUS ITEMS</h6><h2 class="fw-bold mb-0"><?php echo $surplus_saved; ?> Listed</h2></div><div class="bg-light p-3 rounded-circle text-primary"><i class="fas fa-leaf fa-xl"></i></div></div></div></div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Live Orders in Kitchen</h4>
        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="location.reload();"><i class="fas fa-sync-alt me-1"></i> Refresh</button>
    </div>
    
    <div class="row">
        <?php if(empty($live_orders)): ?>
            <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm">
                <img src="https://cdn-icons-png.flaticon.com/512/3296/3296340.png" width="100" class="mb-3 opacity-50">
                <h5 class="fw-bold">No active orders right now</h5>
                <p class="text-muted">Take a break! New orders will pop up here.</p>
            </div>
        <?php else: ?>
            <?php foreach($live_orders as $order): 
                $order_items_stmt = $conn->prepare("SELECT m.item_name, m.price FROM orders o JOIN menu_items m ON o.restaurant_id = m.restaurant_id WHERE o.id = ?");
                $order_items_stmt->execute([$order['id']]);
                $items = $order_items_stmt->fetchAll();
            ?>
                <div class="col-md-6 mb-4">
                    <div class="card order-card shadow-sm h-100 border-0">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0 text-dark">Order #<?php echo $order['id']; ?></h5>
                                <span class="status-pill bg-<?php echo $order['status']; ?>"><?php echo strtoupper(str_replace('_', ' ', $order['status'])); ?></span>
                            </div>
                            <div class="bg-light p-3 rounded-3 mb-3">
                                <p class="mb-1 fw-bold"><i class="fas fa-user me-2 text-danger"></i> <?php echo $order['c_name']; ?></p>
                                <p class="mb-1 small"><i class="fas fa-phone me-2 text-muted"></i> <?php echo $order['c_phone']; ?></p>
                                <p class="mb-0 small text-muted"><i class="fas fa-map-marker-alt me-2 text-muted"></i> <?php echo $order['c_address']; ?></p>
                            </div>

                            <div class="mt-3 mb-3">
                                <button class="btn btn-sm btn-outline-dark rounded-pill" data-bs-toggle="collapse" data-bs-target="#items<?php echo $order['id']; ?>">
                                    <i class="fas fa-list"></i> View Food Items
                                </button>
                                <div class="collapse mt-2" id="items<?php echo $order['id']; ?>">
                                    <div class="p-2 bg-light rounded shadow-sm small">
                                       <?php foreach($items as $f): ?>
                                            <div class="d-flex justify-content-between border-bottom py-1">
                                                <span><?php echo $f['item_name']; ?></span>
                                                <span class="text-muted">₹<?php echo $f['price']; ?></span>
                                            </div>
                                       <?php endforeach; ?>
                                       <p class="mb-0 mt-2 text-muted italic" style="font-size:0.75rem;">Check the printed KOT for specific modifications.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div><span class="text-muted small d-block">Bill Amount</span><h5 class="text-danger fw-bold mb-0">₹<?php echo $order['total_amount']; ?></h5></div>
                                <form action="update-order-status.php" method="POST" class="d-flex gap-2 align-items-center">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <select name="status" class="form-select form-select-sm rounded-pill shadow-none" style="width: auto;">
                                        <option value="preparing" <?php echo $order['status']=='preparing' ? 'selected' : ''; ?>>Preparing</option>
                                        <option value="out_for_delivery" <?php echo $order['status']=='out_for_delivery' ? 'selected' : ''; ?>>Out for Delivery</option>
                                        <option value="delivered">Delivered</option>
                                    </select>
                                    <button class="btn btn-update btn-sm px-4 rounded-pill">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>