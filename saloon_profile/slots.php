<?php
// require '../mysql_connection.php';

// date_default_timezone_set('Asia/Dhaka');
// $current_time = date("H:i"); // "H" for hours (00 to 23), "i" for minutes
// echo "Current time: " . $current_time;
// echo gettype($current_time);
// $current_date = date('Y-m-d');
// echo "Current Date: " . $current_date;
// function getDayOfWeek($date)
// {
//     $timestamp = strtotime($date);
//     $dayOfWeek = date("l", $timestamp);
//     return $dayOfWeek;
// }
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $shop_id = $_POST['shop_id'];
//     $date = $_POST['date'];
//     $worker_id = $_POST['worker_id'];
//     $result_slot = [];
//     if($date<$current_date)goto x;
//     $day = getDayOfWeek($date);
//     echo '<br>'. $shop_id . ' ' . $worker_id . ' ' . $date . ' ' . $day . ' <br>';
//     $scheduleQuery = "SELECT `start_time`, `end_time` 
//                   FROM `barber_schedule` 
//                   WHERE `shop_id` = ? AND `day_of_week` = ?";
//     $stmt = $conn->prepare($scheduleQuery);
//     $stmt->bind_param("is", $shop_id, $day);
//     $stmt->execute();
//     $scheduleResult = $stmt->get_result();
//     $scheduleSlots = [];
//     if ($scheduleResult->num_rows > 0) {
//         while ($schedule = $scheduleResult->fetch_assoc()) {

//             $startTime = new DateTime($schedule['start_time']);
//             $endTime = new DateTime($schedule['end_time']);
//             while ($startTime < $endTime) {
//                 // echo $startTime->format('H:i').' '.$current_time.'<br>';
//                 if($current_date==$date&&$startTime->format('H:i')<$current_time){
//                     goto check;
//                 }
//                 $scheduleSlots[] = $startTime->format('H:i');
//                 check:
//                 $nextTime = clone $startTime;
//                 $nextTime->modify('+1 hour');
//                 $startTime->modify('+1 hour');
//             }
//         }
//     } else {
//         echo "No schedule found.";
//     }
//     echo '<br>All slot here<br>';
    
//     sort($scheduleSlots);
//     foreach ($scheduleSlots as $slot) {
//         $sql_slot = "SELECT * FROM `bookings` WHERE worker_id = ? AND booking_time = ? AND date=?";
//         $stmt = $conn->prepare($sql_slot);
//         $stmt->bind_param("iss", $worker_id, $slot,$date);
//         $stmt->execute();
//         $result = $stmt->get_result();
//         if ($result->num_rows > 0) {
//             while ($row = $result->fetch_assoc()) {
//                 echo "Booking ID: " . $row['id'] . "<br>";
//                 echo "Date: " . $row['date'] . "<br>";
//                 echo "Booking Time: " . $row['booking_time'] . "<br>";
//                 echo "Status: " . $row['status'] . "<br>";
//                 echo "Shop ID: " . $row['shop_id'] . "<br>";
//                 echo "Customer ID: " . $row['customer_id'] . "<br>";
//                 echo "<br>";
//             }
//         } else {
//             $result_slot[] = $slot;
//         }
//         $stmt->close();
//     }
//     x:
//     foreach ($result_slot as $slot) {
//         echo $slot . '<br>';
//     }

// }

?>
<?php
// require '../mysql_connection.php';

// date_default_timezone_set('Asia/Dhaka');
// $current_time = date("H:i"); // "H" for hours (00 to 23), "i" for minutes
// // echo "Current time: " . $current_time;
// // echo gettype($current_time);
// $current_date = date('Y-m-d');
// // echo "Current Date: " . $current_date;
// function getDayOfWeek($date)
// {
//     $timestamp = strtotime($date);
//     $dayOfWeek = date("l", $timestamp);
//     return $dayOfWeek;
// }
// $result_slot = ['01:00'];
// if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//     $shop_id = $_GET['shop_id'];
//     $date = $_GET['date'];
//     $worker_id = $_GET['worker_id'];

//     if ($date < $current_date)
//         goto x;
//     $day = getDayOfWeek($date);
//     // echo '<br>'. $shop_id . ' ' . $worker_id . ' ' . $date . ' ' . $day . ' <br>';
//     $scheduleQuery = "SELECT `start_time`, `end_time` 
//                   FROM `barber_schedule` 
//                   WHERE `shop_id` = ? AND `day_of_week` = ?";
//     $stmt = $conn->prepare($scheduleQuery);
//     $stmt->bind_param("is", $shop_id, $day);
//     $stmt->execute();
//     $scheduleResult = $stmt->get_result();
//     $scheduleSlots = [];
//     if ($scheduleResult->num_rows > 0) {
//         while ($schedule = $scheduleResult->fetch_assoc()) {

