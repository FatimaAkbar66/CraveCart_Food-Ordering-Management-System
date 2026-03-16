<?php 
include('../config/db.php');
session_start();

// 1. Check Admin Login
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

// 2. Get Item Details
if(isset($_GET['id'])){
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $res = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");
    
    if(mysqli_num_rows($res) > 0){
        $p = mysqli_fetch_assoc($res);
    } else {
        header("Location: manage_menu.php");
        exit();
    }
} else {
    header("Location: manage_menu.php");
    exit();
}

// 3. Update Logic
if(isset($_POST['update'])){
    $n = mysqli_real_escape_string($conn, $_POST['name']);
    $r = mysqli_real_escape_string($conn, $_POST['restaurant']);
    $pr = $_POST['price'];
    $rt = $_POST['rating'];
    $d = mysqli_real_escape_string($conn, $_POST['desc']);
    
    // Image Handling
    if(!empty($_FILES["image"]["name"])){
        $img_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_dir = "../images/";
        $target_file = $target_dir . $img_name;
        
        if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
            $q = "UPDATE products SET name='$n', restaurant='$r', price='$pr', rating='$rt', image='$img_name', description='$d' WHERE id='$id'";
        } else {
            // Agar upload fail ho to purani image hi rehne dein
            $q = "UPDATE products SET name='$n', restaurant='$r', price='$pr', rating='$rt', description='$d' WHERE id='$id'";
        }
    } else {
        // Update without changing image
        $q = "UPDATE products SET name='$n', restaurant='$r', price='$pr', rating='$rt', description='$d' WHERE id='$id'";
    }

    if(mysqli_query($conn, $q)){
        echo "<script>alert('Item Updated Successfully!'); window.location='manage_menu.php';</script>";
    } else {
        echo "<script>alert('Update Failed: " . mysqli_error($conn) . "');</script>";
    }
}

// SMART IMAGE PATH LOGIC (For Preview)
$image_path = "../images/" . $p['image'];
if (!file_exists($image_path) || empty($p['image'])) {
    $image_path = "../assets/images/" . $p['image'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Menu - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f4; font-family: 'Poppins', sans-serif; }
        .edit-container { max-width: 700px; margin: 40px auto; }
        .edit-card { background: white; border-radius: 25px; padding: 35px; box-shadow: 0 15px 40px rgba(0,0,0,0.08); border:none; }
        .current-img-preview { width: 140px; height: 140px; object-fit: cover; border-radius: 20px; border: 5px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .form-control { border-radius: 12px; padding: 12px; border: 1px solid #ebf0eb; background: #fcfdfc; }
        .btn-update { background: #5b7c5c; color: white; border: none; border-radius: 50px; padding: 14px; transition: 0.3s; }
        .btn-update:hover { background: #4a664b; transform: translateY(-2px); color: white; }
    </style>
</head>
<body>

<div class="container edit-container">
    <div class="edit-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold m-0" style="color:#2c3e2d;">Edit Menu Item</h3>
                <p class="text-muted small">Update food details and pricing</p>
            </div>
            <a href="manage_menu.php" class="btn btn-outline-secondary rounded-pill px-4 btn-sm">Cancel</a>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="text-center mb-4 bg-light p-3 rounded-4">
                <p class="text-muted small mb-2 fw-bold">Current Display Image</p>
                <img src="<?php echo $image_path; ?>" class="current-img-preview" onerror="this.src='https://placehold.co/150'">
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">Food Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $p['name']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">Restaurant</label>
                    <input type="text" name="restaurant" class="form-control" value="<?php echo $p['restaurant']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">Price (Rs.)</label>
                    <input type="number" name="price" class="form-control" value="<?php echo $p['price']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">Rating (1-5)</label>
                    <input type="number" step="0.1" max="5" name="rating" class="form-control" value="<?php echo $p['rating']; ?>">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold text-muted">Description</label>
                    <textarea name="desc" class="form-control" rows="3"><?php echo $p['description']; ?></textarea>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label small fw-bold text-muted">Replace Image (Optional)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="col-12">
                    <button name="update" class="btn btn-update w-100 fw-bold shadow-sm">
                        <i class="bi bi-check2-circle me-2"></i> Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

</body>
</html>