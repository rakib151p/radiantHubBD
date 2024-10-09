<?php
require '../../mysql_connection.php';
session_start();
// Ensure the session variables are set
if (
    !isset(
    $_SESSION['first-name'],
    $_SESSION['last-name'],
    $_SESSION['email'],
    $_SESSION['password'],
    $_SESSION['mobile-number'],
    $_SESSION['gender'],
    $_SESSION['district'],
    $_SESSION['upazilla'],
    $_SESSION['area'],
    $_SESSION['latitude'],
    $_SESSION['longitude']
)
) {
    die("Required session variables are missing.");
}

$first_name = $_SESSION['first-name'];
$last_name = $_SESSION['last-name'];
$email = $_SESSION['email'];
$password = $_SESSION['password'];
$mobile = $_SESSION['mobile-number'];
$gender = $_SESSION['gender'];
$district = $_SESSION['district'];
$upazilla = $_SESSION['upazilla'];
$area = $_SESSION['area'];
$landmarks = $_SESSION['landmarks'];
$latitude = $_SESSION['latitude'];
$longitude = $_SESSION['longitude'];

// Fetch the max customer_id available
$sql = 'SELECT MAX(customer_id) as customer_id FROM customer';
$result = mysqli_query($conn, $sql);
$customer_id = 1;
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $customer_id = $row['customer_id'] + 1;
}

// Prepare and bind
$sql = "INSERT INTO customer (customer_id, first_name, last_name, mobile_number, email, gender, password, district, upazilla, area, latitude, longitude) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssssssssss", $customer_id, $first_name, $last_name, $mobile, $email, $gender, $password, $district, $upazilla, $area, $latitude, $longitude);

// Execute the statement
if ($stmt->execute()) {
    // Success
} else {
    // Handle the error
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

session_destroy();
header("Location: http://localhost/rakib/final1/login/login.php");
exit();
?>