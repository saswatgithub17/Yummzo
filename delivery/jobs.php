<?php
include '../includes/auth_check.php';
checkRole('delivery');
include '../config/db.php';

$rider_id = $_SESSION['user_id'];

// 1. Fetch Available Jobs (Out for Delivery but no rider assigned yet)
$stmt = $conn->prepare("
    SELECT o.*, r.restaurant_name, r.cuisine_type, u.full_name as c_name, u.address as c_address 
    FROM orders o 
    JOIN restaurants r ON o.restaurant_id = r.id 
    JOIN users u ON o.customer_id = u.id 
    WHERE o.status = 'out_for_delivery' AND o.delivery_id IS NULL
    ORDER BY o.order_date DESC
");
$stmt->execute();
$available_jobs = $stmt->fetchAll();

// 2. Fetch My Active Task (The job the rider is currently doing)
$active_stmt = $conn->prepare("
    SELECT o.*, r.restaurant_name, u.full_name as c_name, u.address as c_address, u.phone as c_phone 
    FROM orders o 
    JOIN restaurants r ON o.restaurant_id = r.id 
    JOIN users u ON o.customer_id = u.id 
    WHERE o.delivery_id = ? AND o.status != 'delivered'
");
$active_stmt->execute([$rider_id]);
$my_job = $active_stmt->fetch();

// 3. Stats for Rider
$total_done = $conn->query("SELECT COUNT(*) FROM orders WHERE delivery_id = $rider_id AND status = 'delivered'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Yummzo Delivery | Rider Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f1f2f6; }
        .nav-rider { background: #2F3542; border-bottom: 3px solid #FF4757; }
        .hero-rider { background: #2F3542; color: white; padding: 40px 0; border-radius: 0 0 30px 30px; }
        .job-card { border: none; border-radius: 20px; transition: 0.3s; box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: white; }
        .active-job-card { border-left: 10px solid #2ed573; background: #fafffa; }
        .btn-yummzo { background: #FF4757; color: white; border-radius: 50px; font-weight: 600; padding: 10px 25px; border:none; }
        .btn-yummzo:hover { background: #2d3436; color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark nav-rider sticky-top py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-danger fs-3" href="jobs.php">YUMMZO <span class="text-white">RIDER</span></a>
        <div class="d-flex align-items-center">
            <a href="earnings.php" class="text-white text-decoration-none me-4 small"><i class="fas fa-wallet"></i> My Earnings</a>
            <a href="../auth/logout.php" class="btn btn-outline-light rounded-pill btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="hero-rider text-center">
    <div class="container">
        <h2 class="fw-bold">Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
        <p class="opacity-75">You have completed <strong><?php echo $total_done; ?></strong> deliveries so far.</p>
    </div>
</div>

<div class="container mt-n4">
    <div class="row">
        <div class="col-lg-6 mb-4">
            <h4 class="fw-bold mb-3 text-success"><i class="fas fa-route"></i> Active Task</h4>
            <?php if(!$my_job): ?>
                <div class="card job-card p-5 text-center">
                    <i class="fas fa-bicycle fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No active delivery. Accept a job to start earning!</p>
                </div>
            <?php else: ?>
                <div class="card job-card active-job-card p-4 shadow-sm">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="badge bg-success">ON THE WAY</span>
                        <h5 class="fw-bold mb-0">Order #<?php echo $my_job['id']; ?></h5>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block text-uppercase fw-bold">Pickup from:</small>
                        <h6 class="fw-bold"><i class="fas fa-utensils text-danger me-2"></i> <?php echo $my_job['restaurant_name']; ?></h6>
                    </div>
                    <div class="mb-4">
                        <small class="text-muted d-block text-uppercase fw-bold">Deliver to:</small>
                        <h6 class="fw-bold mb-1"><i class="fas fa-map-marker-alt text-success me-2"></i> <?php echo $my_job['c_name']; ?></h6>
                        <p class="small mb-0 ms-4"><?php echo $my_job['c_address']; ?></p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="tel:<?php echo $my_job['c_phone']; ?>" class="btn btn-dark rounded-pill flex-grow-1"><i class="fas fa-phone"></i> Call Customer</a>
                        <a href="complete-job.php?id=<?php echo $my_job['id']; ?>" class="btn btn-yummzo flex-grow-1">Mark Delivered</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-6">
            <h4 class="fw-bold mb-3"><i class="fas fa-clipboard-list"></i> Available Pickups</h4>
            <?php if(empty($available_jobs)): ?>
                <div class="text-center py-5">
                    <p class="text-muted">Scanning for new orders in your area...</p>
                    <div class="spinner-grow text-danger spinner-grow-sm" role="status"></div>
                </div>
            <?php else: ?>
                <?php foreach($available_jobs as $job): ?>
                    <div class="card job-card p-4 mb-3 border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1"><?php echo $job['restaurant_name']; ?></h6>
                                <p class="small text-muted mb-0"><i class="fas fa-location-arrow"></i> To: <?php echo substr($job['c_address'], 0, 40); ?>...</p>
                            </div>
                            <div class="text-end">
                                <span class="d-block fw-bold text-danger mb-2">₹<?php echo $job['total_amount']; ?></span>
                                <a href="accept-job.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-dark rounded-pill px-4 <?php echo $my_job ? 'disabled' : ''; ?>">Accept</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>setTimeout(function(){ if(!<?php echo $my_job ? 'true' : 'false'; ?>) location.reload(); }, 20000);</script>
</body>
</html>