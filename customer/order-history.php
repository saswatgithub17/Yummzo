<?php
include '../includes/auth_check.php';
checkRole('customer');
include '../config/db.php';

$user_id = $_SESSION['user_id'];

// Fetch all orders with restaurant details
$stmt = $conn->prepare("
    SELECT o.*, r.restaurant_name, r.cuisine_type 
    FROM orders o 
    JOIN restaurants r ON o.restaurant_id = r.id 
    WHERE o.customer_id = ? 
    ORDER BY o.order_date DESC
");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

// Fetch User details for the address display in modal
$user_stmt = $conn->prepare("SELECT address, phone FROM users WHERE id = ?");
$user_stmt->execute([$user_id]);
$user_info = $user_stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | Yummzo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Poppins', sans-serif; }
        .navbar { background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .order-card { border: none; border-radius: 20px; transition: 0.3s; background: white; }
        .order-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
        .status-badge { font-size: 0.75rem; padding: 6px 15px; border-radius: 50px; font-weight: 700; }
        
        /* Status Colors */
        .status-pending { background: #fff4e6; color: #fd7e14; }
        .status-preparing { background: #e7f5ff; color: #228be6; }
        .status-delivered { background: #ebfbee; color: #40c057; }
        .status-cancelled { background: #fff5f5; color: #fa5252; }
        
        .modal-content { border-radius: 25px; border: none; }
        .modal-header { background: #FF4757; color: white; border-radius: 25px 25px 0 0; }
        .btn-close { filter: brightness(0) invert(1); }
        .item-list-row { border-bottom: 1px dashed #eee; padding: 8px 0; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold text-danger" href="index.php">YUMMZO</a>
        <a href="index.php" class="btn btn-outline-dark btn-sm rounded-pill"><i class="fas fa-arrow-left"></i> Back to Home</a>
    </div>
</nav>

<div class="container pb-5">
    <h2 class="fw-bold mb-4">Order History</h2>

    <?php if(empty($orders)): ?>
        <div class="text-center py-5">
            <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" width="120" class="mb-3 opacity-50">
            <h4 class="text-muted">No orders found!</h4>
            <a href="index.php" class="btn btn-danger mt-3 rounded-pill px-4">Start Ordering</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($orders as $order): ?>
                <div class="col-md-6 mb-4">
                    <div class="card order-card shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-bold mb-0"><?php echo $order['restaurant_name']; ?></h5>
                                    <small class="text-muted"><?php echo $order['cuisine_type']; ?></small>
                                </div>
                                <span class="status-badge status-<?php echo $order['status']; ?>">
                                    <?php echo strtoupper($order['status']); ?>
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="small">
                                    <p class="mb-0 text-muted"><i class="far fa-calendar-alt me-1"></i> <?php echo date('D, d M Y', strtotime($order['order_date'])); ?></p>
                                    <p class="mb-0 text-muted"><i class="far fa-clock me-1"></i> <?php echo date('h:i A', strtotime($order['order_date'])); ?></p>
                                </div>
                                <div class="text-end">
                                    <span class="d-block text-muted small">Total Paid</span>
                                    <span class="fw-bold text-danger fs-5">₹<?php echo $order['total_amount']; ?></span>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-light rounded-pill fw-bold border" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo $order['id']; ?>">
                                    View Details & Receipt
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="detailModal<?php echo $order['id']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content shadow-lg">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">Order Summary #<?php echo $order['id']; ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="text-center mb-4">
                                    <h4 class="fw-bold mb-0 text-danger">₹<?php echo $order['total_amount']; ?></h4>
                                    <span class="badge bg-light text-dark border">Paid via <?php echo (rand(0,1) ? 'UPI' : 'Cash'); ?></span>
                                </div>

                                <h6 class="fw-bold"><i class="fas fa-utensils me-2"></i> From: <?php echo $order['restaurant_name']; ?></h6>
                                <p class="small text-muted mb-4 border-bottom pb-2">Restaurant Address: <?php echo $order['cuisine_type']; ?> Market Center</p>
                                
                                <h6 class="fw-bold"><i class="fas fa-map-marker-alt me-2 text-success"></i> Delivered to:</h6>
                                <p class="small text-muted mb-4"><?php echo $user_info['address']; ?><br>Phone: <?php echo $user_info['phone']; ?></p>

                                <div class="bg-light p-3 rounded-4">
                                    <h6 class="fw-bold mb-2">Order Timeline</h6>
                                    <div class="small">
                                        <p class="mb-1 text-success"><i class="fas fa-check-circle"></i> Order Placed: <?php echo date('h:i A', strtotime($order['order_date'])); ?></p>
                                        <p class="mb-0 <?php echo ($order['status'] == 'delivered') ? 'text-success' : 'text-muted'; ?>">
                                            <i class="<?php echo ($order['status'] == 'delivered') ? 'fas fa-check-circle' : 'far fa-circle'; ?>"></i> 
                                            Delivered: <?php echo ($order['status'] == 'delivered') ? date('h:i A', strtotime($order['order_date'] . ' + 30 minutes')) : 'Pending'; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-dark w-100 rounded-pill" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>