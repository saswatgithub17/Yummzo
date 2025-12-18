<?php
session_start();
if(isset($_GET['id'])) {
    $item_id = $_GET['id'];
    $res_id = $_GET['res_id'];
    
    // Add to session
    if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    
    if(isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['qty']++;
    } else {
        $_SESSION['cart'][$item_id] = ['qty' => 1, 'res_id' => $res_id];
    }
    header("Location: cart.php");
    exit();
}