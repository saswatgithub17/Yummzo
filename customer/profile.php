<?php
include '../includes/auth_check.php';
checkRole('customer');
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$message = "";

// Handle Profile Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_phone = trim($_POST['phone']);
    $new_address = trim($_POST['address']);

    try {
        $update_sql = "UPDATE users SET phone = ?, address = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->execute([$new_phone, $new_address, $user_id]);
        $message = "<div class='alert alert-success'>Profile updated successfully!</div>";
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger'>Update failed: " . $e->getMessage() . "</div>";
    }
}

// Fetch current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | Yummzo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .profile-header { background: #FF4757; color: white; padding: 60px 0; margin-bottom: -50px; }
        .profile-card { border: none; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .info-label { color: #888; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; }
        .readonly-box { background: #f1f2f6; border-radius: 10px; padding: 10px 15px; margin-bottom: 15px; border: 1px solid #e1e2e7; color: #57606f; }
        .btn-update { background: #FF4757; color: white; border-radius: 50px; padding: 10px 30px; border: none; font-weight: 600; }
        .btn-update:hover { background: #2F3542; color: white; }
    </style>
</head>
<body>

<div class="profile-header text-center">
    <div class="container">
        <h1>My Account</h1>
        <p>Manage your details and delivery preferences</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card profile-card">
                <div class="card-body p-5">
                    <?php echo $message; ?>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="info-label">Full Name</label>
                            <div class="readonly-box"><?php echo $user['full_name']; ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="info-label">Email Address</label>
                            <div class="readonly-box"><?php echo $user['email']; ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="info-label">Account Role</label>
                            <div class="readonly-box text-capitalize"><?php echo $user['role']; ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="info-label">Member Since</label>
                            <div class="readonly-box"><?php echo date('d M Y', strtotime($user['created_at'])); ?></div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-4"><i class="fas fa-map-marker-alt text-danger"></i> Update Delivery Information</h5>

                    <form action="profile.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control form-control-lg" value="<?php echo $user['phone']; ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Delivery Address / Location</label>
                            <textarea name="address" class="form-control form-control-lg" rows="4" required><?php echo $user['address']; ?></textarea>
                            <div class="form-text mt-2">Make sure to include landmarks to help our riders find you faster!</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="index.php" class="text-decoration-none text-muted"><i class="fas fa-arrow-left"></i> Back to Home</a>
                            <button type="submit" class="btn btn-update px-5">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="../auth/logout.php" class="text-danger fw-bold text-decoration-none"><i class="fas fa-sign-out-alt"></i> Logout from Yummzo</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>