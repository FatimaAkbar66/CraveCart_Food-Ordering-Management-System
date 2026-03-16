<?php 
include('../config/db.php'); 
session_start(); 

// Check Admin Login
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

// 1. ADD ITEM LOGIC
if(isset($_POST['add'])){
    $n = mysqli_real_escape_string($conn, $_POST['name']);
    $r = mysqli_real_escape_string($conn, $_POST['restaurant']);
    $p = $_POST['price'];
    $rt = $_POST['rating'];
    $d = mysqli_real_escape_string($conn, $_POST['desc']);

    // Image Upload Logic
    $target_dir = "../images/";

    // Check agar folder nahi hai to bana do
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = $_FILES["image"]["name"];
    $file_tmp = $_FILES["image"]["tmp_name"];
    
    // Unique name taake images mix na hon
    $new_img_name = time() . "_" . $file_name;
    $target_file = $target_dir . $new_img_name;

    if(move_uploaded_file($file_tmp, $target_file)){
        $query = "INSERT INTO products (name, restaurant, price, rating, image, description) 
                  VALUES ('$n', '$r', '$p', '$rt', '$new_img_name', '$d')";
        mysqli_query($conn, $query);
        echo "<script>alert('Item Added Successfully!'); window.location='manage_menu.php';</script>";
    } else {
        echo "<script>alert('Error: Folder permissions ka masla hai ya path ghalat hai.');</script>";
    }
}

// 2. DELETE LOGIC
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    mysqli_query($conn, "DELETE FROM products WHERE id='$id'");
    header("Location: manage_menu.php");
}

// 3. FETCH ALL ITEMS
$items = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
        .prod-img { width: 60px; height: 60px; object-fit: cover; border-radius: 10px; border: 1px solid #ddd; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="d-flex align-items-center mb-5 px-2"><i class="bi bi-flower1 text-success fs-3 me-2"></i><span class="fs-4 fw-bold">CraveCart</span></div>
    <a href="dashboard.php" class="nav-link"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="manage_menu.php" class="nav-link active"><i class="bi bi-box-seam"></i> Manage Menu</a>
    <a href="manage_orders.php" class="nav-link"><i class="bi bi-cart-check"></i> Manage Orders</a>
    <a href="manage_riders.php" class="nav-link"><i class="bi bi-bicycle"></i> Manage Riders</a>
    <a href="../logout.php" class="nav-link text-danger mt-5"><i class="bi bi-box-arrow-left"></i> Logout</a>
</div>

<div class="main-content">
    <h2 class="fw-bold mb-4">Restaurant Menu Manager</h2>
    
    <div class="admin-card mb-5">
        <h5 class="fw-bold mb-4">Add New Food Item</h5>
        <form method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Food Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Cheese Pizza" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Restaurant Name</label>
                    <input type="text" name="restaurant" class="form-control" placeholder="e.g. Pizza Hut" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Price (Rs.)</label>
                    <input type="number" name="price" class="form-control" placeholder="500" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Rating</label>
                    <input type="number" step="0.1" name="rating" class="form-control" placeholder="4.5">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Description</label>
                    <textarea name="desc" class="form-control" rows="1" placeholder="Short description..."></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Select Dish Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button name="add" class="btn btn-success w-100 rounded-pill" style="background:#5b7c5c; border:none; height:40px;">Save Item</button>
                </div>
            </div>
        </form>
    </div>

    <div class="admin-card">
        <h5 class="fw-bold mb-4">Active Menu Items</h5>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Dish</th>
                        <th>Details</th>
                        <th>Restaurant</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($items)): ?>
                    <tr>
                        <td>
                            <img src="../images/<?php echo $row['image']; ?>" class="prod-img">
                        </td>
                        <td>
                            <div class="fw-bold"><?php echo $row['name']; ?></div>
                            <small class="text-muted"><?php echo substr($row['description'], 0, 40); ?>...</small>
                        </td>
                        <td><?php echo $row['restaurant']; ?></td>
                        <td class="fw-bold text-success">Rs. <?php echo number_format($row['price']); ?></td>
                        <td>
                            <a href="edit_menu.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary border-0"><i class="bi bi-pencil-square"></i></a>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Pakka delete karna hai?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>