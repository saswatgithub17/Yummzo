<?php
include '../includes/auth_check.php';
include '../config/db.php';

$id = $_GET['id'];

// Get Restaurant Details
$res_stmt = $conn->prepare("SELECT * FROM restaurants WHERE id = ?");
$res_stmt->execute([$id]);
$res = $res_stmt->fetch();

// Get Menu Items
$menu_stmt = $conn->prepare("SELECT * FROM menu_items WHERE restaurant_id = ?");
$menu_stmt->execute([$id]);
$menu = $menu_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $res['restaurant_name']; ?> | Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .menu-header { background: white; padding: 40px 0; border-bottom: 1px solid #eee; margin-bottom: 30px; }
        .rating-badge { background: #2ed573; color: white; padding: 4px 12px; border-radius: 8px; font-weight: 700; }
        
        .menu-card { 
            border: none; 
            border-radius: 20px; 
            transition: 0.3s; 
            overflow: hidden; 
            background: white;
            position: relative;
        }
        .menu-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        
        /* Surplus Style */
        .surplus-card { border: 2px solid #2ed573 !important; }
        .surplus-label { 
            position: absolute; top: 10px; left: 10px; 
            background: #2ed573; color: white; 
            font-size: 0.65rem; font-weight: 700; 
            padding: 3px 10px; border-radius: 50px; 
            z-index: 5;
        }

        .dish-img { 
            width: 120px; height: 120px; 
            object-fit: cover; border-radius: 15px; 
            background: #f1f2f6;
        }
        .sold-out { filter: grayscale(1); opacity: 0.6; pointer-events: none; }
        .btn-add { border-radius: 10px; font-weight: 600; transition: 0.3s; }
        .btn-add:hover { background: #FF4757; color: white; border-color: #FF4757; }
    </style>
</head>
<body>

<div class="menu-header shadow-sm">
    <div class="container">
        <a href="index.php" class="btn btn-sm btn-outline-dark rounded-pill mb-3">
            <i class="fas fa-arrow-left me-1"></i> Back to Restaurants
        </a>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fw-bold mb-1 text-dark"><?php echo $res['restaurant_name']; ?></h1>
                <p class="text-muted mb-0">
                    <i class="fas fa-utensils me-1"></i> <?php echo $res['cuisine_type']; ?> 
                    <span class="mx-2">|</span> 
                    <i class="fas fa-map-marker-alt me-1"></i> Fast Delivery
                </p>
            </div>
            <div class="text-end">
                <span class="rating-badge">★ <?php echo $res['health_rating']; ?></span>
                <p class="small text-muted mt-1 mb-0">Govt. Safety Rated</p>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <h4 class="fw-bold mb-4">Recommended Dishes</h4>
    <div class="row g-4">
        <?php foreach($menu as $item): 
            // UNIQUE DISH IMAGE LOGIC
            // Keyword: Dish Name
            // Lock: Item ID (Ensures unique image for every item)
            $dish_keyword = strtolower(str_replace(' ', '', $item['item_name']));
            $item_image = "https://loremflickr.com/200/200/food,".$dish_keyword."/all?lock=" . $item['id'];
        ?>
        <div class="col-md-6">
            <div class="card menu-card p-3 <?php echo $item['is_surplus'] ? 'surplus-card' : ''; ?> <?php echo !$item['is_available'] ? 'sold-out' : ''; ?>">
                
                <?php if($item['is_surplus']): ?>
                    <span class="surplus-label text-uppercase"><i class="fas fa-leaf"></i> Surplus Deal</span>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center">
                    <div style="flex: 1; padding-right: 15px;">
                        <h5 class="fw-bold mb-1"><?php echo $item['item_name']; ?></h5>
                        <p class="text-muted small mb-3">
                            <?php echo $item['description'] ?: 'Prepared fresh with high quality ingredients and local spices.'; ?>
                        </p>
                        <div class="d-flex align-items-center">
                            <h5 class="fw-bold text-dark mb-0 me-2">₹<?php echo $item['price']; ?></h5>
                            <?php if($item['is_surplus']): ?>
                                <small class="text-success fw-bold">(Save 50%)</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <img src="<?php echo $item_image; ?>" class="dish-img mb-2" alt="dish">
                        <br>
                        <?php if($item['is_available']): ?>
                            <a href="add-to-cart.php?id=<?php echo $item['id']; ?>&res_id=<?php echo $id; ?>" 
                               class="btn btn-sm btn-outline-danger btn-add w-100">
                               ADD
                            </a>
                        <?php else: ?>
                            <button class="btn btn-sm btn-secondary w-100 disabled">Sold Out</button>
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