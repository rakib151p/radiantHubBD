<?php
// update_status.php
include '../mysql_connection.php';
session_start();
header('Content-Type: application/json');

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['booking_id'])) {
    $booking_id = intval($data['booking_id']);

    // Optional: Verify that the booking belongs to the current shop
    $shop_id = $_SESSION['shop_id'];
    $sql_verify = "SELECT * FROM bookings WHERE id = '$booking_id'";
    $result_verify = mysqli_query($conn, $sql_verify);

    if (mysqli_num_rows($result_verify) > 0) {
        // Update the status to 'completed'
        $sql_update = "UPDATE bookings 
                        SET shop_end = 1 
                        WHERE id = '$booking_id'
                        AND NOW() > CONCAT(date, ' ', booking_time)";
        if (mysqli_query($conn, $sql_update)) {
            if (mysqli_affected_rows($conn) > 0) {
                $sql_update_again = "UPDATE bookings 
                        SET status = 'completed' 
                        WHERE id = '$booking_id' AND customer_end=1";
                if (mysqli_query($conn, $sql_update_again)) {
                    // echo json_encode(['success' => true]);
                }
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'You cant change it now..']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update status.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Booking not found or unauthorized.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>