<?php
include '../mysql_connection.php';
session_start();
$shop_id = $_SESSION['shop_id'];

// Fetching the bookings of the logged-in customer
$query = "SELECT * FROM bookings WHERE shop_id = ? AND  status='completed' ORDER BY date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $shop_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
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
            font-size: 35px;
            font-weight: bold;
            margin: 0;
            color: rgb(0, 0, 0);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
            font-size: 16px;
            text-align: left;
        }

        th,
        td {
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* table {
            width: 1620px;
            background-color: whitesmoke;
        } */
        *{
            font-family: cursive;
        }
    </style>
</head>

<body class="bg-gray-200 font-sans leading-normal tracking-normal">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-gray-100 w-64 space-y-6 py-7 px-2" id="dashboard">
            <div class="flex items-center justify-center">
                <span class="text-2xl font-extrabold uppercase" style="margin-right:40px"><a href="../home.php">RadiantHub</a> </span>
            </div>
            <nav class="mt-10">
                <a href="saloon_dashboard.php"
                    class="block py-2.5 px-4 rounded-lg mt-2 text-gray-100 hover:bg-pink-500">Dashboard</a>
                <a href="saloon_gallery.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Photo
                    Gallery</a>
                <a href="saloon_calender.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Calendar</a>
                <a href="booking_slots.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Appointments</a>
                <a href="message.php"
                    class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Message&nbsp;<?php if ($_SESSION['message'] > 0)
                        echo '<span class="right-0 bg-yellow-500 text-yellow-900 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">' . $_SESSION['message'] . '</span>'; ?></a>
                <a href="staffs.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Staffs</a>
                <a href="Customers.php"
                    class="block bg-pink-600 py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Customers</a>
                <a href="Reviews.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Reviews</a>
                <a href="Notifications.php"
                    class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Notifications&nbsp;<?php if ($_SESSION['notification'] > 0)
                        echo '<span class="right-0 bg-yellow-500 text-yellow-900 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">' . $_SESSION['notification'] . '</span>'; ?></a>
                <a href="saloon_setting.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Settings</a>
                
            </nav>
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="main-content p-4 flex-1">
                <div class="header flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold" style="margin:0 0 0 40px;">Customers history</h2>
                    <div class="notification flex items-center" style="margin-right:40px;">
                        <img src="../image/shop/<?php echo $_SESSION['shop_id']; ?>.jpeg" alt="Notification Icon"
                            class="w-8 h-8 rounded-full mr-2">
                        <h3 class="text-base font-bold"><?php echo $_SESSION['shop_name']; ?>&nbsp;</h3>
                        <a href="../logout.php"><img src="../image/icon/logout.png" alt="logout"
                                class="w-8 h-8 rounded-full mr-2"></a>
                    </div>
                </div>
                <?php
                if ($result->num_rows > 0) {
                    echo '<div class="overflow-x-auto tables">';
                    echo '<table class="min-w-full bg-white border border-gray-200 rounded-lg">';
                    echo '<thead class="bg-pink-500 text-white">';
                    echo '<tr>';
                    echo '<th class="px-2 py-1 text-left">Booking Date</th>';
                    echo '<th class="px-2 py-1 text-left">customer_name</th>';
                    echo '<th class="px-2 py-1 text-left">Customer_address</th>';
                    echo '<th class="px-2 py-1 text-left">Contact</th>';
                    echo '<th class="px-2 py-1 text-left">Product details</th>';
                    echo '<th class="px-2 py-1 text-left">Appointment date</th>';
                    echo '<th class="px-2 py-1 text-left">Appointment Time</th>';
                    echo '<th class="px-2 py-1 text-left">Expert</th>';
                    echo '<th class="px-2 py-1 text-left">Expert contact</th>';
                    echo '<th class="px-2 py-1 text-left">Status</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody class="text-gray-700">';
                    while ($row = $result->fetch_assoc()) {

                        echo '<tr class="border-t">';
                        echo '<td class="px-2 py-1">' . htmlspecialchars($row['when_booked']) . '</td>';
                        //fetching shops
                        $customer_id = $row['customer_id'];
                        $sql_shop = "select * from customer where customer_id='$customer_id'";
                        $result_shop = mysqli_query($conn, $sql_shop);
                        while ($row_shop = $result_shop->fetch_assoc()) {
                            echo '<td class="px-2 py-1">' . htmlspecialchars($row_shop['first_name']) . ' ' . htmlspecialchars($row_shop['last_name']) . '</td>';
                            echo '<td class="px-2 py-1">' . htmlspecialchars($row_shop['district']) . ',' . htmlspecialchars($row_shop['upazilla']) . ',' . htmlspecialchars($row_shop['area']) . '</td>';
                            echo '<td class="px-2 py-1">' . htmlspecialchars($row_shop['mobile_number']);
                        }
                        //fetching items
                        $item_id = $row['item_id'];
                        $sql_item = "select item_name from item_table where item_id='$item_id'";
                        $result_item = mysqli_query($conn, $sql_item);
                        while ($row_item = $result_item->fetch_assoc()) {
                            echo '<td class="px-2 py-1">' . htmlspecialchars($row_item['item_name']) . '</td>';
                        }
                        // booking date and time
                        echo '<td class="px-2 py-1">' . htmlspecialchars($row['date']) . '</td>';
                        echo '<td class="px-2 py-1">' . htmlspecialchars($row['booking_time']) . '</td>';

                        // expert details 
                        $worker_id = $row['worker_id'];
                        $sql_worker = "select worker_name,mobile_number from shop_worker where worker_id='$worker_id'";
                        $result_worker = mysqli_query($conn, $sql_worker);
                        while ($row_worker = $result_worker->fetch_assoc()) {
                            echo '<td class="px-2 py-1">' . htmlspecialchars($row_worker['worker_name']) . '</td>';
                            echo '<td class="px-2 py-1">' . htmlspecialchars($row_worker['mobile_number']) . '</td>';
                        }
                        //status
                        echo '<td class="px-2 py-1">' . htmlspecialchars($row['status']) . '</td>';

                    }
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                } else {
                    echo '<p class="text-gray-500">No bookings found.</p>';
                }
                ?>
            </div>
    </div>
    </main>
</body>

</html>