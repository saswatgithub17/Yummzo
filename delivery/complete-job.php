<?php
include '../includes/auth_check.php';
include '../config/db.php';

if(isset($_GET['id'])) {
    $order_id = $_GET['id'];
    
    // Update status to delivered
    $stmt = $conn->prepare("UPDATE orders SET status = 'delivered' WHERE id = ?");
    $stmt->execute([$order_id]);

    header("Location: jobs.php?msg=Success");
    exit();
}