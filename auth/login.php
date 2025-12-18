<?php
session_start();
include '../config/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];

        if($user['role'] == 'customer') header("Location: ../customer/index.php");
        elseif($user['role'] == 'restaurant') header("Location: ../restaurant/dashboard.php");
        elseif($user['role'] == 'delivery') header("Location: ../delivery/jobs.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Yummzo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #FF4757; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { width: 400px; background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); padding: 40px; }
        .btn-yummzo { background: #FF4757; color: white; border: none; font-weight: 600; width: 100%; padding: 12px; border-radius: 10px; }
        .btn-yummzo:hover { background: #2F3542; color: white; }
    </style>
</head>
<body>

<div class="login-card">
    <h3 class="text-center fw-bold mb-4">Login to Yummzo</h3>
    
    <?php if($error): ?>
        <div class="alert alert-danger text-center py-2" style="font-size: 0.9rem;"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-yummzo mb-3">Login</button>
    </form>
    <p class="text-center small">New user? <a href="register.php" class="text-danger text-decoration-none fw-bold">Register here</a></p>
</div>

</body>
</html>