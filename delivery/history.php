<?php 
include('../config/db.php');
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'delivery'){
    header("Location: ../login.php");
    exit();
}

$rider_id = $_SESSION['user_id'];

// Sirf wo orders jo 'Delivered' ho chuke hain
$query = "SELECT orders.*, users.name as customer_name, users.address 
          FROM orders 
          JOIN users ON orders.customer_id = users.id 
          WHERE orders.delivery_boy_id = '$rider_id' AND orders.status = 'Delivered'
          ORDER BY orders.id DESC";
$history = mysqli_query($conn, $query);
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
        .history-card { background: white; border-radius: 15px; padding: 20px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="d-flex align-items-center mb-5 px-2"><i class="bi bi-bicycle text-success fs-3 me-2"></i><span class="fs-4 fw-bold">Rider Panel</span></div>
    <a href="tasks.php" class="nav-link"><i class="bi bi-list-check"></i> My Tasks</a>
    <a href="history.php" class="nav-link active"><i class="bi bi-clock-history"></i> History</a>
    <a href="../logout.php" class="nav-link text-danger mt-5"><i class="bi bi-box-arrow-left"></i> Logout</a>
</div>

<div class="main-content">
    <h2 class="fw-bold mb-4">Delivery History</h2>
    <div class="history-card">
        <table class="table align-middle">
            <thead class="text-muted">
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Address</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($history)) { ?>
                <tr>
                    <td>#ORD-<?php echo $row['id']; ?></td>
                    <td class="fw-bold"><?php echo $row['customer_name']; ?></td>
                    <td class="small"><?php echo $row['address']; ?></td>
                    <td>Rs. <?php echo number_format($row['total_amount']); ?></td>
                    <td><span class="badge bg-success rounded-pill px-3">Delivered</span></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php if(mysqli_num_rows($history) == 0) echo "<p class='text-center text-muted'>No history found.</p>"; ?>
    </div>
</div>

</body>
</html>