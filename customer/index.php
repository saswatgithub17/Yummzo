<?php
include '../includes/auth_check.php';
checkRole('customer');
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// 1. Search Logic
$cuisine_filter = isset($_GET['cuisine']) ? $_GET['cuisine'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Advanced SQL using LEFT JOIN to search for dishes inside restaurants
$sql = "SELECT DISTINCT r.* FROM restaurants r 
        LEFT JOIN menu_items m ON r.id = m.restaurant_id 
        WHERE 1=1";
$params = [];

if ($cuisine_filter) {
    $sql .= " AND r.cuisine_type = ?";
    $params[] = $cuisine_filter;
}
if ($search_query) {
    $sql .= " AND (r.restaurant_name LIKE ? OR r.cuisine_type LIKE ? OR m.item_name LIKE ?)";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
}

$sql .= " ORDER BY r.status DESC, r.health_rating DESC";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$restaurants = $stmt->fetchAll();

$latest_order_stmt = $conn->prepare("SELECT status, id FROM orders WHERE customer_id = ? ORDER BY order_date DESC LIMIT 1");
$latest_order_stmt->execute([$user_id]);
$latest_order = $latest_order_stmt->fetch();

$current_time = date('H:i:s');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yummzo | Discover Best Food</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; color: #2d3436; }
        .navbar { background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .navbar-brand { font-weight: 700; color: #FF4757 !important; font-size: 1.5rem; }
        
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                        url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1350&q=80');
            background-size: cover; background-position: center; padding: 100px 0; color: white; border-radius: 0 0 40px 40px; margin-bottom: 40px;
        }

        .res-card { border: none; border-radius: 20px; overflow: hidden; transition: 0.4s; background: white; height: 100%; position: relative; }
        .res-card:hover { transform: translateY(-10px); box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        .res-card.closed { opacity: 0.8; filter: grayscale(0.8); }
        .status-badge-open { background: #2ed573; color: white; padding: 3px 12px; border-radius: 50px; font-size: 0.7rem; font-weight: 700; }
        .status-badge-closed { background: #747d8c; color: white; padding: 3px 12px; border-radius: 50px; font-size: 0.7rem; font-weight: 700; }
        .closed-overlay { position: absolute; top: 15px; right: 15px; z-index: 5; }
        
        .res-img { height: 210px; object-fit: cover; width: 100%; background: #eee; }
        .rating-chip { background: #2ed573; color: white; padding: 2px 10px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; }
        
        /* Search Box Enhancements */
        .search-container { position: relative; max-width: 600px; margin: 0 auto; }
        .clear-search { 
            position: absolute; right: 140px; top: 50%; transform: translateY(-50%);
            background: #eee; border-radius: 50%; width: 25px; height: 25px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #666; font-size: 0.8rem; z-index: 10;
        }
        .clear-search:hover { background: #ddd; color: #333; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">YUMMZO</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link px-3 fw-bold" href="order-history.php">Orders</a></li>
                <li class="nav-item"><a class="nav-link px-3 fw-bold" href="profile.php">Profile</a></li>
                <li class="nav-item"><a href="cart.php" class="btn btn-danger rounded-pill px-4 ms-2">Cart</a></li>
                <li class="nav-item"><a href="../auth/logout.php" class="btn btn-outline-dark rounded-pill px-4 ms-2 btn-sm">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
        <p class="lead mb-5">Search by restaurant or find your favorite dish.</p>
        
        <div class="search-container">
            <form action="index.php" method="GET" id="searchForm">
                <div class="input-group input-group-lg shadow-lg rounded-pill overflow-hidden">
                    <input type="text" name="search" id="searchInput" class="form-control border-0 px-4" placeholder="What are you craving today?" value="<?php echo htmlspecialchars($search_query); ?>">
                    
                    <?php if($search_query): ?>
                        <div class="clear-search" onclick="window.location.href='index.php'">
                            <i class="fas fa-times"></i>
                        </div>
                    <?php endif; ?>

                    <button class="btn btn-danger px-4" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">
            <?php echo $search_query ? 'Search Results for "'.htmlspecialchars($search_query).'"' : 'Explore Restaurants'; ?>
        </h4>
        <span class="text-muted small"><?php echo count($restaurants); ?> restaurants available</span>
    </div>
    
    <div class="row g-4">
        <?php foreach($restaurants as $res): 
            $is_open_time = ($current_time >= $res['open_time'] && $current_time <= $res['close_time']);
            $is_really_open = ($res['status'] == 'open' && $is_open_time);
            $img_cat = strtolower(str_replace(' ', '', $res['cuisine_type']));
            $unique_image = "https://loremflickr.com/600/400/restaurant,".$img_cat."/all?lock=" . $res['id'];
        ?>
        <div class="col-md-4">
            <div class="card res-card <?php echo (!$is_really_open) ? 'closed' : ''; ?>">
                <div class="closed-overlay">
                    <span class="<?php echo $is_really_open ? 'status-badge-open' : 'status-badge-closed'; ?> text-uppercase">
                        <?php echo $is_really_open ? 'Open Now' : 'Closed'; ?>
                    </span>
                </div>
                <img src="<?php echo $unique_image; ?>" class="res-img" alt="Restaurant">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($res['restaurant_name']); ?></h5>
                            <small class="text-muted"><?php echo $res['cuisine_type']; ?></small>
                        </div>
                        <span class="rating-chip">★ <?php echo $res['health_rating']; ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                        <span class="text-success small fw-bold"><i class="fas fa-shield-alt"></i> Safety First</span>
                        <?php if($is_really_open): ?>
                            <a href="view-menu.php?id=<?php echo $res['id']; ?>" class="btn btn-danger btn-sm rounded-pill px-4">View Menu</a>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-sm rounded-pill px-4" disabled>Offline</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>