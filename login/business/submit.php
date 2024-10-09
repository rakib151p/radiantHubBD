<?php
require 'mysql_connection.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    // Collecting the session variables

    // 1st step information
    $shop_owner = $_SESSION['first-name'] . " " . $_SESSION['last-name'];
    $email = $_SESSION['email'];
    $password = $_SESSION['password'];
    $mobile_number = (string) $_SESSION['mobile-number'];
    $country = $_SESSION['country'];
    //print
    echo '<br>1st step data printing' . '<br><hr>';
    echo 'shop owner name: ' . $shop_owner . '<br>';
    echo 'Email          : ' . $email . '<br>';
    echo 'password       : ' . $password . '<br>';
    echo 'Mobile number  : ' . $mobile_number . '<br>';

    // 2nd step information
    $shop_name = $_SESSION['business-name'];
    $shop_title = $_SESSION['business-title'];
    $shop_website = $_SESSION['website'];
    $shop_info = $_SESSION['business-info'];
    $gender = $_SESSION['gender'];
    $shop_image = '';
    //print
    echo '<br>2nd step data printing' . '<br><hr>';
    echo 'shop name   : ' . $shop_name . '<br>';
    echo 'shop title  : ' . $shop_title . '<br>';
    echo 'shop website: ' . $shop_website . '<br>';
    echo 'shop info   : ' . $shop_info . '<br>';

    // 3rd step information
    $district = $_SESSION['district'];
    $upazilla = $_SESSION['upazilla'];
    $area = $_SESSION['area'];
    $latitude = $_SESSION['latitude'];
    $longitude = $_SESSION['longitude'];
    $landmarks = $_SESSION['landmarks'];
    //print
    echo '<br>3rd step data printing' . '<br><hr>';
    echo 'district  : ' . $district . '<br>';
    echo 'Upazilla  : ' . $upazilla . '<br>';
    echo 'Area      : ' . $area . '<br>';
    echo 'longitude : ' . $longitude . '<br>';
    echo 'latitude  : ' . $latitude . '<br>';
    $count = 1;
    foreach ($landmarks as $landmark) {
        echo 'Landmark ' . $count . ':' . $landmark . '<br>';
        $count++;
    }

    // 4th step information
    $services = $_SESSION['services'];
    //print
    echo '<br>4th step data printing' . '<br><hr>';
    $count = 1;
    foreach ($services as $service) {
        echo 'Services ' . $count . ':' . $service . '<br>';
    }

    // 5th step information
    $items = $_SESSION['items'];
    //print
    echo '<br>5th step data printing' . '<br><hr>';
    $count = 1;
    foreach ($items as $index => $serviceItems) {
        echo "<div class='bg-white p-4 rounded shadow mb-4'>
                <h3 class='font-bold text-xl mb-2'>Service: " . htmlspecialchars($index) . "</h3>
                <ul>";
        foreach ($serviceItems['name'] as $itemIndex => $itemName) {
            $price = htmlspecialchars($serviceItems['price'][$itemIndex]);
            echo "<li class='mb-2'>" . htmlspecialchars($itemName) . " - $" . $price . "</li>";
        }
        echo "</ul>
            </div>";
    }

    // 6th step information
    $member = $_SESSION['memberCount'];
    //print
    echo '<br>6th step data printing' . '<br><hr>';
    echo 'Total member :' . $member . "<br>";


    // 7th step information
    $members_data = $_SESSION['members'];
    //print
    echo '<br>7th step data printing' . '<br><hr>';
    for ($i = 0; $i < $member; $i++) {
        echo $members_data[$i]['name'] . "<br>";
        echo $members_data[$i]["email"] . "<br>";
        echo $members_data[$i]['contact'] . "<br>";
        foreach ($members_data[$i]['expertise'] as $item) {
            echo $item . " ";
        }
        echo "<br>" . $members_data[$i]['experience'] . "<br>";
    }


    // 8th step information
    $schedule = $_SESSION['schedule'] = $_POST['schedule'];
    //print
    // foreach ($schedule as $day => $slots) {
    //     foreach ($slots as $index => $slot) {
    //         $start_time = $slot['start'];
    //         $end_time = $slot['end'];
    //         $insert_sql = "INSERT INTO barber_schedule (shop_id, day_of_week, start_time, end_time) VALUES ($shop_id, '$day', '$start_time', '$end_time')";
    //         mysqli_query($conn, $insert_sql);
    //     }
    // }

    //the max shop_id available till now
    $sql = 'SELECT MAX(shop_id) as shop_id FROM barber_shop';
    $result = mysqli_query($conn, $sql);
    $shop_id = 1;
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $shop_id = $row['shop_id'];
        $shop_id++;
    }

    // $shop_id = 104;
    // Store data in `barber_shop` table
    $sql = "INSERT INTO `barber_shop`(`shop_id`, `shop_name`, `shop_title`, `shop_info`,`gender`, `shop_email`, `shop_password`, `shop_owner`, `mobile_number`, `shop_state`, `shop_city`, `shop_area`, `shop_landmark_1`, `shop_landmark_2`, `shop_landmark_3`, `shop_landmark_4`, `shop_landmark_5`,`latitude`, `longitude`) 
            VALUES ('$shop_id','$shop_name','$shop_title','$shop_info','$gender','$email','$password','$shop_owner','$mobile_number','$district','$upazilla','$area','$landmarks[0]','$landmarks[1]','$landmarks[2]','$landmarks[3]','$landmarks[4]','$latitude','$longitude')";
    $text = $conn->prepare($sql);
    $text->execute();

    // Storing data in shop_address table
    // $sql = "INSERT INTO `shop_address`(`shop_id`, `shop_state`, `shop_city`, `shop_area`, `shop_landmark_1`, `shop_landmark_2`, `shop_landmark_3`, `shop_landmark_4`, `shop_landmark_5`,`latitude`, `longitude`) 
    //         VALUES ('$shop_id','$district','$upazilla','$area','$landmarks[0]','$landmarks[1]','$landmarks[2]','$landmarks[3]','$landmarks[4]','$latitude','$longitude')";
    // $text = $conn->prepare($sql);
    // $text->execute();

    //storing available services to shop_services_table
    foreach ($items as $index => $serviceItems) {
        foreach ($serviceItems['name'] as $itemIndex => $itemName) {
            $sql = "SELECT * from `item_table` where item_name='$itemName' ";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $service_id = $row["service_id"];
                $item_id = $row["item_id"];
                $price = htmlspecialchars($serviceItems['price'][$itemIndex]);
                // echo' '. $item_id .' '. $service_id .' '. $itemName .' '. $price .' <br>';
                $sql = "INSERT INTO `shop_service_table`(`shop_id`, `service_id`, `item_id`, `item_price`) 
                        VALUES ('$shop_id','$service_id','$item_id','$price')";
                $text = $conn->prepare($sql);
                $text->execute();
            }
        }
    }

    //storing data for shop_workers in shop_workers table
    for ($i = 0; $i < $member; $i++) {
        $sql = 'SELECT MAX(worker_id) as worker_id FROM shop_worker';
        $result = mysqli_query($conn, $sql);
        $worker_id = 1;
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $worker_id = $row['worker_id'];
            $worker_id++;
        }
        $expertise = '';
        foreach ($members_data[$i]['expertise'] as $item) {
            $expertise = $expertise . ',' . $item;
        }
        $sql = "INSERT INTO `shop_worker` (`worker_id`,`shop_id`, `worker_name`, `experience`, `expertise`, `email`, `mobile_number`) 
        VALUES ('$worker_id','$shop_id', '{$members_data[$i]["name"]}', '{$members_data[$i]["experience"]}', '$expertise', '{$members_data[$i]["email"]}', '{$members_data[$i]["contact"]}')";
        $text = $conn->prepare($sql);
        $text->execute();
        foreach ($members_data[$i]['expertise'] as $item) {
            $sql = "SELECT `item_id` FROM `item_table` WHERE item_name='$item'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $item_id = $row['item_id'];
                $sql = "INSERT INTO worker_expertise(worker_id, item_id) VALUES ($worker_id,$item_id)";
                $text = $conn->prepare($sql);
                $text->execute();
            }
        }
    }

    //storing data about schedule of the shop
    $schedule = $_POST['schedule'];
    foreach ($schedule as $day => $slots) {
        usort($slots, function ($a, $b) {
            return strtotime($a['start']) - strtotime($b['start']);
        });

        for ($i = 0; $i < count($slots) - 1; $i++) {
            if (strtotime($slots[$i]['end']) > strtotime($slots[$i + 1]['start'])) {
                echo "Error: Overlapping time slots detected on $day.";
                exit;
            }
        }
    }
    // Clear existing schedule for the barber
    $delete_sql = "DELETE FROM barber_schedule WHERE shop_id = $shop_id";
    mysqli_query($conn, $delete_sql);

    foreach ($schedule as $day => $slots) {
        foreach ($slots as $index => $slot) {
            $start_time = $slot['start'];
            $end_time = $slot['end'];
            $insert_sql = "INSERT INTO barber_schedule (shop_id, day_of_week, start_time, end_time) 
                            VALUES ($shop_id, '$day', '$start_time', '$end_time')";
            mysqli_query($conn, $insert_sql);
        }
    }
    // $sql = "INSERT INTO `shop_service_table`(`shop_id`, `service_id`, `item_id`, `item_price`) 
    //         VALUES ('$shop_id','[value-2]','[value-3]','[value-4]')";
    // $text = $conn->prepare($sql);
    // $text->execute();

    echo "updated successfully";
    // Redirect or display a success message
    session_destroy();
    header("Location: http://localhost/rakib/final1/login/login.php");
    exit();
}
?>