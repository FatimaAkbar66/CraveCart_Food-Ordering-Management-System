<?php 
include('../config/db.php');
session_start();

// Check if Admin is logged in
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

// 1. Fetch PENDING Orders with Customer Details
$query = "SELECT orders.*, users.name as customer_name, users.address, users.phone 
          FROM orders 
          JOIN users ON orders.customer_id = users.id 
          WHERE orders.status = 'Pending' 
          ORDER BY orders.id DESC";
$orders = mysqli_query($conn, $query);

// 2. Fetch all Registered Delivery Boys for the dropdown
$riders_query = "SELECT id, name FROM users WHERE role = 'delivery'";
$riders = mysqli_query($conn, $riders_query);
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
        .table-card { background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: none; }
        .btn-assign { background: #111827; color: white; border-radius: 50px; font-size: 0.8rem; padding: 8px 20px; transition: 0.3s; }
        .btn-assign:hover { background: #374151; transform: scale(1.05); }
        .form-select-sm { border-radius: 8px; padding: 8px; border: 1px solid #e5e7eb; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="d-flex align-items-center mb-5 px-2"><i class="bi bi-flower1 text-success fs-3 me-2"></i><span class="fs-4 fw-bold">CraveCart</span></div>
    <a href="dashboard.php" class="nav-link"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="manage_menu.php" class="nav-link"><i class="bi bi-box-seam"></i> Manage Menu</a>
    <a href="manage_orders.php" class="nav-link active"><i class="bi bi-cart-check"></i> Manage Orders</a>
    <a href="manage_riders.php" class="nav-link"><i class="bi bi-bicycle"></i> Manage Riders</a>
    <a href="../logout.php" class="nav-link text-danger mt-5"><i class="bi bi-box-arrow-left"></i> Logout</a>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Incoming Orders</h2>
        <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><?php echo mysqli_num_rows($orders); ?> New Orders</span>
    </div>
    
    <div class="table-card">
        <table class="table align-middle">
            <thead class="text-muted small text-uppercase">
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Delivery Address</th>
                    <th>Total</th>
                    <th>Assign Rider</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($orders)) { ?>
                <tr>
                    <td><span class="text-muted fw-bold">#ORD-<?php echo $row['id']; ?></span></td>
                    <td>
                        <div class="fw-bold"><?php echo $row['customer_name']; ?></div>
                        <div class="small text-muted"><?php echo $row['phone'] ?? ''; ?></div>
                    </td>
                    <td class="small text-muted" style="max-width: 200px;"><?php echo $row['address']; ?></td>
                    <td class="fw-bold text-success">Rs. <?php echo number_format($row['total_amount']); ?></td>
                    
                    <form action="assign_order.php" method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <td>
                            <select name="rider_id" class="form-select form-select-sm" required>
                                <option value="">Select Delivery Boy</option>
                                <?php 
                                mysqli_data_seek($riders, 0); // Reset rider query to top
                                while($rider = mysqli_fetch_assoc($riders)) {
                                    echo "<option value='".$rider['id']."'>".$rider['name']."</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-assign">Assign</button>
                        </td>
                    </form>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <?php if(mysqli_num_rows($orders) == 0): ?>
            <div class="text-center py-5">
                <i class="bi bi- inbox fs-1 text-muted"></i>
                <p class="mt-2 text-muted">No pending orders at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>