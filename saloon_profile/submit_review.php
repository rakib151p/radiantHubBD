<?php
session_start();
require '../mysql_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reviewer_name = $_POST['reviewer_name'];
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $review = mysqli_real_escape_string($conn, $_POST['review']);
    $shop_id = mysqli_real_escape_string($conn, $_POST['shop_id']);
    $customer_id = mysqli_real_escape_string($conn, $_SESSION['customer_id']);

    $sql = "INSERT INTO `review_shop`(`shop_id`, `customer_id`, `review`, `star`) 
            VALUES ('$shop_id', '$customer_id', '$review', '$rating')";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error inserting review: " . mysqli_error($conn));
    }
    $sql_review_count = "SELECT count(*) as total FROM review_shop where shop_id='$shop_id'";
    $result_review_count = mysqli_query($conn, $sql_review_count);
    $total_review = 0;
    if ($result_review_count) {
        $total_review_row = $result_review_count->fetch_assoc();
        $total_review = $total_review_row['total'];
    }
    $sql = "SELECT shop_rating,shop_customer_count FROM barber_shop WHERE shop_id='$shop_id'";
    $result = $result = mysqli_query($conn, $sql);
    $current_rating = $total_customer = 0;
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $current_rating = $row['shop_rating'];
            // $total_customer=$row['shop_customer_count'];
        }
    }
    $total_review--;
    echo $current_rating.' rating<br>'.$total_review;
    $current_rating = ($current_rating * $total_review + $rating) / ($total_review + 1);
    // $current_rating = ceil($current_rating * 100) / 100;
    $sql = "UPDATE barber_shop SET `shop_rating` = '$current_rating',`shop_customer_count` = `shop_customer_count` + 1 WHERE `shop_id`='$shop_id'";
    echo $current_rating . ' ' . $total_customer;
    if (mysqli_query($conn, $sql)) {
        echo "Shop rating updated successfully to: " . round($current_rating, 2);
    } else {
        echo "Error updating rating: " . mysqli_error($conn);
    }

    header("Location: dashboard.php?shop_id=$shop_id");  // Redirect after successful insert
    exit();
}
?>