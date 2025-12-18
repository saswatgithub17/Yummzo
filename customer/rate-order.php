<?php
include '../config/db.php';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $order_id = $_POST['order_id'];
    $rating = $_POST['rating'];
    // Logic to update restaurant health_rating average (simplified)
    echo "<script>alert('Thank you for your feedback!'); window.location.href='order-history.php';</script>";
}
?>
<?php if($order['status'] == 'delivered'): ?>
<form action="rate-order.php" method="POST" class="mt-3 border-top pt-2">
    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
    <label class="small fw-bold">Rate this Meal:</label>
    <div class="d-flex gap-2">
        <select name="rating" class="form-select form-select-sm">
            <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
            <option value="4">⭐⭐⭐⭐ (Good)</option>
            <option value="3">⭐⭐⭐ (Average)</option>
        </select>
        <button class="btn btn-sm btn-danger">Submit</button>
    </div>
</form>
<?php endif; ?>