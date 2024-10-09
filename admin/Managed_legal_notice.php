<?php
include '../mysql_connection.php';

$searchEmail = isset($_GET['email']) ? $_GET['email'] : '';

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 50;  // Maximum records per page
$offset = ($page - 1) * $limit;  // Calculate offset

// Prepare SQL query to search both by email (using LIKE) or by customer_id (using exact match)
if(strlen($searchEmail)>0){
    $sql = "SELECT * FROM `notifications_by_admin` WHERE `shop_id`='$searchEmail' LIMIT $limit OFFSET $offset";
}else{
    $sql = "SELECT * FROM `notifications_by_admin` LIMIT $limit OFFSET $offset";
}

$result = mysqli_query($conn, $sql);

// Count total records for pagination
$total_sql = "SELECT COUNT(*) FROM `notifications_by_admin`";
$total_result = mysqli_query($conn, $total_sql);
$total_records = mysqli_fetch_array($total_result)[0];
$total_pages = ceil($total_records / $limit);





//delet
if (isset($_POST['delete'])) {

    $id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_delete']);


    $sql = "DELETE FROM notifications_by_admin WHERE notification_id = '$id_to_delete'";

    if (mysqli_query($conn, $sql)) {

        header('Location: Managed_legal_notice.php');
        exit();
    } else {
        echo 'Query error: ' . mysqli_error($conn);
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upstats Dashboard</title>
    <!-- <link rel="stylesheet" href="manage_customer.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
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

        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            background-color: rgba(0, 0, 0, 0.7);
            /* Black background with opacity */
            overflow: auto;
        }

        /* Modal Content/Box */
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
            /* Could be more or less, depending on screen size */
            border-radius: 8px;
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

        /* Close button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            text-align: center;
        }

        .modal-header,
        .modal-footer {
            margin-bottom: 10px;
        }

        .modal-footer {
            display: flex;
            justify-content: space-between;
        }

        * {
            font-family: cursive;
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
                <div class="hover:bg-pink-500 p-3 transition-colors duration-300 rounded-lg"><a href="manage_customer.php"
                        style="color:white;font-size:20px;">Manage customer</a> </div>
                <div class="hover:bg-pink-500 p-3 transition-colors duration-300 rounded-lg"><a href="check_Report.php"
                        style="color:white;font-size:20px;">Check Reports</a></div>
                <div class="hover:bg-pink-500 p-3 transition-colors duration-300 rounded-lg"><a href="Managed_legal_notice.php"
                        style="color:white;font-size:20px;">Manage legal notice</a></div>
            </ul>
        </div>
        <div class="main-content p-4 flex-1">
            <div class="header flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold" style="margin:0 0 0 40px;">Managed Legal Notice</h2>
                <div class="notification flex items-center" style="margin-right:40px;">
                    <img src="1-change1.jpg" alt="Notification Icon" class="w-8 h-8 rounded-full mr-2">
                    <span class="text-gray-500 mr-2">RadientHub</span>
                    <h3 class="text-base font-bold">Admin</h3>
                </div>
            </div>


            <!-- Search Form -->
            <form class="search-form mb-4" method="GET" action="">
                <input type="text" name="email" placeholder="Search by Shop ID"
                    value="<?php echo htmlspecialchars($searchEmail); ?>">
                <button type="submit">Search</button>
            </form>
            <!-- Start Shop Details Table -->



            <!-- <h2 class="text-2xl font-bold mb-4">Manage Shops</h2> -->
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 p-2">Notification ID</th>
                        <th class="border border-gray-300 p-2">Shop ID</th>
                        <th class="border border-gray-300 p-2">Message</th>
                        <th class="border border-gray-300 p-2">Date Time</th>
                        <th class="border border-gray-300 p-2">Subject</th>
                        <th class="border border-gray-300 p-2">Status</th>
                        <th class="border border-red-300 p-2 bg-blue-500">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td class='border border-gray-300 p-2'>{$row['notification_id']}</td>";
                            echo "<td class='border border-gray-300 p-2'>{$row['shop_id']}</td>";
                            echo "<td class='border border-gray-300 p-2'>{$row['message']}</td>";
                            echo "<td class='border border-gray-300 p-2'>{$row['date_time']}</td>";
                            echo "<td class='border border-gray-300 p-2'>{$row['subject']}</td>";

                            // Status button logic
                            if ($row['status'] == 1) {
                                echo "<td class='border border-gray-300 p-2'>
                    <button class='bg-green-500 text-white px-4 py-1 rounded'>Seen</button>
                  </td>";
                            } else {
                                echo "<td class='border border-gray-300 p-2'>
                    <button class='bg-red-500 text-white px-4 py-1 rounded'>Unseen</button>
                  </td>";
                            }

                            // Delete button
                            echo "<td class='border border-gray-300 p-2'>
                <form action='' method='POST'>
                    <input type='hidden' name='id_to_delete' value='{$row['notification_id']}'>
                    <input type='submit' name='delete' value='Delete' class='bg-blue-500 text-white px-4 py-1 rounded'>
                </form>
              </td>";

                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>


            <!-- Pagination Links -->
            <div class="pagination mt-4">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&email=<?php echo $searchEmail; ?>">&laquo; Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&email=<?php echo $searchEmail; ?>"
                        class="<?php echo $i == $page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&email=<?php echo $searchEmail; ?>">Next &raquo;</a>
                <?php endif; ?>
            </div>

        </div>


    </div>

</body>

</html>