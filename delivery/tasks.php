<?php 
include('../config/db.php');
session_start();

// 1. Check login & Role (Security)
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'delivery'){
    header("Location: ../login.php");
    exit();
}

$rider_id = $_SESSION['user_id'];

// 2. Status update logic 
if(isset($_GET['action']) && isset($_GET['order_id'])){
    $oid = $_GET['order_id'];
    $new_status = ($_GET['action'] == 'out') ? 'Out for Delivery' : 'Delivered';
    
    // Sirf wahi order update ho jo is rider ko assigned ho
    mysqli_query($conn, "UPDATE orders SET status='$new_status' WHERE id='$oid' AND delivery_boy_id='$rider_id'");
    header("Location: tasks.php");
}

// 3. Earnings Calculation (10% Commission example)
$earning_res = mysqli_query($conn, "SELECT SUM(total_amount * 0.10) as total FROM orders WHERE delivery_boy_id = '$rider_id' AND status = 'Delivered'");
$earning_data = mysqli_fetch_assoc($earning_res);
$my_earnings = $earning_data['total'] ?? 0;

// 4. Fixed Query (Sirf is Rider ke Assigned & Pending orders)
$query = "SELECT orders.*, users.name as customer_name, users.address, users.phone 
          FROM orders 
          INNER JOIN users ON orders.customer_id = users.id 
          WHERE orders.delivery_boy_id = '$rider_id' AND orders.status != 'Delivered'
          ORDER BY orders.id DESC";

$orders = mysqli_query($conn, $query);

if (!$orders) {
    die("Query Failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --sidebar-bg: #111827; --main-bg: #f4f7f4; --accent: #5b7c5c; }
        body { background-color: var(--main-bg); font-family: 'Inter', sans-serif; display: flex; min-height: 100vh; margin: 0; }
        .sidebar { width: 260px; background: var(--sidebar-bg); color: white; padding: 20px; position: fixed; height: 100vh; }
        .nav-link { color: #9ca3af; padding: 12px 15px; border-radius: 10px; margin-bottom: 5px; display: block; text-decoration: none; }
        .nav-link.active { background: #1f2937; color: white; }
        .main-content { margin-left: 260px; padding: 40px; width: 100%; }
        .order-card { background: white; border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: 0.3s; }
        .order-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.07); }
        .earning-banner { background: white; border-left: 5px solid var(--accent); border-radius: 12px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="d-flex align-items-center mb-5 px-2">
        <i class="bi bi-bicycle fs-3 text-success me-2"></i>
        <span class="fs-5 fw-bold">Rider Panel</span>
    </div>
    <a href="tasks.php" class="nav-link active"><i class="bi bi-list-check"></i> My Tasks</a>
    <a href="history.php" class="nav-link"><i class="bi bi-clock-history"></i> History</a>
    <a href="../logout.php" class="nav-link text-danger mt-5"><i class="bi bi-box-arrow-left"></i> Logout</a>
</div>

<div class="main-content">
    <div class="row mb-4">
        <div class="col-12">
            <div class="earning-banner d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-0 small fw-bold text-uppercase">My Total Earnings</p>
                    <h3 class="fw-bold mb-0 text-dark">Rs. <?php echo number_format($my_earnings); ?></h3>
                </div>
                <div class="text-end">
                    <span class="badge bg-success-subtle text-success rounded-pill px-3">Active Now</span>
                </div>
            </div>
        </div>
    </div>

    <h4 class="fw-bold mb-4">Assigned Deliveries</h4>
    
    <div class="row">
        <?php if(mysqli_num_rows($orders) > 0): ?>
            <?php while($o = mysqli_fetch_assoc($orders)): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card order-card p-3 h-100">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small fw-bold">#ORD-<?php echo $o['id']; ?></span>
                        <span class="badge bg-light text-dark rounded-pill"><?php echo $o['status']; ?></span>
                    </div>
                    
                    <h5 class="fw-bold mb-1"><?php echo $o['customer_name']; ?></h5>
                    <p class="text-muted small mb-3"><i class="bi bi-geo-alt"></i> <?php echo $o['address'] ?? 'No address provided'; ?></p>
                    <p class="small mb-3"><i class="bi bi-phone"></i> <?php echo $o['phone'] ?? 'N/A'; ?></p>
                    
                    <a href="http://maps.google.com/?q=<?php echo urlencode($o['address']); ?>" target="_blank" class="btn btn-outline-primary btn-sm w-100 rounded-pill mb-3">
                        <i class="bi bi-map"></i> Open Google Maps
                    </a>

                    <div class="mt-auto">
                        <?php if($o['status'] == 'Picked Up'): ?>
                            <a href="?action=out&order_id=<?php echo $o['id']; ?>" class="btn btn-dark w-100 rounded-pill shadow-sm">Start Delivery</a>
                        <?php else: ?>
                            <a href="?action=delivered&order_id=<?php echo $o['id']; ?>" class="btn btn-success w-100 rounded-pill shadow-sm">Mark as Delivered</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-clipboard-check text-muted fs-1"></i>
                <p class="text-muted mt-2">All caught up! No new orders for you.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>