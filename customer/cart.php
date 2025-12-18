<?php
include '../includes/auth_check.php';
include '../config/db.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Yummzo | My Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #fdfdfd; font-family: 'Poppins', sans-serif; }
        .cart-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .item-row { border-bottom: 1px solid #eee; padding: 15px 0; align-items: center; }
        .qty-btn { width: 30px; height: 30px; border-radius: 8px; border: 1px solid #FF4757; background: white; color: #FF4757; }
        .qty-btn:hover { background: #FF4757; color: white; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="index.php" class="btn btn-outline-dark rounded-pill"><i class="fa fa-arrow-left"></i> Back to Menu</a>
        <h2 class="fw-bold mb-0">Shopping Cart</h2>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card cart-card p-4">
                <?php if(empty($cart)): ?>
                    <div class="text-center py-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" width="100" class="mb-3">
                        <p class="text-muted">Your cart is feeling lonely.</p>
                    </div>
                <?php else: ?>
                    <?php foreach($cart as $id => $details): 
                        $stmt = $conn->prepare("SELECT * FROM menu_items WHERE id = ?");
                        $stmt->execute([$id]);
                        $item = $stmt->fetch();
                        $sub = $item['price'] * $details['qty'];
                        $total += $sub;
                    ?>
                    <div class="row item-row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-0"><?php echo $item['item_name']; ?></h6>
                            <span class="text-muted small">₹<?php echo $item['price']; ?> per unit</span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                            <a href="update-cart.php?action=dec&id=<?php echo $id; ?>" class="qty-btn text-center">-</a>
                            <span class="mx-3 fw-bold"><?php echo $details['qty']; ?></span>
                            <a href="update-cart.php?action=inc&id=<?php echo $id; ?>" class="qty-btn text-center">+</a>
                        </div>
                        <div class="col-md-2 fw-bold text-danger">₹<?php echo $sub; ?></div>
                        <div class="col-md-1 text-end">
                            <a href="update-cart.php?action=remove&id=<?php echo $id; ?>" class="text-muted"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card cart-card p-4 bg-white">
                <h5 class="fw-bold mb-3">Bill Details</h5>
                <div class="d-flex justify-content-between mb-2"><span>Item Total</span><span>₹<?php echo $total; ?></span></div>
                <div class="d-flex justify-content-between mb-2"><span>Delivery Fee</span><span class="text-success">FREE</span></div>
                <hr>
                <div class="d-flex justify-content-between mb-4"><span class="fw-bold">Grand Total</span><span class="fw-bold text-danger fs-4">₹<?php echo $total; ?></span></div>
                <a href="payment.php" class="btn btn-danger w-100 py-3 rounded-pill fw-bold <?php echo empty($cart) ? 'disabled' : ''; ?>">Proceed to Checkout</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>