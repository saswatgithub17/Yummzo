<?php
session_start();
include '../config/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and clean basic user data
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $password  = trim($_POST['password']); // PLAIN TEXT as requested
    $phone     = trim($_POST['phone']);
    $address   = trim($_POST['address']);
    $role      = $_POST['role'];

    try {
        // 1. Insert into 'users' table
        $sql = "INSERT INTO users (full_name, email, password, phone, role, address) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$full_name, $email, $password, $phone, $role, $address]);
        
        // Get the ID of the newly created user
        $user_id = $conn->lastInsertId();

        // 2. If the user is a restaurant owner, also create the restaurant entry
        if ($role == 'restaurant') {
            $restaurant_name = trim($_POST['restaurant_name']);
            $cuisine_type    = trim($_POST['cuisine_type']);
            
            $sql_res = "INSERT INTO restaurants (user_id, restaurant_name, cuisine_type) VALUES (?, ?, ?)";
            $stmt_res = $conn->prepare($sql_res);
            $stmt_res->execute([$user_id, $restaurant_name, $cuisine_type]);
        }

        $message = "<div class='alert alert-success'>Account created for Yummzo! <a href='login.php'>Login here</a></div>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Error code for duplicate entry (email)
            $message = "<div class='alert alert-danger'>Error: This email is already registered.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Yummzo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f1f2f6; }
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .btn-yummzo { background: #FF4757; color: white; border: none; font-weight: 600; }
        .btn-yummzo:hover { background: #e84118; color: white; }
        #restaurant-extra { display: none; background: #fff5f6; padding: 20px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #ffccd2; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card p-4">
                <div class="card-body">
                    <h2 class="text-center mb-4" style="color: #FF4757; font-weight: 700;">Join Yummzo</h2>
                    
                    <?php echo $message; ?>

                    <form action="register.php" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="full_name" class="form-control" required placeholder="John Doe">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" required placeholder="john@yummzo.com">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Your Role</label>
                            <select name="role" id="roleSelect" class="form-select" onchange="toggleRestaurantFields()" required>
                                <option value="customer">I want to Order Food (Customer)</option>
                                <option value="restaurant">I want to Sell Food (Restaurant Partner)</option>
                                <option value="delivery">I want to Deliver Food (Delivery Partner)</option>
                            </select>
                        </div>

                        <div id="restaurant-extra">
                            <h5 class="mb-3 text-danger">Business Information</h5>
                            <div class="mb-3">
                                <label class="form-label">Restaurant Name</label>
                                <input type="text" name="restaurant_name" id="res_name" class="form-control" placeholder="e.g. Yummzo Green Bites">
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Cuisine Type</label>
                                <input type="text" name="cuisine_type" id="res_cuisine" class="form-control" placeholder="e.g. Italian, Vegan, Local">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control" required placeholder="1234567890">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required placeholder="Choose a password">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Home/Business Address</label>
                            <textarea name="address" class="form-control" rows="2" required placeholder="Enter full address"></textarea>
                        </div>

                        <button type="submit" class="btn btn-yummzo w-100 py-2">Create My Account</button>
                    </form>

                    <p class="text-center mt-3 mb-0">Already registered? <a href="login.php" style="color: #FF4757;">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleRestaurantFields() {
    const roleSelect = document.getElementById('roleSelect');
    const extraFields = document.getElementById('restaurant-extra');
    const resName = document.getElementById('res_name');
    const resCuisine = document.getElementById('res_cuisine');

    if (roleSelect.value === 'restaurant') {
        extraFields.style.display = 'block';
        resName.required = true;
        resCuisine.required = true;
    } else {
        extraFields.style.display = 'none';
        resName.required = false;
        resCuisine.required = false;
    }
}
</script>

</body>
</html>