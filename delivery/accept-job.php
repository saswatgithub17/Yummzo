<?php
include '../includes/auth_check.php';
include '../config/db.php';

if(isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $rider_id = $_SESSION['user_id'];

    // Update the order with rider ID
    $stmt = $conn->prepare("UPDATE orders SET delivery_id = ?, status = 'out_for_delivery' WHERE id = ?");
    $stmt->execute([$rider_id, $order_id]);

    header("Location: jobs.php?msg=JobAccepted");
    exit();
}