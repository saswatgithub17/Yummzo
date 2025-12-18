<?php
session_start();
$id = $_GET['id'];
$action = $_GET['action'];

if ($action == 'inc') {
    $_SESSION['cart'][$id]['qty']++;
} elseif ($action == 'dec') {
    if ($_SESSION['cart'][$id]['qty'] > 1) $_SESSION['cart'][$id]['qty']--;
    else unset($_SESSION['cart'][$id]);
} elseif ($action == 'remove') {
    unset($_SESSION['cart'][$id]);
}

header("Location: cart.php");
exit();