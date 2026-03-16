<?php
$conn = mysqli_connect("localhost", "root", "", "food_db");
if (!$conn) { die("Connection Failed: " . mysqli_connect_error()); }
?>