//             $startTime = new DateTime($schedule['start_time']);
//             $endTime = new DateTime($schedule['end_time']);
//             while ($startTime < $endTime) {
//                 // echo $startTime->format('H:i').' '.$current_time.'<br>';
//                 if ($current_date == $date && $startTime->format('H:i') < $current_time) {
//                     goto check;
//                 }
//                 $scheduleSlots[] = $startTime->format('H:i');
//                 check:
//                 $nextTime = clone $startTime;
//                 $nextTime->modify('+1 hour');
//                 $startTime->modify('+1 hour');
//             }
//         }
//     } else {
//         // echo "No schedule found.";
//     }
//     // echo '<br>All slot here<br>';

//     sort($scheduleSlots);
//     foreach ($scheduleSlots as $slot) {
//         $sql_slot = "SELECT * FROM `bookings` WHERE worker_id = ? AND booking_time = ? AND date=?";
//         $stmt = $conn->prepare($sql_slot);
//         $stmt->bind_param("iss", $worker_id, $slot, $date);
//         $stmt->execute();
//         $result = $stmt->get_result();
//         if ($result->num_rows > 0) {
//             while ($row = $result->fetch_assoc()) {
//                 // echo "Booking ID: " . $row['id'] . "<br>";
//                 // echo "Date: " . $row['date'] . "<br>";
//                 // echo "Booking Time: " . $row['booking_time'] . "<br>";
//                 // echo "Status: " . $row['status'] . "<br>";
//                 // echo "Shop ID: " . $row['shop_id'] . "<br>";
//                 // echo "Customer ID: " . $row['customer_id'] . "<br>";
//                 // echo "<br>";
//             }
//         } else {
//             $result_slot[] = $slot;
//         }
//         $stmt->close();
//     }
//     x:
//     foreach ($result_slot as $slot) {
//         echo $slot . '<br>';
//     }
// }
// header('Content-Type: application/json');
// echo json_encode($result_slot);

require '../mysql_connection.php';

date_default_timezone_set('Asia/Dhaka');
$current_time = date("H:i");
$current_date = date('Y-m-d');

function getDayOfWeek($date)
{
    $timestamp = strtotime($date);
    return date("l", $timestamp);
}

$result_slot = ["02:00"]; // Initialize an empty array for available slots
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $shop_id = $_POST['shop_id'];
    $date = $_POST['date'];
    $worker_id = $_POST['worker_id'];


    if ($date < $current_date) {
        // echo json_encode($result_slot); // Return empty array for past dates
        // exit;
    }
    $result_slot[] = $shop_id;
    $result_slot[] = $date;
    $result_slot[] = $worker_id;
    $day = getDayOfWeek($date);

    // Fetch the schedule
    $scheduleQuery = "SELECT `start_time`, `end_time` 
                   FROM `barber_schedule` 
                   WHERE `shop_id` = ? AND `day_of_week` = ?";
    $stmt = $conn->prepare($scheduleQuery);
    $stmt->bind_param("is", $shop_id, $day);
    $stmt->execute();
    $scheduleResult = $stmt->get_result();
    $scheduleSlots = [];
    if ($scheduleResult->num_rows > 0) {
        echo 'check';
        while ($schedule = $scheduleResult->fetch_assoc()) {

            $startTime = new DateTime($schedule['start_time']);
            $endTime = new DateTime($schedule['end_time']);
            while ($startTime < $endTime) {
                $result_slot[] = $startTime->format('H:i');
                if ($current_date == $date && $startTime->format('H:i') < $current_time) {
                    $startTime->modify('+1 hour'); // Skip past slots
                    continue;
                }
                $scheduleSlots[] = $startTime->format('H:i');
                $startTime->modify('+1 hour');
            }
        }
    }

    // Check booked slots
    foreach ($scheduleSlots as $slot) {
        $sql_slot = "SELECT * FROM `bookings` WHERE worker_id = ? AND booking_time = ? AND date=?";
        $stmt = $conn->prepare($sql_slot);
        $stmt->bind_param("iss", $worker_id, $slot, $date);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) { // If the slot is not booked
            $result_slot[] = $slot;
        }
        $stmt->close();
    }
}
$result_slot[] = "04:00";
foreach($result_slot as $slots){
    echo $slots.'<br>';
}
// Set header and output JSON response
// header('Content-Type: application/json');
// echo json_encode($result_slot);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="" method="post">
        <label for="">shop_id:</label><input type="text" name="shop_id">
        <label for="">Worker_id:</label><input type="text" name="worker_id">
        <label for="">Select date:</label><input type="date" name="date">
        <button type="submit">Submit</button>
    </form>
</body>

</html>