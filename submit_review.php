<?php
session_start(); 
require 'mysql_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo $_POST['reviewer_name'];
    $rating= $_POST['rating'];
    $review= $_POST['review'];
    $customer_id= $_SESSION['customer_id'];
    $sql="INSERT INTO `review_platform`(`customer_id`, `review`, `star`) 
            VALUES ('$customer_id','$review','$rating')";
    $text = $conn->prepare($sql);
    $text->execute();
}
header("Location: home.php");  // Redirect to the login page
exit();
?>