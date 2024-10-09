<?php
// report_booking.php
include '../mysql_connection.php';
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if(isset($data['booking_id']) && isset($data['reason'])) {
    $booking_id = intval($data['booking_id']);
    $reason = mysqli_real_escape_string($conn, trim($data['reason']));
    $shop_id = $_SESSION['shop_id'];
    // $user_id = $_SESSION['user_id'];
    $sql_verify = "SELECT * FROM bookings WHERE id = '$booking_id' AND shop_id = '$shop_id'";
    $result_verify = mysqli_query($conn, $sql_verify);

    if(mysqli_num_rows($result_verify) > 0){
        // Insert the report into reports table
        $sql_insert = "INSERT INTO report_bookings (booking_id, reason, reported_at) 
                       VALUES ('$booking_id','$reason', NOW())";
        if(mysqli_query($conn, $sql_insert)){
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to submit report.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Booking not found or unauthorized.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
