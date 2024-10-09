<?php
require '../mysql_connection.php';
session_start();
$district = $_POST['district'];
$upazilla = $_POST['upazilla'];
$area = $_POST['area'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$customer_id = $_SESSION['customer_id'];
$sql = "UPDATE `customer` SET `district`='$district', `upazilla`='$upazilla', `area`='$area', `latitude`='$latitude',`longitude`='$longitude' WHERE `customer_id`='$customer_id'"; // Use the appropriate WHERE condition
if (mysqli_query($conn, $sql)) {
    // echo "<script>alert('Your details updated successfully.');</script>";
    echo "<script>
            window.onload = function() {
            alert('Successfully updated.');
            };
        </script>";
} else {
    echo "<script>
            window.onload = function() {
            alert('Error updating record: " . mysqli_error($conn) . "');
            };
        </script>";
}
?>