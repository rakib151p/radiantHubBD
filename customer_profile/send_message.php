<?php
require '../mysql_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'], $_POST['shop_id'])) {
    $customer_id = $_SESSION['customer_id'];
    $shop_id = $_POST['shop_id'];
    $message = $_POST['message'];

    // Insert message into the database
    $stmt = $conn->prepare("INSERT INTO message_table (shop_id, customer_id, message, from_whom,customer_status) VALUES (?, ?, ?, 'customer',1)");
    $stmt->bind_param('iis', $shop_id, $customer_id, $message);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send message']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
