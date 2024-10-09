<?php
include '../mysql_connection.php';
session_start();

function build_calendar($month, $year)
{
    $days_of_week = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    $first_day_of_month = mktime(0, 0, 0, $month, 1, $year);
    $next_month = $month;
    $next_year = $year;
    $prev_month = $month;
    $prev_year = $year;

    if ($month == 1) {
        $prev_month = 12;
        $prev_year = $year - 1;
    } else {
        $prev_month = $month - 1;
    }

    if ($month == 12) {
        $next_month = 1;
        $next_year = $year + 1;
    } else {
        $next_month = $month + 1;
    }

    $number_of_days_of_month = date('t', $first_day_of_month);
    $date_info = getdate($first_day_of_month);
    $month_name = $date_info['month'];
    $day_of_week = $date_info['wday'];
    $timezone = new DateTimeZone('Asia/Dhaka');
    $current_date_time_bd = new DateTime('now', $timezone);
    $current_date = $current_date_time_bd->format('Y-m-d');

    $calendar = "<center><h5 class='text-gray-700 text-2xl font-bold'>$month_name $year</h5>";
    $calendar .= "<div class='flex justify-center space-x-4 mt-4 mb-8'>";
    $calendar .= "<a class='  bg-pink-500 text-white px-3 py-1 rounded-lg hover:bg-violet-600' href='?month=" . $prev_month . "&year=" . $prev_year . "'>Prev Month</a> ";
    $calendar .= "<a class='bg-pink-500 text-white px-3 py-1 rounded-lg hover:bg-violet-600' href='?month=" . $current_date_time_bd->format('m') . "&year=" . $current_date_time_bd->format('Y') . "'>Current Month</a> ";
    $calendar .= "<a class='bg-pink-500 text-white px-3 py-1 rounded-lg hover:bg-violet-600' href='?month=" . $next_month . "&year=" . $next_year . "'>Next Month</a>";
    $calendar .= "</div></center>";
    $calendar .= "<table class='w-full text-center border-collapse'>";
    $calendar .= "<tr class='bg-gray-300'>";

    foreach ($days_of_week as $day) {
        $calendar .= "<th class='py-2'>$day</th>";
    }

    $calendar .= "</tr><tr class='bg-gray-100'>";

    if ($day_of_week > 0) {
        for ($i = 0; $i < $day_of_week; $i++) {
            $calendar .= "<td></td>";
        }
    }

    $current_day = 1;
    $month = str_pad($month, 2, "0", STR_PAD_LEFT);

    while ($current_day <= $number_of_days_of_month) {
        if ($day_of_week == 7) {
            $day_of_week = 0;
            $calendar .= "</tr><tr class='bg-gray-100'>";
        }

        $current_day_rel = str_pad($current_day, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$current_day_rel";

        $calendar .= "<td class='py-4'><center><h4 class='mb-2'>$current_day</h4>";

        $timezone = new DateTimeZone('Asia/Dhaka');
        $given_date = new DateTime("$year-$month-$current_day");
        $current_date = new DateTime('now', $timezone);
        $interval = $current_date->diff($given_date);
        $total_days_difference = $interval->days;

        // if ($total_days_difference < 7 && $given_date >= $current_date) {
            $calendar .= "<a class='bg-pink-500 text-white px-2 py-1 rounded-lg hover:bg-violet-600' href='booking_slots.php?date=$date'>Details</a> ";
        // } else {
            // $calendar .= "<button class='bg-gray-400 text-white px-2 py-1 rounded-lg' disabled>Unavailable</button>";
        // }

        $calendar .= "</center></td>";
        $current_day++;
        $day_of_week++;
    }

    if ($day_of_week != 7) {
        $remaining_days = 7 - $day_of_week;
        for ($i = 0; $i < $remaining_days; $i++) {
            $calendar .= "<td></td>";
        }
    }

    $calendar .= "</tr>";
    $calendar .= "</table>";
    echo $calendar;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saloon Calender</title>
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
            color:rgb(0, 0, 0);
        }
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
                <a href="saloon_calender.php"
                    class="block bg-pink-600 py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Calendar</a>
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

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="main-content p-4 flex-1">
                <div class="header flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold" style="margin:0 0 0 40px;">Calender</h2>
                    <div class="notification flex items-center" style="margin-right:40px;">
                        <img src="../image/shop/<?php echo $_SESSION['shop_id']; ?>.jpeg" alt="Notification Icon"
                            class="w-8 h-8 rounded-full mr-2">
                        <h3 class="text-base font-bold"><?php echo $_SESSION['shop_name']; ?>&nbsp;</h3>
                        <a href="../logout.php"><img src="../image/icon/logout.png" alt="logout"
                                class="w-8 h-8 rounded-full mr-2"></a>
                    </div>
                </div>
                <div class="container1">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            $timezone = new DateTimeZone('Asia/Dhaka');
                            $current_date_time_bd = new DateTime('now', $timezone);
                            $dateComponents = getdate();
                            if (isset($_GET['month']) && isset($_GET['year'])) {
                                $month = $_GET['month'];
                                $year = $_GET['year'];
                            } else {
                                $month = $dateComponents['mon'];
                                $year = $dateComponents['year'];
                            }

                            echo build_calendar($month, $year);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>


</body>

</html>