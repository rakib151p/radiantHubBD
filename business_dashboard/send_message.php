<?php
// Include the database connection
include '../mysql_connection.php';

// Start the session to use session variables
session_start();

// Check if the required fields are set and not empty
if (isset($_POST['message']) && isset($_POST['shop_id']) && isset($_POST['customer_id'])) {
    // Sanitize the inputs to avoid SQL injection
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $shop_id = mysqli_real_escape_string($conn, $_POST['shop_id']);
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);

    // Determine the sender (for example, 'shop' or 'customer')
    // Assuming this is a message from the shop
    $from_whom = 'shop';

    // Prepare the SQL query to insert the new message into the database
    $sql = "INSERT INTO message_table (shop_id, customer_id, message, from_whom, date_and_time) 
            VALUES ('$shop_id', '$customer_id', '$message', '$from_whom', NOW())";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        // Return a success message or simply exit
        echo 'Message sent successfully.';
    } else {
        // Handle the error
        echo 'Error: ' . mysqli_error($conn);
    }
} else {
    // Handle the case when required fields are not set
    echo 'Error: Missing required fields.';
}

// Close the database connection
mysqli_close($conn);
?>
