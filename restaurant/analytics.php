<?php
include '../includes/auth_check.php';
include '../config/db.php';

/* 
   NOTE:
   This page is opened from restaurant/dashboard.php
   So restaurant must be logged in
*/
$user_id = $_SESSION['user_id'];

/* Get Restaurant ID */
$res_stmt = $conn->prepare("SELECT id, restaurant_name FROM restaurants WHERE user_id = ?");
$res_stmt->execute([$user_id]);
$restaurant = $res_stmt->fetch();

if (!$restaurant) {
    die("Restaurant not found.");
}

$res_id = $restaurant['id'];

/* Fetch last 7 days revenue */
$stmt = $conn->prepare("
    SELECT DATE(order_date) as date, SUM(total_amount) as daily_revenue 
    FROM orders 
    WHERE restaurant_id = ? AND status = 'delivered'
    GROUP BY DATE(order_date)
    ORDER BY date DESC
    LIMIT 7
");
$stmt->execute([$res_id]);
$data = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Analytics | Yummzo Partner</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
        }
        .nav-partner {
            background: white;
            border-bottom: 2px solid #FF4757;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .stat-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .profile-link {
            color: #2d3436;
            font-weight: 600;
            text-decoration: none;
        }
        .profile-link:hover {
            color: #FF4757;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light nav-partner py-3 sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-danger fs-3" href="../restaurant/dashboard.php">
            YUMMZO <span class="text-dark">PARTNER</span>
        </a>

        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item">
                <a class="nav-link profile-link px-3" href="../restaurant/dashboard.php">
                    <i class="fas fa-columns me-1"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link profile-link px-3 active text-danger" href="#">
                    <i class="fas fa-chart-line me-1"></i> Analytics
                </a>
            </li>
            <li class="nav-item ms-lg-3">
                <a href="../auth/logout.php" class="btn btn-danger rounded-pill px-4">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- CONTENT -->
<div class="container py-5">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Revenue Analytics</h3>
        <p class="text-muted mb-0">
            <?php echo $restaurant['restaurant_name']; ?> — Last 7 Days Performance
        </p>
    </div>

    <!-- ANALYTICS CARD -->
    <div class="card stat-card p-4 border-0 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold mb-1">Sales Overview</h5>
                <p class="text-muted small mb-0">Daily delivered order revenue</p>
            </div>
            <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-circle">
                <i class="fas fa-chart-line fa-lg"></i>
            </div>
        </div>

        <canvas id="salesChart" height="120"></canvas>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('salesChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_reverse(array_column($data, 'date'))); ?>,
        datasets: [{
            label: 'Revenue (₹)',
            data: <?php echo json_encode(array_reverse(array_column($data, 'daily_revenue'))); ?>,
            fill: true,
            backgroundColor: 'rgba(255, 71, 87, 0.08)',
            borderColor: '#FF4757',
            borderWidth: 3,
            pointBackgroundColor: '#FF4757',
            pointRadius: 5,
            tension: 0.35
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#2F3542',
                titleColor: '#fff',
                bodyColor: '#fff',
                padding: 12,
                callbacks: {
                    label: function(context) {
                        return ' ₹' + context.parsed.y;
                    }
                }
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: {
                    color: '#6c757d',
                    font: { weight: '600' }
                }
            },
            y: {
                grid: { borderDash: [6, 6] },
                ticks: {
                    color: '#6c757d',
                    callback: value => '₹' + value
                }
            }
        }
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
