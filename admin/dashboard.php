<?php 
include('../config/db.php');
session_start();

// 1. Stats Calculations
$total_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM orders"))['total'];
$pending_deliveries = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM orders WHERE status != 'Delivered'"))['total'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders WHERE status = 'Delivered'"))['total'];
$active_menus = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM products"))['total'];

// Revenue agar null ho toh 0 show karein
$revenue = $total_revenue ? $total_revenue : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --sidebar-bg: #111827; --main-bg: #f4f7f4; --accent: #10b981; }
        body { background-color: var(--main-bg); font-family: 'Inter', sans-serif; display: flex; }
        .sidebar { width: 260px; height: 100vh; background: var(--sidebar-bg); position: fixed; color: white; padding: 20px; }
        .nav-link { color: #9ca3af; padding: 12px 15px; border-radius: 10px; margin-bottom: 5px; display: block; text-decoration: none; }
        .nav-link.active { background: #1f2937; color: white; }
        .main-content { margin-left: 260px; padding: 40px; width: 100%; }
        
        /* Stats Cards */
        .stat-card { border: none; border-radius: 16px; padding: 20px; background: white; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .icon-box { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        
        /* Status Badges */
        .badge-delivered { background: #dcfce7; color: #166534; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-way { background: #e0f2fe; color: #075985; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="d-flex align-items-center mb-5 px-2"><i class="bi bi-flower1 text-success fs-3 me-2"></i><span class="fs-4 fw-bold">CraveCart</span></div>
    <a href="dashboard.php" class="nav-link active"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="manage_menu.php" class="nav-link"><i class="bi bi-box-seam"></i> Manage Menu</a>
    <a href="manage_orders.php" class="nav-link"><i class="bi bi-cart-check"></i> Manage Orders</a>
    <a href="manage_riders.php" class="nav-link"><i class="bi bi-bicycle"></i> Manage Riders</a>
    <a href="../logout.php" class="nav-link text-danger mt-5"><i class="bi bi-box-arrow-left"></i> Logout</a>
</div>

<div class="main-content">
    <h2 class="fw-bold mb-4">Store Overview</h2>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between mb-3">
                    <div class="icon-box bg-light text-primary"><i class="bi bi-bag"></i></div>
                </div>
                <span class="text-muted small">Total Orders</span>
                <h3 class="fw-bold"><?php echo $total_orders; ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between mb-3">
                    <div class="icon-box bg-light text-warning"><i class="bi bi-truck"></i></div>
                </div>
                <span class="text-muted small">Pending Deliveries</span>
                <h3 class="fw-bold"><?php echo $pending_deliveries; ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between mb-3">
                    <div class="icon-box bg-light text-success"><i class="bi bi-cash-stack"></i></div>
                </div>
                <span class="text-muted small">Total Revenue</span>
                <h3 class="fw-bold">Rs. <?php echo number_format($revenue); ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between mb-3">
                    <div class="icon-box bg-light text-info"><i class="bi bi-list-ul"></i></div>
                </div>
                <span class="text-muted small">Active Menus</span>
                <h3 class="fw-bold"><?php echo $active_menus; ?></h3>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4 rounded-4">
        <h5 class="fw-bold mb-4">Recent Activity</h5>
        <table class="table align-middle">
            <thead class="text-muted">
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Status filter hata diya takay sary orders nazar ayen
                $res = mysqli_query($conn, "SELECT orders.*, users.name FROM orders JOIN users ON orders.customer_id = users.id ORDER BY orders.id DESC LIMIT 6");
                while($row = mysqli_fetch_assoc($res)) { 
                    $status_class = '';
                    if($row['status'] == 'Delivered') $status_class = 'badge-delivered';
                    elseif($row['status'] == 'Pending') $status_class = 'badge-pending';
                    else $status_class = 'badge-way'; // For Out for Delivery / Picked Up
                ?>
                <tr>
                    <td>#ORD-<?php echo $row['id']; ?></td>
                    <td class="fw-semibold"><?php echo $row['name']; ?></td>
                    <td>Rs. <?php echo number_format($row['total_amount']); ?></td>
                    <td><span class="badge <?php echo $status_class; ?> rounded-pill px-3 py-2"><?php echo $row['status']; ?></span></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>