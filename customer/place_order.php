<?php
include('../config/db.php');
session_start();

if(isset($_GET['id'])){
    $c_id = $_SESSION['user_id'];
    $price = $_GET['price'];

    $q = "INSERT INTO orders (customer_id, total_amount, status) VALUES ('$c_id', '$price', 'Pending')";
    if(mysqli_query($conn, $q)){
        echo "<script>alert('Order Placed Successfully!'); window.location='menu.php';</script>";
    }
}
?>