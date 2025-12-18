<?php
include '../includes/auth_check.php';
include '../config/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);

    header("Location: dashboard.php?msg=Status Updated");
    exit();
}