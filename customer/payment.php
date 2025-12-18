<!DOCTYPE html>
<html lang="en">
<head>
    <title>Yummzo | Secure Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .pay-card { border: 2px solid #eee; cursor: pointer; transition: 0.3s; }
        .pay-card:hover { border-color: #FF4757; background: #fff8f8; }
        input[type="radio"]:checked + .pay-card { border-color: #FF4757; background: #fff8f8; }
    </style>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <a href="cart.php" class="text-decoration-none text-dark"><i class="fa fa-arrow-left"></i> Back to Cart</a>
            <h3 class="fw-bold my-4">Payment Method</h3>
            
            <form action="place-order.php" method="POST">
                <label class="w-100 mb-3">
                    <input type="radio" name="method" value="UPI" class="d-none" required onclick="document.getElementById('upi_input').style.display='block'">
                    <div class="card pay-card p-3 rounded-4">
                        <div class="d-flex align-items-center">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/e/e1/UPI-Logo-vector.svg" width="50" class="me-3">
                            <h6 class="mb-0 fw-bold">UPI (GPay / PhonePe)</h6>
                        </div>
                    </div>
                </label>
                
                <div id="upi_input" style="display:none;" class="mb-3 px-2">
                    <input type="text" name="upi_id" class="form-control" placeholder="enter-vpa@upi">
                </div>

                <label class="w-100 mb-4">
                    <input type="radio" name="method" value="COD" class="d-none" onclick="document.getElementById('upi_input').style.display='none'">
                    <div class="card pay-card p-3 rounded-4">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-wallet fa-2x me-3 text-muted"></i>
                            <h6 class="mb-0 fw-bold">Cash on Delivery</h6>
                        </div>
                    </div>
                </label>

                <button class="btn btn-danger w-100 py-3 rounded-pill fw-bold shadow">PLACE ORDER</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>