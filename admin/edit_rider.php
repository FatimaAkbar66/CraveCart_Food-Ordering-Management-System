<?php 
include('../config/db.php');
$id = $_GET['id'];
$res = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
$r = mysqli_fetch_assoc($res);

if(isset($_POST['update'])){
    $n = $_POST['name']; $e = $_POST['email']; $p = $_POST['phone'];
    mysqli_query($conn, "UPDATE users SET name='$n', email='$e', phone='$p' WHERE id='$id'");
    header("Location: manage_riders.php");
}
?>
<!DOCTYPE html>
<html>
<head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light p-5">
    <div class="container card p-4 shadow-sm" style="max-width: 500px; border-radius: 15px;">
        <h4 class="fw-bold mb-4">Edit Rider Info</h4>
        <form method="POST">
            <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" value="<?php echo $r['name']; ?>"></div>
            <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="<?php echo $r['email']; ?>"></div>
            <div class="mb-3"><label>Phone</label><input type="text" name="phone" class="form-control" value="<?php echo $r['phone']; ?>"></div>
            <button name="update" class="btn btn-dark w-100 rounded-pill">Update Details</button>
            <a href="manage_riders.php" class="btn btn-link w-100 text-muted mt-2">Cancel</a>
        </form>
    </div>
</body>
</html>