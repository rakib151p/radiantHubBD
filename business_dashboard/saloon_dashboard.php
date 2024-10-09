<?php
include '../mysql_connection.php';
session_start();
$shop_id = $_SESSION['shop_id']; // Example shop ID
// echo $shop_id;
//now fetch all the needed details of the shop by using shop_id
$sql_shop = "SELECT* FROM barber_shop WHERE shop_id='$shop_id'";
$result_shop = mysqli_query($conn, $sql_shop);
if ($row_shop = $result_shop->fetch_assoc()) {
    $_SESSION['shop_id'] = $row_shop['shop_id'];
    $_SESSION['shop_name'] = $row_shop['shop_name'];
    $_SESSION['shop_title'] = $row_shop['shop_title'];
    $_SESSION['shop_info'] = $row_shop['shop_info'];
    $_SESSION['gender'] = $row_shop['gender'];
    $_SESSION['shop_rating'] = $row_shop['shop_rating'];
    $_SESSION['shop_picture'] = $row_shop['shop_picture'];
    $_SESSION['shop_email'] = $row_shop['shop_email'];
    $_SESSION['shop_password'] = $row_shop['shop_password'];
    $_SESSION['shop_owner'] = $row_shop['shop_owner'];
    $_SESSION['shop_customer_count'] = $row_shop['shop_customer_count'];
    $_SESSION['status'] = $row_shop['status'];
    $_SESSION['mobile_number'] = $row_shop['mobile_number'];
    $_SESSION['member_since'] = $row_shop['member_since'];
    $_SESSION['shop_state'] = $row_shop['shop_state'];
    $_SESSION['shop_city'] = $row_shop['shop_city'];
    $_SESSION['shop_area'] = $row_shop['shop_area'];
    $_SESSION['shop_landmark_1'] = $row_shop['shop_landmark_1'];
    $_SESSION['shop_landmark_2'] = $row_shop['shop_landmark_2'];
    $_SESSION['shop_landmark_3'] = $row_shop['shop_landmark_3'];
    $_SESSION['shop_landmark_4'] = $row_shop['shop_landmark_4'];
    $_SESSION['shop_landmark_5'] = $row_shop['shop_landmark_5'];
    $_SESSION['latitude'] = $row_shop['latitude'];
    $_SESSION['longitude'] = $row_shop['longitude'];
}
$sql_notify = "SELECT count(*) as total FROM notifications_by_admin WHERE shop_id='$shop_id' AND status=0";
$result_notify = mysqli_query($conn, $sql_notify);
$notification = 0;
if ($result_notify) {
    $notify = $result_notify->fetch_assoc();
    $notification = $notify['total'];
}
$_SESSION['notification'] = $notification;
// echo $notification;
//  count_message
$sql_msg = "SELECT count(*) as total FROM message_table WHERE shop_id='$shop_id' AND status=0";
$result_msg = mysqli_query($conn, $sql_msg);
$message = 0;
if ($result_msg) {
    $result_msg_a = $result_msg->fetch_assoc();
    $message = $result_msg_a['total'];
}
$_SESSION['message'] = $message;

$dataPoints = array();
$current_timestamp = date('Y-m-d'); // Get the current timestamp

$sql_1 = "SELECT date, COUNT(*) AS total_bookings
          FROM `bookings`
          WHERE `shop_id` = ? AND date <= ? AND status='completed'
          GROUP BY date
          ORDER BY date
          LIMIT 7";
$stmt = $conn->prepare($sql_1);
$stmt->bind_param("is", $shop_id, $current_timestamp); // Bind the shop_id as an integer
$stmt->execute();
$results = $stmt->get_result(); // Fetch the result set

$previous7Days = [];
for ($i = 0; $i < 7; $i++) {
    $day = date('Y-m-d', strtotime("-$i days"));
    $day_name = date('l', strtotime($day));
    $previous7Days[$day] = ["day_name" => $day_name, "total_bookings" => 0]; // Initialize with 0 bookings
}

