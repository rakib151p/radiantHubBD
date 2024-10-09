<?php
include '../mysql_connection.php';
session_start(); // Start the session if not already started
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $message = $data['message'] ?? '';
    $shop_id = $data['shop_id'] ?? '';
    // Validate the data
    if (!empty($message) && !empty($shop_id)) {
        $customer_id = $_SESSION['customer_id'];
        // // Here, add your code to save the message to the database
        $sql = "INSERT INTO message_table (shop_id, customer_id, message, date_and_time,from_whom,customer_status) VALUES ('$shop_id', '$customer_id', '$message', NOW(),'customer',1)";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Message sent successfully!',
            ];
        } else {
            $response = [
                'success' => false,
                'error' => 'Message or Shop ID is empty.',
            ];
        }
        // Prepare a success response
        $response = [
                    'success' => true,
                    'error' => 'Message or Shop ID is empty.',
                ];

    } else {
        // Prepare an error response
        $response = [
            'success' => false,
            'error' => 'Message or Shop ID is empty 12.',
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Terminate the script after sending the response
}
$response = [
    'success' => false,
    'error' => 'Invalid request method.',
];
header('Content-Type: application/json');
echo json_encode($response);
exit;

?>