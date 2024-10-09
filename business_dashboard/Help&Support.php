<?php
include '../mysql_connection.php';
$shop_id = 1; // Example shop ID
$dataPoints = array();
$current_timestamp = date('Y-m-d'); // Get the current timestamp

$sql_1 = "SELECT date, COUNT(*) AS total_bookings
          FROM `bookings`
          WHERE `shop_id` = ? AND date <= ?
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
            bookings.shop_id = 104 
        GROUP BY 
            YEAR(bookings.date), MONTH(bookings.date)
        ORDER BY 
            YEAR(bookings.date) DESC, MONTH(bookings.date) DESC
        LIMIT 12";

$results_sales = mysqli_query($conn, $sql);
if ($results_sales) {
    while ($row = mysqli_fetch_assoc($results_sales)) {
        // Update the corresponding month with actual sales data
        $dataPointsales[$row['month']]['y'] = $row['total_price'];
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

// Convert to indexed array
$dataPointsales = array_values($dataPointsales);

// Now $dataPointsales contains all months with total_price or zero

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
    </style>
</head>

<body class="bg-gray-200 font-sans leading-normal tracking-normal">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-gray-100 w-64 space-y-6 py-7 px-2" id="dashboard">
            <div class="flex items-center justify-center">
                <span class="text-2xl font-extrabold uppercase" style="margin-right:40px">RadiantHub</span>
            </div>
            <nav class="mt-10">
                <a href="saloon_dashboard.php"
                    class="block py-2.5 px-4 rounded-lg mt-2 text-gray-100 hover:bg-pink-500">Dashboard</a>
                <a href="saloon_gallery.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Photo
                    Gallery</a>
                <a href="saloon_calender.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Calendar</a>
                <a href="booking_slots.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Appointments</a>
                <a href="staffs.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Staffs</a>
                <a href="Customers.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Customers</a>
                <a href="Reviews.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Reviews</a>
                <a href="Notifications.php"
                    class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Notifications</a>
                <a href="saloon_setting.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Settings</a>
                <a href="Help&Support.php" class="block bg-pink-600 py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Help &
                    Support</a>
            </nav>
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="main-content p-4 flex-1">
                <div class="header flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold" style="margin:0 0 0 40px;">Gallery</h2>
                    <div class="notification flex items-center" style="margin-right:40px;">
                        <img src="1-change1.jpg" alt="Notification Icon" class="w-8 h-8 rounded-full mr-2">
                        <span class="text-gray-500 mr-2">Enterprise</span>
                        <h3 class="text-base font-bold">Bombshell Studio</h3>
                    </div>
                </div>
                
            </div>
        </main>
        

</body>

</html>