// Override with actual data from the query
while ($row = $results->fetch_assoc()) {
    $row_date = $row['date'];
    if (isset($previous7Days[$row_date])) {
        $previous7Days[$row_date]['total_bookings'] = (int) $row['total_bookings'];
    }
}
// Prepare the final data for the chart last 7 days stats
foreach ($previous7Days as $date => $data) {
    array_push($dataPoints, array("y" => $data['total_bookings'], "label" => $data['day_name']));
}
$months = [
    1 => "January",
    2 => "February",
    3 => "March",
    4 => "April",
    5 => "May",
    6 => "June",
    7 => "July",
    8 => "August",
    9 => "September",
    10 => "October",
    11 => "November",
    12 => "December"
];
$dataPointsales = array_fill_keys(array_keys($months), array("y" => 0, "label" => ""));

// Initialize labels for each month
foreach ($months as $key => $value) {
    $dataPointsales[$key]['label'] = $value;
}
$this_month_revenue = 0;
$month_name = date('n');
//SQL query to fetch recent 7 days customers details
$sql = "SELECT 
        MONTH(bookings.date) AS month, 
        YEAR(bookings.date) AS year,  
        SUM(shop_service_table.item_price) AS total_price
        FROM 
            bookings 
        JOIN 
            shop_service_table 
        ON 
            bookings.shop_id = shop_service_table.shop_id 
            AND bookings.item_id = shop_service_table.item_id 
        WHERE 
            bookings.shop_id = '$shop_id' AND bookings.status='completed'
        GROUP BY 
            YEAR(bookings.date), MONTH(bookings.date)
        ORDER BY 
            YEAR(bookings.date) DESC, MONTH(bookings.date) DESC
        LIMIT 12";

