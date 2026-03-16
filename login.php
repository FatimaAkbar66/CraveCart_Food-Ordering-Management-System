<?php
include('config/db.php');
session_start();

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); 

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    
    if(mysqli_num_rows($result) > 0){
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_name'] = $user['name'];

        if($user['role'] == 'admin'){
            header("Location: admin/dashboard.php");
        } elseif($user['role'] == 'delivery'){
            header("Location: delivery/tasks.php");
        } else {
            header("Location: customer/index.php");
        }
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Grapeslab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --olive-primary: #5b7c5c; }
        body { 
            /* Fast Food Aesthetic Background */
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), 
                        url('https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=1470&auto=format&fit=crop');
            background-size: cover; background-position: center;
            font-family: 'Poppins', sans-serif; height: 100vh; display: flex; 
            align-items: center; justify-content: center; margin: 0;
        }
        .login-card { 
            background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);
            padding: 35px; border-radius: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
            width: 100%; max-width: 380px; border: 1px solid rgba(255,255,255,0.3);
        }
        .brand-logo { color: var(--olive-primary); font-weight: 700; font-size: 1.7rem; text-align: center; }
        .form-control { border-radius: 12px; padding: 10px 15px; border: 1px solid #ddd; font-size: 0.9rem; }
        .btn-login { 
            background: var(--olive-primary); color: white; border-radius: 50px; 
            padding: 12px; width: 100%; font-weight: 600; border: none; margin-top: 10px;
        }
        .btn-login:hover { background: #4a664b; transform: translateY(-2px); }
        .footer-text { text-align: center; margin-top: 20px; font-size: 0.85rem; color: #444; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="brand-logo mb-1"><i class="bi bi-flower1"></i> CraveCart</div>
    <h5 class="text-center fw-bold mb-4">Login to Account</h5>
    
    <?php if(isset($error)): ?>
        <div class="alert alert-danger py-2 small text-center rounded-3"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label small fw-bold text-muted">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
        </div>
        <div class="mb-4">
            <label class="form-label small fw-bold text-muted">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button name="login" class="btn btn-login shadow-sm">Sign In</button>
    </form>

    <div class="footer-text">
        Don't have an account? <a href="signup.php" style="color:var(--olive-primary); font-weight:700; text-decoration:none;">Create One</a>
    </div>
</div>
</body>
</html>