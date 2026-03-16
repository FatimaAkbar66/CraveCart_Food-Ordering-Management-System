<?php 

include('../config/db.php'); 
session_start(); 

// Cart count check (Session se)
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grapeslab - Fresh Food Delivery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --olive-primary: #5b7c5c; --olive-dark: #425e43ff; }
        body { font-family: 'Poppins', sans-serif; background-color: #fcfdfc; color: #333; scroll-behavior: smooth; }
        
        /* Navigation Bar */
        .navbar { padding: 15px 0; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.03); }
        .navbar-brand { font-weight: 700; font-size: 1.5rem; color: #1a1a1a !important; }
        .nav-link { color: #333 !important; font-weight: 500; margin: 0 15px; transition: 0.3s; }
        .nav-link:hover { color: var(--olive-primary) !important; }
        
        /* Icons */
        .nav-icons .bi { font-size: 1.4rem; cursor: pointer; color: #333; transition: 0.3s; }
        .cart-icon-btn { background: #c8d8c6ff; color: white !important; padding: 10px; border-radius: 50%; position: relative; display: inline-flex; align-items: center; justify-content: center; width: 45px; height: 45px; }
        .badge-cart { position: absolute; top: -5px; right: -5px; background: var(--olive-primary); font-size: 0.7rem; color: white; border: 2px solid white; }

        /* Hero Section */
        .hero-title { font-size: 4.5rem; font-weight: 800; line-height: 1.1; }
        .btn-success-grapes { background-color: var(--olive-primary); border: none; border-radius: 50px; padding: 14px 40px; color: white; transition: 0.3s; text-decoration: none; display: inline-block; font-weight: 600; }
        .btn-success-grapes:hover { background-color: var(--olive-dark); transform: translateY(-3px); color: white; box-shadow: 0 10px 20px rgba(91, 124, 92, 0.2); }
        
        /* Services Section */
        .service-card { border-radius: 25px; border: none; transition: 0.3s; padding: 40px; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.02); height: 100%; }
        .service-card:hover { transform: translateY(-10px); background: var(--olive-primary); color: white !important; }
        .service-card i { font-size: 3rem; color: var(--olive-primary); }
        .service-card:hover i, .service-card:hover p { color: white !important; }

        /* Testimonial Section */
        .testimonial-card { background: white; border-radius: 25px; padding: 35px; border: none; box-shadow: 0 15px 45px rgba(0,0,0,0.04); }
        .customer-img { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; border: 3px solid var(--olive-primary); }
        .rating { color: #ffc107; }

        /* Footer */
        .footer { background: #1a1a1a; color: white; padding: 80px 0 30px; border-radius: 60px 60px 0 0; margin-top: 100px; }
        .footer-link { color: #aaa; text-decoration: none; display: block; margin-bottom: 12px; transition: 0.3s; font-size: 0.9rem; }
        .footer-link:hover { color: var(--olive-primary); padding-left: 5px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="bi bi-flower1 text-success"></i> CraveCart</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link fw-bold" style="color:var(--olive-primary) !important;" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="menu.php">Browse Menu</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#feedback">Feedback</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
            </ul>
            <div class="nav-icons d-flex align-items-center gap-3">
                <a href="cart.php" class="text-decoration-none">
                    <span class="cart-icon-btn">
                        <i class="bi bi-bag"></i>
                        <?php if($cart_count > 0): ?><span class="badge badge-cart rounded-pill"><?php echo $cart_count; ?></span><?php endif; ?>
                    </span>
                </a>
                
                <div class="dropdown">
                    <i class="bi bi-person-circle ms-2" id="userMenu" data-bs-toggle="dropdown" style="font-size:1.8rem; cursor:pointer;"></i>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 p-2" style="border-radius:15px;">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <li class="px-3 py-2 fw-bold text-muted small">Account: #<?php echo $_SESSION['user_id']; ?></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item rounded-3" href="profile.php">My Profile</a></li>
                            <li><a class="dropdown-item rounded-3 text-danger" href="../logout.php">Logout</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item rounded-3" href="../login.php">Login</a></li>
                            <li><a class="dropdown-item rounded-3 fw-bold" href="../signup.php" style="color:var(--olive-primary)">Sign Up</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<section class="container py-5">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <h1 class="hero-title mb-4">Be The Fastest <br> In Delivering <br> <span style="color: var(--olive-primary);">Your Food</span></h1>
            <p class="text-muted mb-5 fs-5">Our job is to fill your tummy with delicious food with fast and free delivery.</p>
            <a href="menu.php" class="btn-success-grapes shadow-lg">Order Now</a>
        </div>
        <div class="col-lg-6 text-center">
            <img src="../assets/images/main-dish.jpg" class="img-fluid rounded-circle shadow-lg" style="max-width: 85%; border: 15px solid white;" alt="Main Dish">
        </div>
    </div>
</section>

<section id="services" class="container py-5 mt-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold display-5">Our Best <span style="color: var(--olive-primary);">Services</span></h2>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="service-card text-center">
                <i class="bi bi-truck mb-3"></i>
                <h4 class="fw-bold">Free Delivery</h4>
                <p class="text-muted">No extra cost for your happiness. We deliver everywhere for free.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="service-card text-center">
                <i class="bi bi-clock-history mb-3"></i>
                <h4 class="fw-bold">Fastest Service</h4>
                <p class="text-muted">Hungry? Don't worry, we reach your doorstep in under 30 mins.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="service-card text-center">
                <i class="bi bi-patch-check mb-3"></i>
                <h4 class="fw-bold">Best Quality</h4>
                <p class="text-muted">Only fresh and organic ingredients from top-rated kitchens.</p>
            </div>
        </div>
    </div>
</section>

<section id="feedback" class="container py-5 mt-5">
    <div class="row align-items-center">
        <div class="col-lg-5 mb-5 mb-lg-0 text-center">
            <img src="../assets/images/customer.jpg" class="img-fluid rounded-4 shadow-lg" alt="Happy Customer">
        </div>
        <div class="col-lg-6 offset-lg-1">
            <h2 class="fw-bold mb-4 display-6">Our Lovely Customers <br> Love Our Food</h2>
            <div class="testimonial-card">
                <p class="fs-5 text-muted mb-4 italic">"CraveCart is the best! The food is always fresh and the delivery is incredibly fast. I love the organic ingredients they use."</p>
                <div class="d-flex align-items-center mt-4">
                    <img src="../assets/images/user-thumb.jpg" class="customer-img me-3">
                    <div>
                        <h6 class="mb-0 fw-bold">Courtney Henry</h6>
                        <small class="text-muted">Lahore, Pakistan</small>
                        <div class="rating mt-1">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-5">
                <a href="#" class="navbar-brand text-white mb-4 d-block" style="font-size: 2rem;">CraveCart</a>
                <p style="color: #aaa;">Bringing the best food from the best restaurants directly to your doorstep with love and care.</p>
                <div class="d-flex gap-3 mt-4">
                    <a href="#" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white fs-4"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6 mb-4">
                <h6 class="fw-bold mb-4">Explore</h6>
                <a href="menu.php" class="footer-link">Browse Menu</a>
                <a href="#services" class="footer-link">Services</a>
                <a href="#feedback" class="footer-link">Testimonials</a>
            </div>
            <div class="col-lg-3 col-6 mb-4">
                <h6 class="fw-bold mb-4">Support</h6>
                <a href="#" class="footer-link">Account Center</a>
                <a href="#" class="footer-link">Contact Us</a>
                <a href="#" class="footer-link">Privacy Policy</a>
            </div>
            <div class="col-lg-3 mb-4">
                <h6 class="fw-bold mb-4">Newsletter</h6>
                <div class="input-group">
                    <input type="text" class="form-control bg-dark border-0 text-white shadow-none" placeholder="Enter Email">
                    <button class="btn btn-success" style="background:var(--olive-primary); border:none;">Go</button>
                </div>
            </div>
        </div>
        <hr class="mt-5 border-secondary">
        <p class="text-center text-muted small mt-4 mb-0">&copy; 2026 Grapeslab Food. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>