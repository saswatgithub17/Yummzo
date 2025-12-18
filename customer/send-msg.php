<?php
include '../config/db.php';
session_start();
$order_id = $_POST['order_id'];
$msg = $_POST['message'];
$sender = $_SESSION['user_id'];

$stmt = $conn->prepare("INSERT INTO messages (order_id, sender_id, message) VALUES (?, ?, ?)");
$stmt->execute([$order_id, $sender, $msg]);
echo "Sent";
?>