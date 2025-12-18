<?php
include '../includes/auth_check.php';
include '../config/db.php';

if(!empty($_SESSION['cart'])) {
    $uid = $_SESSION['user_id'];
    $first = reset($_SESSION['cart']);
    $res_id = $first['res_id'];
    
    // Calculate total
    $total = 0;
    foreach($_SESSION['cart'] as $id => $item) {
        $price = $conn->query("SELECT price FROM menu_items WHERE id = $id")->fetchColumn();
        $total += ($price * $item['qty']);
    }

    $stmt = $conn->prepare("INSERT INTO orders (customer_id, restaurant_id, total_amount, status) VALUES (?, ?, ?, 'pending')");
    $stmt->execute([$uid, $res_id, $total]);
    
    unset($_SESSION['cart']);
    header("Location: sucess.php");
}