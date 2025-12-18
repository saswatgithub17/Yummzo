<?php
include '../includes/auth_check.php';
include '../config/db.php';

$rider_id = $_SESSION['user_id'];

/* -------------------------
   Delivered Orders History
--------------------------*/
$earnings_stmt = $conn->prepare("
    SELECT o.*, r.restaurant_name 
    FROM orders o 
    JOIN restaurants r ON o.restaurant_id = r.id 
    WHERE o.delivery_id = ? AND o.status = 'delivered'
    ORDER BY o.order_date DESC
");
$earnings_stmt->execute([$rider_id]);
$history = $earnings_stmt->fetchAll();

/* -------------------------
   Commission Calculation
--------------------------*/
$commission_per_order = 40;
$total_earned = count($history) * $commission_per_order;

/* -------------------------
   Cash in Hand Calculation
--------------------------*/
$cash_stmt = $conn->prepare("
    SELECT SUM(total_amount)
    FROM orders
    WHERE delivery_id = ? 
      AND status = 'delivered' 
      AND payment_received = FALSE
");
$cash_stmt->execute([$rider_id]);
$cash_in_hand = $cash_stmt->fetchColumn() ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Earnings | Yummzo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container py-5">

    <h2 class="fw-bold mb-4">Earnings Summary</h2>

    <!-- Total Earnings -->
    <div class="card p-4 border-0 shadow-sm mb-4 bg-dark text-white rounded-4">
        <h6>Total Payout Collected</h6>
        <h1 class="display-4 fw-bold text-danger">₹<?php echo $total_earned; ?></h1>
        <p class="mb-0 opacity-50">
            Based on <?php echo count($history); ?> completed trips
        </p>
    </div>

    <!-- Cash In Hand -->
    <div class="card bg-warning text-dark p-3 rounded-4 border-0 mb-4">
        <h6 class="fw-bold">
            <i class="fas fa-hand-holding-dollar"></i>
            Cash in Hand (to be submitted)
        </h6>
        <h2 class="fw-bold">₹<?php echo $cash_in_hand; ?></h2>
        <small>Please visit the hub to deposit this cash.</small>
    </div>

    <!-- Earnings Table -->
    <div class="card border-0 shadow-sm rounded-4">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Restaurant</th>
                    <th>Status</th>
                    <th>Earning</th>
                </tr>
            </thead>
            <tbody>
            <?php if(count($history) > 0): ?>
                <?php foreach($history as $row): ?>
                    <tr>
                        <td><?php echo date('d M', strtotime($row['order_date'])); ?></td>
                        <td><?php echo htmlspecialchars($row['restaurant_name']); ?></td>
                        <td><span class="badge bg-success">Paid</span></td>
                        <td class="fw-bold">₹<?php echo $commission_per_order; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        No completed deliveries yet
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <a href="jobs.php" class="btn btn-link mt-3 text-dark">← Back to Dashboard</a>

</div>
</body>
</html>