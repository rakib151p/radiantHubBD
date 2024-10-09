<?php
include '../mysql_connection.php';
session_start();
$shop_id = $_SESSION['shop_id'];
$sql_update = "UPDATE `message_table` SET `status` = 1 WHERE `shop_id` = '$shop_id'";
$result = mysqli_query($conn, $sql_update);
if ($result) {
    // echo "Update successful.";
    $_SESSION['message'] = 0;
} else {
    echo "Error updating record: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox</title>
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

        /* .container{
            background-color: red;
        } */
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
                    class="block py-2.5 px-4 rounded-lg mt-2 text-gray-100 hover:bg-pink-500">Dashboard</a>
                <a href="saloon_gallery.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Photo
                    Gallery</a>
                <a href="saloon_calender.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Calendar</a>
                <a href="booking_slots.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Appointments</a>
                <a href="message.php"
                    class="block  bg-pink-600 py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500 ">Messages</a>
                <a href="staffs.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Staffs</a>
                <a href="Customers.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Customers</a>
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
                    <h2 class="text-2xl font-bold" style="margin:0 0 0 40px;">Inbox</h2>
                    <div class="notification flex items-center" style="margin-right:40px;">
                        <img src="../image/shop/<?php echo $_SESSION['shop_id']; ?>.jpeg" alt="Notification Icon"
                            class="w-8 h-8 rounded-full mr-2">
                        <h3 class="text-base font-bold"><?php echo $_SESSION['shop_name']; ?>&nbsp;</h3>
                        <a href="../logout.php"><img src="../image/icon/logout.png" alt="logout"
                                class="w-8 h-8 rounded-full mr-2"></a>
                    </div>
                </div>
                <?php
                $shop_id = $_SESSION['shop_id'];
                $sql_user = "SELECT 
                                    m.message_id,
                                    m.shop_id,
                                    m.customer_id,
                                    m.message,
                                    m.date_and_time,
                                    CONCAT(c.first_name, ' ', c.last_name) AS name,
                                    c.mobile_number,
                                    c.email
                                FROM 
                                    message_table m
                                JOIN 
                                    customer c ON m.customer_id = c.customer_id
                                WHERE 
                                    m.date_and_time = (
                                        SELECT 
                                            MAX(mt.date_and_time)
                                        FROM 
                                            message_table mt
                                        WHERE 
                                            mt.customer_id = m.customer_id
                                            AND mt.shop_id = m.shop_id
                                    ) 
                                    AND m.shop_id = '$shop_id'
                                ORDER BY 
                                    m.date_and_time DESC";

                // $sql_notify = "SELECT * FROM notifications_by_admin WHERE shop_id='$shop_id' ORDER BY date_time desc";
                $result_notify = mysqli_query($conn, $sql_user);
                if ($result_notify->num_rows > 0) {
                    while ($row_notify = $result_notify->fetch_assoc()) {
                        echo '<a href="reply_message.php?customer_id=' . $row_notify['customer_id'] . '">';
                        echo '<div class="bg-green-100 border border-green-400 text-green-700 my-5 px-4 py-3 rounded relative"
                        role="alert"><h4 class="font-bold text-lg">' . $row_notify['name'] . '</h4>';
                        // echo '<p class="mt-2">' . $row_notify['message'] . '</p>';
                        echo '<p class="mb-0">' . $row_notify['message'] . '
                                    </p><span id="current-time" class="absolute bottom-0 right-0 mb-2 mr-2 text-sm text-gray-500">' . $row_notify['date_and_time'] . '</span></div></a>';
                    }
                }
                ?>
                <!-- <h4 class="font-bold text-lg">Well done!</h4> -->
                <!-- <p class="mt-2">Aww yeah, you successfully read this important alert message. This example text is
                        going to run a
                        bit longer so that you can see how spacing within an alert works with this kind of content.</p>
                    <hr class="my-4 border-t-2 border-green-400">
                    <p class="mb-0">Admin, RadientHub BD
                    </p><span id="current-time" class="absolute bottom-0 right-0 mb-2 mr-2 text-sm text-gray-500">Time and date</span> -->

            </div>
        </main>
</body>

</html>