$results_sales = mysqli_query($conn, $sql);
if ($results_sales) {
    while ($row = mysqli_fetch_assoc($results_sales)) {
        // echo $row['month'];
        // Update the corresponding month with actual sales data
        if ($month_name === $row['month'])
            $this_month_revenue = $row['total_price'];
        $dataPointsales[$row['month']]['y'] = $row['total_price'];
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

// Convert to indexed array
$dataPointsales = array_values($dataPointsales);
// Now $dataPointsales contains all months with total_price or zero

//

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saloon Dashboard</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
    <!-- Include Chart.js for graphs/charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #dashboard {
            height: 100vh;
            scrollbar-width: none;

        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            background-color: #00014E;
            width: 250px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .logo h1 {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .sidebar li img {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        .sidebar li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        .header {
            display: flex;
            background-color: #00E081;
            color: white;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            width: 1625px;
            box-shadow: 1px 1px 5px 3px rgba(0, 0, 0, 0.1);
            /* border:1px solid black; */
            height: 100px;
        }

        .header h2 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .search-bar {
            position: relative;
            width: 300px;
            height: 40px;
            border-radius: 20px;
            background-color: white;
            padding: 10px 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .search-bar input {
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
            font-size: 16px;
            padding: 0 10px;
        }

        .search-bar img {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
        }

        .notification {
            display: flex;
            align-items: center;
        }

        .notification img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .notification span {
            font-size: 14px;
            color: #555;
            margin-right: 10px;
        }

        .notification h3 {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }

        .dashboard-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            width: calc(50% - 10px);
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .chart-container {
            width: 100%;
            height: 200px;
        }

        .chart-options {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }

        .chart-options button {
            background-color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            margin-left: 5px;
            cursor: pointer;
        }

        .chart-options button.active {
            background-color: #007bff;
            color: white;
        }

        .card-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card-content .number {
            font-size: 40px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .card-content span {
            font-size: 14px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .metrics {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            width: calc(33.33% - 10px);
            margin-bottom: 10px;
        }

        .metrics h3 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .metrics .rating {
            font-size: 24px;
            font-weight: bold;
        }

        .metrics .number {
            font-size: 20px;
            font-weight: bold;
        }




        /* Carousel Container */
        .carousel-container {
            position: relative;
            max-width: 300px;
            margin: auto;
            overflow: hidden;
        }

        /* Carousel Inner Wrapper */
        .carousel-inner {
            display: flex;
            transition: transform 0.5s ease-in-out;
            width: 300%;
        }

        /* Card Style */
        .cards {
            background-color: #71C383;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 10px;
            text-align: center;
            width: 300px;
            /* Each card takes up a third of the width */
            height: 200px;
            box-sizing: border-box;
        }

        /* Name, Rating, and Feedback */
        .cards h3 {
            font-size: 1.5em;
            color: #333;
        }

        .cards .rating {
            color: #FFD700;
            /* Gold star color */
            font-size: 1.2em;
            margin: 10px 0;
        }

        .cards p {
            color: #666;
            font-size: 1em;
            margin-bottom: 15px;
        }

        /* View More Button */
        .view-more {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .view-more:hover {
            background-color: #0056b3;
        }

        /* Navigation Buttons */
        .prev-btn,
        .next-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 50%;
            font-size: 10px;
        }

        .prev-btn {
            left: 10px;
        }

        .next-btn {
            right: 10px;
        }

        .prev-btn:hover,
        .next-btn:hover {
            background-color: #0056b3;
        }

        * {
            font-family: cursive;
        }
    </style>
</head>

<body class="bg-gray-200 font-sans leading-normal tracking-normal">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-gray-100 w-64 space-y-6 py-7 px-2" id="dashboard">
            <div class="flex items-center justify-center">
                <span class="text-2xl font-extrabold uppercase" style="margin-right:40px"><a
                        href="../home.php">RadiantHub</a> </span>
            </div>
            <nav class="mt-10">
                <a href="saloon_dashboard.php"
                    class="block py-2.5 px-4 rounded-lg mt-2 bg-pink-600 text-gray-100">Dashboard</a>
                <a href="saloon_gallery.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Photo
                    Gallery</a>
                <a href="saloon_calender.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Calendar</a>
                <a href="booking_slots.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Appointments</a>
                <a href="message.php"
                    class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Message&nbsp;<?php if ($_SESSION['message'] > 0)
                        echo '<span class="right-0 bg-yellow-500 text-yellow-900 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">' . $_SESSION['message'] . '</span>'; ?></a>
                <a href="staffs.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Staffs</a>
                <a href="Customers.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Customers</a>
                <a href="Reviews.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Reviews</a>
                <a href="Notifications.php"
                    class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Notifications&nbsp;<?php if ($_SESSION['notification'] > 0)
                        echo '<span class="right-0 bg-yellow-500 text-yellow-900 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">' . $_SESSION['notification'] . '</span>'; ?></a>
                <a href="saloon_setting.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Settings</a>

            </nav>
        </div>

        <!-- Main Content -->

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="main-content p-4 flex-1">
                <div class="header flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold" style="margin:0 0 0 40px;">Welcome to Deshboard</h2>
                    <!-- <h2 class="text-2xl font-bold" style="margin:0 0 0 40px;">Welcome to Deshboard</h2> -->
                    <div class="notification flex items-center" style="margin-right:40px;">
                        <img src="../image/shop/<?php echo $_SESSION['shop_id']; ?>.jpeg" alt="Notification Icon"
                            class="w-8 h-8 rounded-full mr-2">
                        <h3 class="text-base font-bold"><?php echo $_SESSION['shop_name']; ?>&nbsp;</h3>
                        <a href="../logout.php"><img src="../image/icon/logout.png" alt="logout"
                                class="w-8 h-8 rounded-full mr-2"></a>
                    </div>
                </div>
                <div class="dashboard-content flex flex-wrap justify-between">
                    <!-- response by last 7 days -->
                    <div class="card bg-white rounded-lg p-4 w-1/2 mb-4 shadow-md">
                        <h3 class="text-xl font-bold mb-2">Response of last 7 days</h3>
                        <div id="stats" style="height: 370px; width: 100%;"></div>
                    </div>
                    <div class="card bg-white rounded-lg p-4 w-1/2 mb-4 shadow-md">
                        <h3 class="text-xl font-bold mb-2">Sales</h3>
                        <div id="sales" style="height: 370px; width: 100%;"></div>
                        <!-- <div class="chart-options flex justify-end mt-2">
                            <button
                                class="bg-white border-none px-3 py-1 rounded-md mr-2 focus:outline-none active:bg-blue-500 active:text-white">Yearly</button>
                            <button class="bg-white border-none px-3 py-1 rounded-md focus:outline-none">Montly</button>
                        </div> -->
                    </div>
                    <div class="card rounded-lg p-4 w-1/2 mb-4 shadow-md" style="background-color:#F1D726;color:white;">
                        <h3 class="text-xl font-bold mb-2">Total customers</h3>
                        <div class="card-content flex flex-col items-center">
                            <div class="number text-4xl font-bold mb-2"><?php
                            $shop_id = $_SESSION['shop_id'];

                            // Fetching the bookings of the logged-in customer
                            $query = "SELECT count(*) as total FROM bookings WHERE shop_id = ? AND  status='completed' ORDER BY date DESC";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("i", $shop_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
                            echo $row['total'];
                            // echo $_SESSION['shop_customer_count']; 
                            ?>
                            </div>
                            <!-- <span class="text-gray-500" style="color:white;">+22 than last week</span> -->
                        </div>
                    </div>
                    <div class="card  rounded-lg p-4 w-1/2 mb-4 shadow-md"
                        style="background-color:#963FF0;color:white;">
                        <h3 class="text-xl font-bold mb-2">Total sales(this month)</h3>
                        <div class="card-content flex flex-col items-center">
                            <div class="number text-4xl font-bold mb-2"><?php echo $this_month_revenue; ?></div>
                            <!-- <span class="text-gray-500" style="color:white;">+22 than last week</span> -->
                        </div>
                    </div>
                    <!-- <div class="card bg-white rounded-lg p-4 w-1/2 mb-4 shadow-md">
                        <h3 class="text-xl font-bold mb-2">Customers statistics</h3>
                        <div class="chart-container">
                            <canvas id="memberChart"></canvas>
                        </div>
                        <div class="chart-options flex justify-end mt-2">
                            <button
                                class="bg-white border-none px-3 py-1 rounded-md mr-2 focus:outline-none active:bg-blue-500 active:text-white">Yearly</button>
                            <button class="bg-white border-none px-3 py-1 rounded-md focus:outline-none">Montly</button>
                        </div>
                    </div>
                    <div class="card bg-white rounded-lg p-4 w-1/2 mb-4 shadow-md">
                        <h3 class="text-xl font-bold mb-2">Age range</h3>
                        <div class="chart-container">
                            <canvas id="feedbackChart"></canvas>
                        </div>
                        <div class="chart-options flex justify-end mt-2">
                            <button
                                class="bg-white border-none px-3 py-1 rounded-md mr-2 focus:outline-none active:bg-blue-500 active:text-white">Guests
                                Visiting</button>
                            <button
                                class="bg-white border-none px-3 py-1 rounded-md focus:outline-none">Feedbacks</button>
                        </div>
                    </div> -->
                    <p style="font-size:20px;font-weight:700;">Our Review:</p>
                    <div class="carousel-container">

                        <div class="carousel-inner">
                            <!-- Card 1 -->
                            <?php
                            $shop_id = $_SESSION['shop_id'];
                            $sql = "SELECT * from review_shop 
                                     JOIN customer ON customer.customer_id = review_shop.customer_id 
                                     WHERE shop_id = '$shop_id'";
                            $result = mysqli_query($conn, $sql);
                            while ($row_result_re = $result->fetch_assoc()) {
                                echo '<div class="cards">
                                <h3 style="color:white">' . $row_result_re['first_name'] . ' ' . $row_result_re['last_name'] . '</h3>';
                                $rate = $row_result_re['star'];

                                echo '<div class="rating">';
                                // ★★★★★
                                while ($rate--) {
                                    echo '★';
                                }
                                echo '</div>';
                                echo '<p style="color:white">' . $row_result_re['review'] . '</p>

                            </div>';
                            }

                            ?>
                        </div>

                        <!-- Navigation Buttons -->
                        <button class="prev-btn">❮</button>
                        <button class="next-btn">❯</button>
                    </div>
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const carouselInner = document.querySelector(".carousel-inner");
                            let currentIndex = 0;

                            document.querySelector(".prev-btn").addEventListener("click", () => {
                                if (currentIndex > 0) {
                                    currentIndex--;
                                    updateCarousel();
                                }
                            });

                            document.querySelector(".next-btn").addEventListener("click", () => {
                                if (currentIndex < 2) { // Adjust this based on the number of cards
                                    currentIndex++;
                                    updateCarousel();
                                }
                            });

                            function updateCarousel() {
                                carouselInner.style.transform = `translateX(-${currentIndex * 33.33}%)`;
                            }
                        });
                    </script>
                    <div class="card bg-white rounded-lg p-4 w-full mb-4 shadow-md flex justify-between">
                        <div class="metrics flex flex-col items-center justify-center p-4 border border-gray-300 rounded-lg w-1/3 mr-2"
                            style="background-color:#F95B77;color:white;">
                            <h3 class="text-sm font-bold mb-2">Average Ratings</h3>
                            <div class="rating text-xl font-bold"><?php echo $_SESSION['shop_rating']; ?></div>
                        </div>
                        <div class="metrics flex flex-col items-center justify-center p-4 border border-gray-300 rounded-lg w-1/3 mr-2"
                            style="background-color:#48CEEE;color:white;">
                            <h3 class="text-sm font-bold mb-2">Happy Customers</h3>
                            <div class="number text-lg font-bold">

                                <?php
                                $shop_id = $_SESSION['shop_id'];
                                $sql = "SELECT count(*) as total from review_shop 
                                        JOIN customer ON customer.customer_id = review_shop.customer_id 
                                        WHERE shop_id = '$shop_id' and star>=4";
                                $result = mysqli_query($conn, $sql);
                                $happy=$result->fetch_assoc();
                                echo $happy['total'];
                                ?>
                            </div>
                        </div>
                        <div class="metrics flex flex-col items-center justify-center p-4 border border-gray-300 rounded-lg w-1/3"
                            style="background-color:#963FF0;color:white;">
                            <h3 class="text-sm font-bold mb-2">Unhappy Customers</h3>
                            <div class="number text-lg font-bold"><?php
                                $shop_id = $_SESSION['shop_id'];
                                $sql = "SELECT count(*) as total from review_shop 
                                        JOIN customer ON customer.customer_id = review_shop.customer_id 
                                        WHERE shop_id = '$shop_id' and star<4";
                                $result = mysqli_query($conn, $sql);
                                $happy=$result->fetch_assoc();
                                echo $happy['total'];
                                ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- Chart.js script for example chart -->
        <script>
            window.onload = function () {
                var chart = new CanvasJS.Chart("stats", {
                    title: {
                        text: "Last 7 days stats"
                    }, axisX: {

                        labelAngle: -45 // Rotates the labels to prevent overlap (adjust angle if necessary)
                    },
                    axisY: {
                        title: "Number of customers"
                    },
                    data: [{
                        type: "line",
                        dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chart.render();

                var chart1 = new CanvasJS.Chart("sales", {
                    animationEnabled: true,
                    theme: "light2", // "light1", "light2", "dark1", "dark2"
                    title: {
                        text: "Sales stats"
                    }, axisX: {
                        interval: 1,  // Ensures every label on the x-axis is displayed
                        labelAngle: -45 // Rotates the labels to prevent overlap (adjust angle if necessary)
                    },
                    axisY: {
                        title: "Revenue"
                    },
                    data: [{
                        type: "column",
                        dataPoints: <?php echo json_encode($dataPointsales, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chart1.render();

            }


        </script>
        <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

</body>

</html>