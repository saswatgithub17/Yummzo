<?php
include '../includes/auth_check.php';
include '../config/db.php';

if(isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];
    $stmt = $conn->prepare("UPDATE menu_items SET is_available = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
}
header("Location: manage-menu.php");
exit();