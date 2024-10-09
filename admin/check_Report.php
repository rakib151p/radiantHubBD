<?php
include '../mysql_connection.php';

$limit = 10;


$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search_id = isset($_POST['book']) ? $_POST['book'] : null;

if ($search_id) {

    $report_sql = "SELECT * FROM report_bookings 
                   INNER JOIN bookings ON report_bookings.booking_id = bookings.id 
                   WHERE booking_id = $search_id 
                   LIMIT $limit OFFSET $offset";
} else {

    $report_sql = "SELECT * FROM report_bookings 
                   INNER JOIN bookings ON report_bookings.booking_id = bookings.id 
                   LIMIT $limit OFFSET $offset";
}

// Get total count of records
$total_query = "SELECT COUNT(*) AS total FROM report_bookings";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_reports = $total_row['total'];

// Calculate the total number of pages
$total_pages = ceil($total_reports / $limit);

$report_result = mysqli_query($conn, $report_sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upstats Dashboard</title>
    <!-- <link rel="stylesheet"href="index_style.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: cursive;
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
            /* color: #333; */
            font-weight: bold;
        }

        .main-content {
            flex: 1;
            /* padding: 20px; */
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

        .pagination a {
            padding: 5px 10px;
            background-color: #ddd;
            margin: 0 5px;
            border-radius: 5px;
            text-decoration: none;
        }

        .pagination a.active {
            background-color: #00E081;
            color: white;
        }

        .search-form input {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .search-form button {
            padding: 5px 10px;
            background-color: #00E081;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .pagination {
            margin-right: 1588px;
        }

        .pagination a {
            padding: 5px 10px;
            background-color: #ddd;
            margin: 0 5px;
            border-radius: 5px;
            text-decoration: none;
        }

        .pagination a.active {
            background-color: #00E081;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container flex">
        <div class="sidebar bg-white p-4 shadow-md">
            <div class="logo flex items-center mb-4">
                <!-- <img src="logo.png" alt="Upstats Logo" class="w-10 h-10 mr-2"> -->
                <h1 style="color:rgb(211, 106, 124);font-size:38px;"><a href="../home.php"> Radienthub</a></h1>
            </div>
            <ul class="list-none p-5">
                <div class="hover:bg-pink-500 p-3 transition-colors duration-300 rounded-lg"><a href="index.php"
                        style="color:white;font-size:20px;">Dashboard</a></div>
                <div class="hover:bg-pink-500 p-3 transition-colors duration-300 rounded-lg"><a href="manage_shops.php"
                        style="color:white;font-size:20px;">Manage shops</a></div>
                <div class="hover:bg-pink-500 p-3 transition-colors duration-300 rounded-lg"><a
                        href="manage_customer.php" style="color:white;font-size:20px;">Manage customer</a> </div>
                <div class="hover:bg-pink-500 p-3 transition-colors duration-300 rounded-lg"><a href="#"
                        style="color:white;font-size:20px;">Check Reports</a></div>
                <div class="hover:bg-pink-500 p-3 transition-colors duration-300 rounded-lg"><a
                        href="Managed_legal_notice.php" style="color:white;font-size:20px;">Managed legal notice</a>
                </div>

            </ul>
        </div>

        <div class="main-content p-4 flex-1">
            <div class="header flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold" style="margin:0 0 0 40px;">Check Reports</h2>
                <div class="notification flex items-center" style="margin-right:40px;">
                    <img src="1-change1.jpg" alt="Notification Icon" class="w-8 h-8 rounded-full mr-2">
                    <span class="text-gray-500 mr-2">RadientHub</span>
                    <h3 class="text-base font-bold">Admin</h3>
                </div>
            </div>

            <!-- Search Form -->
            <form class="search-form mb-4" method="POST" action="check_Report.php">
                <input type="text" name="book" placeholder="Search by booking ID"
                    value="<?= htmlspecialchars($search_id) ?>">
                <button type="submit" name="submit">Search</button>
            </form>


            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 p-2">Report ID</th>
                        <th class="border border-gray-300 p-2">booking ID</th>
                        <th class="border border-gray-300 p-2">Shop ID</th>
                        <th class="border border-gray-300 p-2">Reason</th>
                        <th class="border border-gray-300 p-2">Reported Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($report_result) > 0) {

                        while ($row = mysqli_fetch_assoc($report_result)) {
                            echo "<tr>";
                            echo "<td class='border border-gray-300 p-2'>{$row['report_id']}</td>";
                            echo "<td class='border border-gray-300 p-2'>{$row['booking_id']}</td>";
                            echo "<td class='border border-gray-300 p-2'>{$row['shop_id']}</td>";
                            echo "<td class='border border-gray-300 p-2'>{$row['reason']}</td>";
                            echo "<td class='border border-gray-300 p-2'>{$row['reported_at']}</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='11' class='border border-gray-300 p-2 text-center'>No shops found</td></tr>";
                    }
                    ?>

                </tbody>
            </table>
            <div class="pagination mt-4 text-center">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>">Prev</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 ?>">Next</a>
                <?php endif; ?>
            </div>

        </div>


    </div>

</body>

</html>