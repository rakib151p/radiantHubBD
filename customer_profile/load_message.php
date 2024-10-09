<?php
require '../mysql_connection.php';
session_start();

if (isset($_GET['shop_id'])) {
    $shop_id = $_GET['shop_id'];
    $customer_id = $_SESSION['customer_id'];

    // Fetch messages between the customer and the shop
    $stmt = $conn->prepare("SELECT * FROM message_table WHERE shop_id = ? AND customer_id = ? ORDER BY date_and_time ASC");
    $stmt->bind_param('ii', $shop_id, $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    echo json_encode($messages);
}
?>
