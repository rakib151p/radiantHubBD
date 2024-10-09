<?php
require '../mysql_connection.php';
date_default_timezone_set('Asia/Dhaka');
$current_time = date("H:i");
$current_date = date('Y-m-d');

function getDayOfWeek($date)
{
    $timestamp = strtotime($date);
    return date("l", $timestamp);
}
$result_slot = []; // Initialize an empty array for available slots
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $shop_id = $_GET['shop_id'];
    $date = $_GET['date'];
    $worker_id = $_GET['worker_id'];
    $item_id = $_GET['item_id'];
    $result_slot[] =$date ;
    // Return empty array for past dates
    if ($date < $current_date) {
        echo json_encode($result_slot);
        exit;
    }
    $day = getDayOfWeek($date);
    // SQL query to fetch available slots by excluding all booked slots
    $sql = "WITH RECURSIVE HourlySlots AS (
                        SELECT 
                            `shop_id`,
                            `day_of_week`,
                            `start_time` AS slot_start,
                            ADDTIME(`start_time`, '01:00:00') AS slot_end,
                            `end_time`
                        FROM 
                            `barber_schedule`
                        WHERE 
                            `shop_id` = '$shop_id'
                        UNION ALL
                        SELECT 
                            `shop_id`,
                            `day_of_week`,
                            ADDTIME(slot_start, '01:00:00'),
                            ADDTIME(slot_end, '01:00:00'),
                            `end_time`
                        FROM 
                            HourlySlots
                        WHERE 
                            slot_end < `end_time` 
                    )
                    SELECT 
                        `day_of_week`, 
                        CONCAT(TIME_FORMAT(slot_start, '%H:%i')) AS available_slots
                    FROM 
                        HourlySlots
                    WHERE 
                        `day_of_week` = '$day'
                        AND TIME_FORMAT(slot_start, '%H:%i') NOT IN (
                            SELECT TIME_FORMAT(booking_time, '%H:%i') 
                            FROM bookings 
                            WHERE date = '$date' AND status='pending' AND worker_id='$worker_id'
                        )AND (
                            ('$date' != CURDATE()) OR (slot_start > NOW())
                        )
                    ORDER BY 
                        `day_of_week`, `slot_start`";
    $result = mysqli_query($conn, $sql);
    $result_slot = [];
    while ($row = $result->fetch_assoc()) {
        $result_slot[] = $row['available_slots']; // Available time slots
    }
}

// sort($result_slot);
header('Content-Type: application/json');
echo json_encode($result_slot);
?>