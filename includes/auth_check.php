<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Optional: check if the user is allowed in this specific folder
function checkRole($allowedRole) {
    if ($_SESSION['role'] !== $allowedRole) {
        header("Location: ../auth/login.php?error=unauthorized");
        exit();
    }
}
?>