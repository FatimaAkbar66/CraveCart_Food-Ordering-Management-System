<?php 
include('../config/db.php'); 
session_start(); 

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

// Agar cart khali hai to wapas bhej do
if(empty($_SESSION['cart'])){
    header("Location: menu.php");
    exit();
}

$uid = $_SESSION['user_id'];

// 1. User ki mojooda details fetch karein (Form bharne ke liye)
$user_res = mysqli_query($conn, "SELECT name, email, address, phone FROM users WHERE id='$uid'");
$user_data = mysqli_fetch_assoc($user_res);

// 2. Cart ka Total calculate karein
$total_amount = 0;
foreach($_SESSION['cart'] as $pid){
    $p_res = mysqli_query($conn, "SELECT price FROM products WHERE id='$pid'");
    $p_data = mysqli_fetch_assoc($p_res);
    $total_amount += $p_data['price'];
}

$order_success = false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - CraveCart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --olive-primary: #5b7c5c; }
        body { background-color: #f4f7f4; font-family: 'Poppins', sans-serif; }
        .checkout-card { border: none; border-radius: 30px; box-shadow: 0 20px 50px rgba(0,0,0,0.05); background: #fff; overflow: hidden; }
        .checkout-header { background: var(--olive-primary); color: white; padding: 25px; text-align: center; }
        .form-control { border-radius: 12px; padding: 12px; border: 1px solid #ebf0eb; }
        
        #successOverlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255,255,255,0.96);
            display: none; flex-direction: column; align-items: center; justify-content: center;
            z-index: 9999; text-align: center;
        }
    </style>
</head>
<body>

<div id="successOverlay">
    <div style="font-size: 5rem; color: var(--olive-primary);"><i class="bi bi-check-circle-fill"></i></div>
    <h2 class="fw-bold">Order Placed Successfully!</h2>
    <p class="text-muted">Aapka order receive ho chuka hai.</p>
    <a href="menu.php" class="btn btn-success rounded-pill px-5 mt-3" style="background:var(--olive-primary); border:none;">Back to Menu</a>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card checkout-card">
                <div class="checkout-header">
                    <h3 class="fw-bold m-0">Confirm Your Order</h3>
                </div>
                
                <div class="p-4 p-md-5">
                    <h6 class="fw-bold mb-3">Delivery Information</h6>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="small fw-bold">Full Name</label>
                            <input type="text" name="customer_name" class="form-control" value="<?php echo $user_data['name']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo $user_data['phone']; ?>" placeholder="03XXXXXXXXX" required>
                        </div>
                        <div class="mb-4">
                            <label class="small fw-bold">Delivery Address</label>
                            <textarea name="address" class="form-control" rows="3" required><?php echo $user_data['address']; ?></textarea>
                        </div>

                        <div class="bg-light p-3 rounded-4 mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Items in Cart</span>
                                <span><?php echo count($_SESSION['cart']); ?></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold fs-4">
                                <span>Total Amount</span>
                                <span style="color: var(--olive-primary);">Rs. <?php echo number_format($total_amount); ?></span>
                            </div>
                        </div>

                        <button type="submit" name="place_order" class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow" style="background:var(--olive-primary); border:none;">
                            Place Order Now
                        </button>
                    </form>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="cart.php" class="text-muted text-decoration-none small"><i class="bi bi-arrow-left"></i> Back to Cart</a>
            </div>
        </div>
    </div>
</div>

<?php
if(isset($_POST['place_order'])){
    $c_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $addr = mysqli_real_escape_string($conn, $_POST['address']);
    $ph = mysqli_real_escape_string($conn, $_POST['phone']);

    // 1. User ki info update karein
    mysqli_query($conn, "UPDATE users SET name='$c_name', address='$addr', phone='$ph' WHERE id='$uid'");

    // 2. Order Table mein entry (Admin ke liye)
    $order_query = "INSERT INTO orders (customer_id, total_amount, status) VALUES ('$uid', '$total_amount', 'Pending')";
    
    if(mysqli_query($conn, $order_query)){
        // Cart khali kar dein order ke baad
        unset($_SESSION['cart']);
        echo "<script>document.getElementById('successOverlay').style.display = 'flex';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>

</body>
</html>