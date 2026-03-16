<?php 
include('../config/db.php');
session_start();

// 1. Add Rider Logic
if(isset($_POST['add_rider'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['password']; // In real world, use password_hash
    $phone = $_POST['phone'];
    
    $q = "INSERT INTO users (name, email, password, role, phone) VALUES ('$name', '$email', '$pass', 'delivery', '$phone')";
    mysqli_query($conn, $q);
    header("Location: manage_riders.php");
}

// 2. Delete Rider Logic
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE id='$id' AND role='delivery'");
    header("Location: manage_riders.php");
}

$riders = mysqli_query($conn, "SELECT * FROM users WHERE role='delivery'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --sidebar-bg: #111827; --main-bg: #f4f7f4; }
        body { background-color: var(--main-bg); font-family: 'Inter', sans-serif; display: flex; }
        .sidebar { width: 260px; height: 100vh; background: var(--sidebar-bg); position: fixed; color: white; padding: 20px; }
        .nav-link { color: #9ca3af; padding: 12px 15px; border-radius: 10px; margin-bottom: 5px; display: block; text-decoration: none; }
        .nav-link.active { background: #1f2937; color: white; }
        .main-content { margin-left: 260px; padding: 40px; width: 100%; }
        .admin-card { background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="d-flex align-items-center mb-5 px-2"><i class="bi bi-flower1 text-success fs-3 me-2"></i><span class="fs-4 fw-bold">CraveCart</span></div>
    <a href="dashboard.php" class="nav-link"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="manage_menu.php" class="nav-link"><i class="bi bi-box-seam"></i> Manage Menu</a>
    <a href="manage_orders.php" class="nav-link"><i class="bi bi-cart-check"></i> Manage Orders</a>
    <a href="manage_riders.php" class="nav-link active"><i class="bi bi-bicycle"></i> Manage Riders</a>
    <a href="../logout.php" class="nav-link text-danger mt-5"><i class="bi bi-box-arrow-left"></i> Logout</a>
</div>

<div class="main-content">
    <h2 class="fw-bold mb-4">Manage Delivery Team</h2>

    <div class="admin-card mb-4">
        <h5 class="fw-bold mb-3">Add New Rider</h5>
        <form method="POST" class="row g-3">
            <div class="col-md-3"><input type="text" name="name" class="form-control" placeholder="Full Name" required></div>
            <div class="col-md-3"><input type="email" name="email" class="form-control" placeholder="Email Address" required></div>
            <div class="col-md-2"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
            <div class="col-md-2"><input type="text" name="phone" class="form-control" placeholder="Phone Number" required></div>
            <div class="col-md-2"><button name="add_rider" class="btn btn-dark w-100 rounded-pill">Add Rider</button></div>
        </form>
    </div>

    <div class="admin-card">
        <table class="table align-middle">
            <thead class="text-muted">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($r = mysqli_fetch_assoc($riders)) { ?>
                <tr>
                    <td>#RID-<?php echo $r['id']; ?></td>
                    <td class="fw-bold"><?php echo $r['name']; ?></td>
                    <td><?php echo $r['email']; ?></td>
                    <td><?php echo $r['phone']; ?></td>
                    <td>
                        <a href="edit_rider.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-primary border-0"><i class="bi bi-pencil"></i></a>
                        <a href="?delete=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Delete this rider?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>