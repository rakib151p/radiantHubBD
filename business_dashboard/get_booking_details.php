<?php
// get_booking_details.php
include '../mysql_connection.php';
session_start();
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['booking_id'])) {
    $booking_id = intval($data['booking_id']);
    $shop_id = $_SESSION['shop_id'];
    
    //SQL query to fetch all details about a booking
    $sql = "SELECT CONCAT(customer.first_name, ' ', customer.last_name) AS full_name,
                item_table.item_name AS item_name,
                FORMAT(shop_service_table.item_price, 2) AS item_price,
                DATE_FORMAT(bookings.date, '%W, %d %M %Y') AS booked_date,
                DATE_FORMAT(STR_TO_DATE(bookings.booking_time, '%H:%i:%s'), '%h:%i %p') AS booked_time,
                CONCAT('Booked on: ', DATE_FORMAT(bookings.when_booked, '%W, %d %M %Y at %h:%i %p')) AS booking_time,
                CASE 
                    WHEN bookings.status = 'pending' THEN 'Pending Confirmation'
                    WHEN bookings.status = 'completed' THEN 'completed'
                    ELSE 'peding'
                END AS status
            FROM `bookings` 
            JOIN customer 
                ON bookings.customer_id = customer.customer_id 
            JOIN item_table 
                ON bookings.item_id = item_table.item_id 
            JOIN shop_service_table 
                ON bookings.item_id = shop_service_table.item_id 
                AND bookings.shop_id = shop_service_table.shop_id 
            WHERE bookings.id = '$booking_id'
            AND bookings.status IN ('pending', 'completed','confirmed')
            ORDER BY bookings.when_booked DESC
            LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $details = mysqli_fetch_assoc($result);
        echo json_encode(['success' => true, 'details' => $details]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Booking not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>