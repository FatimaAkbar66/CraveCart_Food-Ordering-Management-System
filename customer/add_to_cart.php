<?php
session_start();
if(isset($_GET['id'])){
    $pid = $_GET['id'];
    
    // Agar cart pehle se nahi bani to naye array banao
    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] = array();
    }

    // Item ko cart mein add karein
    if(!in_array($pid, $_SESSION['cart'])){
        array_push($_SESSION['cart'], $pid);
    }

    header("Location: cart.php");
}
?>