<?php 
include('../config/db.php'); 
session_start(); 

// Cart count check
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Search Logic
$search_query = "";
if(isset($_GET['search'])){
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM products WHERE name LIKE '%$search_query%' OR restaurant LIKE '%$search_query%' ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM products ORDER BY id DESC";
}
$res = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Grapeslab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --olive-primary: #5b7c5c; --olive-dark: #4a664b; --bg-light: #f4f7f4; }
        body { background-color: var(--bg-light); font-family: 'Poppins', sans-serif; }
        
        /* Navbar Styling */
        .navbar { background: white; box-shadow: 0 2px 15px rgba(0,0,0,0.05); padding: 15px 0; }
        .nav-link { color: #333 !important; font-weight: 500; margin: 0 15px; }
        .nav-link:hover { color: var(--olive-primary) !important; }
        .cart-icon-btn { background: #1a1a1a; color: white !important; padding: 10px; border-radius: 50%; position: relative; display: inline-flex; width: 42px; height: 42px; align-items: center; justify-content: center; }
        .badge-cart { position: absolute; top: -5px; right: -5px; background: var(--olive-primary); font-size: 0.7rem; border: 2px solid white; }

        /* Search Bar */
        .search-container { max-width: 600px; margin: -30px auto 50px; position: relative; z-index: 100; }
        .search-input { border-radius: 50px; padding: 15px 30px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.08); font-size: 1rem; }
        .search-btn { position: absolute; right: 10px; top: 7px; border-radius: 50px; background: var(--olive-primary); border: none; padding: 8px 25px; color: white; }

        /* Food Card Styling */
        .food-card { border: none; border-radius: 30px; transition: 0.4s; background: white; box-shadow: 0 10px 25px rgba(0,0,0,0.02); overflow: hidden; }
        .food-card:hover { transform: translateY(-12px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
        .card-img-top { height: 200px; object-fit: cover; padding: 15px; border-radius: 45px; }
        .price-tag { color: #1a1a1a; font-weight: 700; font-size: 1.25rem; }
        .btn-cart { background: var(--olive-primary); color: white; border-radius: 50px; border: none; padding: 10px 25px; font-weight: 500; transition: 0.3s; }
        .btn-cart:hover { background: var(--olive-dark); box-shadow: 0 5px 15px rgba(91, 124, 92, 0.3); color: white; }
        .rating-badge { background: rgba(255,255,255,0.95); padding: 5px 12px; border-radius: 50px; position: absolute; top: 25px; left: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); font-size: 0.8rem; font-weight: bold; z-index: 5; }

        /* Footer */
        .footer { background: #1a1a1a; color: white; padding: 80px 0 30px; border-radius: 60px 60px 0 0; margin-top: 80px; }
        .footer-link { color: #aaa; text-decoration: none; display: block; margin-bottom: 12px; font-size: 0.9rem; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-flower1 text-success"></i> CraveCart</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link fw-bold" style="color:var(--olive-primary) !important;" href="menu.php">Menu</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#feedback">Reviews</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <a href="cart.php" class="text-decoration-none">
                    <span class="cart-icon-btn">
                        <i class="bi bi-bag"></i>
                        <?php if($cart_count > 0): ?><span class="badge badge-cart rounded-pill"><?php echo $cart_count; ?></span><?php endif; ?>
                    </span>
                </a>
                <div class="dropdown">
                    <i class="bi bi-person-circle" data-bs-toggle="dropdown" style="font-size:1.8rem; cursor:pointer;"></i>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 p-2" style="border-radius:15px;">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <li><a class="dropdown-item rounded-3" href="profile.php">My Profile</a></li>
                            <li><a class="dropdown-item rounded-3 text-danger" href="../logout.php">Logout</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item rounded-3" href="../login.php">Login</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<div style="background: var(--olive-primary); padding: 80px 0 100px; text-align: center; color: white;">
    <div class="container">
        <h1 class="fw-bold display-4">Delicious Menu</h1>
        <p class="opacity-75">Discover the best food from top restaurants</p>
    </div>
</div>

<div class="container search-container">
    <form action="menu.php" method="GET">
        <div class="position-relative">
            <input type="text" name="search" class="form-control search-input" placeholder="Search for food or restaurants..." value="<?php echo $search_query; ?>">
            <button type="submit" class="search-btn fw-bold">Search</button>
        </div>
    </form>
</div>

<div class="container pb-5">
    <div class="row">
        <?php 
        if(mysqli_num_rows($res) > 0) {
            while($row = mysqli_fetch_assoc($res)) { 
                $image_path = "../images/" . $row['image'];
                if (!file_exists($image_path) || empty($row['image'])) {
                    $image_path = "../assets/images/" . $row['image'];
                }
        ?>
        <div class="col-lg-4 col-md-6 mb-5">
            <div class="card food-card h-100 position-relative">
                <div class="rating-badge">
                    <i class="bi bi-star-fill text-warning"></i> <?php echo (!empty($row['rating'])) ? $row['rating'] : '4.2'; ?>
                </div>
                
                <img src="<?php echo $image_path; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>" onerror="this.src='https://placehold.co/400x300?text=Food+Image'">
                
                <div class="card-body d-flex flex-column">
                    <div class="mb-2">
                        <span class="badge bg-light text-success rounded-pill px-3 py-2 small fw-bold">
                            <i class="bi bi-shop me-1"></i> <?php echo $row['restaurant']; ?>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-2 text-dark"><?php echo $row['name']; ?></h5>
                    <p class="text-muted small mb-4"><?php echo $row['description']; ?></p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <span class="price-tag">Rs. <?php echo number_format($row['price']); ?></span>
                        <a href="add_to_cart.php?id=<?php echo $row['id']; ?>" class="btn btn-cart">
                            <i class="bi bi-plus-lg me-1"></i> Add To Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            }
        } else {
            echo "<div class='text-center py-5'><h3 class='text-muted'>No food found matching your search.</h3></div>";
        }
        ?>
    </div>
</div>

<footer class="footer" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-5">
                <a href="#" class="navbar-brand text-white mb-4 d-block fs-2 fw-bold">CraveCart</a>
                <p style="color: #aaa;">Satisfying your cravings with the best flavors in town. Fast delivery, fresh food, and great mood.</p>
            </div>
            <div class="col-lg-2 col-6 mb-4">
                <h6 class="fw-bold mb-4 text-uppercase small" style="letter-spacing:1px;">Quick Links</h6>
                <a href="index.php" class="footer-link">Home</a>
                <a href="menu.php" class="footer-link">Menu</a>
                <a href="cart.php" class="footer-link">My Cart</a>
            </div>
            <div class="col-lg-3 col-6 mb-4">
                <h6 class="fw-bold mb-4 text-uppercase small" style="letter-spacing:1px;">Contact Info</h6>
                <p class="footer-link mb-2"><i class="bi bi-geo-alt me-2"></i> Lahore, Pakistan</p>
                <p class="footer-link mb-2"><i class="bi bi-telephone me-2"></i> +92 300 1234567</p>
                <p class="footer-link"><i class="bi bi-envelope me-2"></i> hello@grapeslab.com</p>
            </div>
            <div class="col-lg-3 mb-4 text-center text-lg-start">
                <h6 class="fw-bold mb-4 text-uppercase small" style="letter-spacing:1px;">Follow Us</h6>
                <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                    <a href="#" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white fs-4"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>
        </div>
        <hr class="mt-5 border-secondary opacity-25">
        <p class="text-center text-muted small mt-4 mb-0">&copy; 2026 CraveCart. Created with ❤️</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>