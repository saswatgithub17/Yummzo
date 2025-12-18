<?php
include '../includes/auth_check.php';
checkRole('restaurant');
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$message = "";

// 1. Fetch Current Restaurant Data
$stmt = $conn->prepare("SELECT * FROM restaurants WHERE user_id = ?");
$stmt->execute([$user_id]);
$res = $stmt->fetch();
$res_id = $res['id'];

// 2. Handle Profile Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $res_name = $_POST['restaurant_name'];
    $cuisine  = $_POST['cuisine_type'];
    $status   = $_POST['status'];
    
    $update = $conn->prepare("UPDATE restaurants SET restaurant_name = ?, cuisine_type = ?, status = ? WHERE id = ?");
    $update->execute([$res_name, $cuisine, $status, $res_id]);
    
    // Also update phone and address in users table
    $update_user = $conn->prepare("UPDATE users SET phone = ?, address = ? WHERE id = ?");
    $update_user->execute([$_POST['phone'], $_POST['address'], $user_id]);
    
    $message = "<div class='alert alert-success rounded-pill px-4'>Store profile updated successfully!</div>";
    header("Refresh:1"); // Refresh to show new data
}

// 3. Simple Analytics Data
$total_delivered = $conn->query("SELECT COUNT(*) FROM orders WHERE restaurant_id = $res_id AND status = 'delivered'")->fetchColumn();
$revenue = $conn->query("SELECT SUM(total_amount) FROM orders WHERE restaurant_id = $res_id AND status = 'delivered'")->fetchColumn() ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Store Profile | Yummzo Partner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f7f6; font-family: 'Poppins', sans-serif; }
        .glass-card { background: white; border: none; border-radius: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .status-toggle { background: #f8f9fa; padding: 20px; border-radius: 15px; border: 1px solid #eee; }
        .analytics-pill { background: #2F3542; color: white; border-radius: 15px; padding: 20px; text-align: center; }
        .btn-save { background: #FF4757; color: white; border-radius: 50px; padding: 12px 40px; border: none; font-weight: 600; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Store Settings & Analytics</h2>
                <a href="dashboard.php" class="btn btn-outline-dark rounded-pill px-4">Dashboard</a>
            </div>

            <?php echo $message; ?>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="analytics-pill mb-4">
                        <i class="fas fa-chart-line fa-2x mb-3 text-danger"></i>
                        <h6 class="text-uppercase small opacity-75">Delivered Orders</h6>
                        <h2 class="fw-bold"><?php echo $total_delivered; ?></h2>
                    </div>
                    <div class="analytics-pill mb-4" style="background: #27ae60;">
                        <i class="fas fa-indian-rupee-sign fa-2x mb-3"></i>
                        <h6 class="text-uppercase small opacity-75">Lifetime Revenue</h6>
                        <h2 class="fw-bold">₹<?php echo number_format($revenue, 2); ?></h2>
                    </div>
                    <div class="card glass-card p-4 text-center">
                        <h6 class="fw-bold">Health Score</h6>
                        <h1 class="text-success fw-bold"><?php echo $res['health_rating']; ?></h1>
                        <p class="small text-muted mb-0">Verified by Govt. Health Dept.</p>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card glass-card p-5">
                        <form method="POST">
                            <h5 class="fw-bold mb-4 border-bottom pb-2">Business Details</h5>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Restaurant Name</label>
                                <input type="text" name="restaurant_name" class="form-control" value="<?php echo $res['restaurant_name']; ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Cuisine Type</label>
                                    <input type="text" name="cuisine_type" class="form-control" value="<?php echo $res['cuisine_type']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Store Status</label>
                                    <select name="status" class="form-select">
                                        <option value="open" <?php if($res['status']=='open') echo 'selected'; ?>>🟢 Currently Open</option>
                                        <option value="closed" <?php if($res['status']=='closed') echo 'selected'; ?>>🔴 Currently Closed</option>
                                    </select>
                                </div>
                            </div>

                            <h5 class="fw-bold mt-4 mb-3 border-bottom pb-2">Contact & Location</h5>
                            
                            <?php 
                                // Get current phone and address from users table
                                $u_stmt = $conn->prepare("SELECT phone, address FROM users WHERE id = ?");
                                $u_stmt->execute([$user_id]);
                                $u_data = $u_stmt->fetch();
                            ?>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Contact Phone</label>
                                <input type="text" name="phone" class="form-control" value="<?php echo $u_data['phone']; ?>" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Business Address</label>
                                <textarea name="address" class="form-control" rows="3" required><?php echo $u_data['address']; ?></textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-save shadow">Save All Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>