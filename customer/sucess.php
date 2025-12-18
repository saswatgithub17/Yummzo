<?php include '../includes/auth_check.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Success!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-white text-center py-5">
    <div class="container">
        <img src="https://i.giphy.com/media/v1.Y2lkPTc5MGI3NjExOHJtYmx1M2M0bmx6bmx6bmx6bmx6bmx6bmx6bmx6bmx6bmx6bmx6bmx6/l0HlMGox8R7yXG5mE/giphy.gif" width="300" class="mb-4">
        <h1 class="text-success fw-bold">Woohoo! Order Placed.</h1>
        <p class="lead">Your food is being prepared. It will arrive in <span class="text-danger fw-bold">25 minutes</span>.</p>
        <a href="order-history.php" class="btn btn-outline-dark px-4 mt-3">Track Order</a>
    </div>
</body>
</html>