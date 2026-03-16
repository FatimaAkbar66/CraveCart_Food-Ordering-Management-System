<?php 
include('../config/db.php'); 
session_start(); 

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

// Item remove karne ki logic
if(isset($_GET['remove'])){
    $id = $_GET['remove'];
    if (($key = array_search($id, $_SESSION['cart'])) !== false) {
        unset($_SESSION['cart'][$key]);
    }
    header("Location: cart.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f4; font-family: 'Poppins', sans-serif; }
        .cart-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .item-img { width: 80px; height: 80px; object-fit: cover; border-radius: 15px; }
        .btn-checkout { background: #5b7c5c; color: white; border-radius: 50px; border: none; padding: 12px; font-weight: 600; }
        .btn-checkout:hover { background: #4a664b; color: white; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="fw-bold">Your <span style="color: #5b7c5c;">Shopping Bag</span></h2>
        <a href="menu.php" class="btn btn-outline-secondary rounded-pill px-4">Continue Shopping</a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <?php 
            $total = 0;
            if(!empty($_SESSION['cart'])){
                foreach($_SESSION['cart'] as $id){
                    $res = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");
                    while($row = mysqli_fetch_assoc($res)){
                        $total += $row['price'];
                        
                        // Smart Path logic
                        $img_path = "../images/" . $row['image'];
                        if (!file_exists($img_path) || empty($row['image'])) {
                            $img_path = "../assets/images/" . $row['image'];
                        }
            ?>
            <div class="card cart-card p-3 mb-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo $img_path; ?>" class="item-img me-3" onerror="this.src='https://placehold.co/80'">
                        <div>
                            <h6 class="mb-0 fw-bold"><?php echo $row['name']; ?></h6>
                            <small class="text-muted"><?php echo $row['restaurant']; ?></small>
                            <div class="fw-bold text-success mt-1">Rs. <?php echo number_format($row['price']); ?></div>
                        </div>
                    </div>
                    <a href="?remove=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger border-0 fs-5"><i class="bi bi-trash"></i></a>
                </div>
            </div>
            <?php 
                    }
                }
            } else {
                echo "<div class='text-center py-5'><i class='bi bi-bag-x display-1 text-muted'></i><p class='mt-3'>Aapki cart khali hai!</p></div>";
            }
            ?>
        </div>

        <div class="col-lg-4">
            <div class="card cart-card p-4">
                <h5 class="fw-bold mb-4">Order Summary</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span>Items Count</span>
                    <span><?php echo count($_SESSION['cart'] ?? []); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Delivery</span>
                    <span class="text-success">Free</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold fs-5">Total</span>
                    <span class="fw-bold fs-5 text-success">Rs. <?php echo number_format($total); ?></span>
                </div>
                
                <?php if($total > 0): ?>
                    <a href="checkout.php" class="btn btn-checkout w-100">Proceed to Checkout</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>