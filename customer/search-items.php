<?php
include '../config/db.php';
$query = $_GET['q'] ?? '';
$stmt = $conn->prepare("
    SELECT m.*, r.restaurant_name 
    FROM menu_items m 
    JOIN restaurants r ON m.restaurant_id = r.id 
    WHERE m.item_name LIKE ? AND m.is_available = 1
");
$stmt->execute(["%$query%"]);
$items = $stmt->fetchAll();
?>