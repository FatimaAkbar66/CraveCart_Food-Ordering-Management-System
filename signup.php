<?php
include('config/db.php');

if(isset($_POST['register'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = $_POST['role']; 

    $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if(mysqli_num_rows($check_email) > 0){
        $error = "This email is already registered!";
    } else {
        $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
        if(mysqli_query($conn, $query)){
            echo "<script>alert('Account Created Successfully! Welcome to CraveCart.'); window.location='login.php';</script>";
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - CraveCart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --olive-primary: #5b7c5c; }
        body { 
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), 
                        url('https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=1470&auto=format&fit=crop');
            background-size: cover; background-position: center;
            font-family: 'Poppins', sans-serif; height: 100vh; display: flex; 
            align-items: center; justify-content: center; margin: 0;
        }
        .signup-card { 
            background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(10px);
            padding: 30px 35px; border-radius: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
            width: 100%; max-width: 400px;
        }
        .brand-logo { color: var(--olive-primary); font-weight: 700; font-size: 1.7rem; text-align: center; }
        .form-control, .form-select { border-radius: 12px; padding: 10px 15px; font-size: 0.9rem; }
        .btn-register { 
            background: var(--olive-primary); color: white; border-radius: 50px; 
            padding: 12px; width: 100%; font-weight: 600; border: none; margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="signup-card">
    <div class="brand-logo mb-1"><i class="bi bi-flower1"></i> CraveCart</div>
    <h5 class="text-center fw-bold mb-4">Create Account</h5>
    
    <?php if(isset($error)): ?>
        <div class="alert alert-danger py-2 small text-center rounded-3 mb-3"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-2">
            <label class="small fw-bold text-muted">Full Name</label>
            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
        </div>
        <div class="mb-2">
            <label class="small fw-bold text-muted">Email</label>
            <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
        </div>
        <div class="mb-2">
            <label class="small fw-bold text-muted">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <div class="mb-3">
            <label class="small fw-bold text-muted">Role</label>
            <select name="role" class="form-select" required>
                <option value="customer">Customer</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button name="register" class="btn btn-register shadow-sm">Sign Up</button>
    </form>
    <div class="text-center mt-3 small">
        Already have an account? <a href="login.php" style="color:var(--olive-primary); font-weight:700; text-decoration:none;">Log In</a>
    </div>
</div>
</body>
</html>