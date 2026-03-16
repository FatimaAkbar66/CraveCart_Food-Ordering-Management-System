<?php
include('../config/db.php');
session_start();

if(isset($_POST['order_id']) && isset($_POST['rider_id'])){
    $oid = $_POST['order_id'];
    $rid = $_POST['rider_id'];

    // Update status to 'Picked Up' and link the rider
    $q = "UPDATE orders SET delivery_boy_id = '$rid', status = 'Picked Up' WHERE id = '$oid'";
    
    if(mysqli_query($conn, $q)){
        echo "<script>alert('Order assigned to rider!'); window.location='manage_orders.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>