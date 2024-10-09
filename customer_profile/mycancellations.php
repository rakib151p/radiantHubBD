<?php
require '../mysql_connection.php';
session_start();
if (isset($_POST['change'])) {
    echo 'change';
    echo $_POST['booking_id'];
}
if (isset($_POST['cancel'])) {
    echo 'cancel';
}
// Fetching customer ID from the session
$customer_id = $_SESSION['customer_id'];

// Fetching the bookings of the logged-in customer
$query = "SELECT * FROM bookings WHERE customer_id = ? AND status='cancelled' ORDER BY date";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My cancellations</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        body {
            background-color: #F4F4F4;
            font-family: 'Inter', sans-serif;
        }


        .text-gray-700 {
            font-weight: bolder;
        }

        .t {
            /* font-family: 'Times New Roman', Times, serif; */
            font-family: cursive;
            font-size: 50px;
            color: #C71585;
        }

        #sidebar {
            flex-direction: column;
            justify-content: center;
            margin: 20px 0 0 30px;
            width: 300px;
        }

        #sidebar ul li a {
            text-decoration: none;
            line-height: 35px;
            font-size: larger;
            margin-left: 20px;
            color: #4A5568;
            transition: color 0.3s;
        }

        #sidebar ul li a:hover {
            color: #C71585;
        }

        #sidebar ul li {
            text-decoration: none;
            list-style-type: none;
        }

        #mma {
            font-size: 27.9px;
            text-decoration: none;
            color: #C71585;
            margin-right: 40px;
        }


        #undernavbar {
            display: flex;
            border-radius: 20px;
            margin-bottom: 80px;
            margin-top: 50px;
            /* Increased margin-bottom for more space */
        }

        #box1 {
            /* background-color: #FFFFFF; */
            width: 61%;
            height: 500px;
            border-radius: 10px;
            margin-left: 180px;
            /* box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px; */

        }

        #box1 h1 {
            font-size: 2rem;
            color: #C71585;
            font-weight: 900;
            position: relative;
            bottom: 20px;
        }

        img {
            height: 100px;
            width: 140px;
            margin: 10px 0 0 60px;
        }

        .tables {
            height: 700px;
            width: 1300px;
            margin-top: 40px;
            margin-left: 80px;
            border-radius: 20px;
        }

        #profile_edit {
            margin-left: 20px;
            margin-top: 2px;
            padding: 10px 0 0 8px;
            height: 300px;
            width: 300px;
            border-radius: 10px;
        }

        .navs {
            background-color: #FFFFFF;
            width: 100vw;
            height: 65px;
            box-shadow: 1px 1px 1px 1px rgba(0, 0, 0, 0.1);
            display: flex;

        }

        .radiant {
            font-size: 42px;
            font-weight: 900;
            margin-left: 90px;
            color: #C71585;
        }

        .login_name {
            font-weight: 900;
        }

        .relation {
            padding-top: 3px;
            padding-left: 10px;
        }

        .together {
            display: flex;
            margin-top: 20px;
            margin-left: 600px;
        }

        li {
            font-size: 21px;
        }

        .arrow {
            position: absolute;
            right: 100px;
        }

        #tittlemnm {
            font-size: x-large;
            color: #C71585;
            margin: 50px 0 0 340px;
        }

        footer {
            background-color: #2D3748;
            color: #F7FAFC;
            padding: 20px;
            width: 100vw;
        }

        footer a {
            color: #E2E8F0;
            transition: color 0.3s;
        }

        footer a:hover {
            color: #C71585;
        }

        footer .grid div {
            margin-bottom: 20px;
        }

        footer .grid div h3 {
            font-size: 1.25rem;
            margin-bottom: 10px;
        }

        footer .grid div p {
            color: #A0AEC0;
        }

        footer .grid div ul li a {
            color: #A0AEC0;
        }

        /* button {
            height: 15px;
            text-align: center;
        } */

        #B {
            font-size: larger;
            font-weight: bolder;
        }

        #name {
            margin: 0 0 0 670px;
        }

        * {
            font-family: cursive;
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="navs">
        <div class="radiant">
            <a href="../home.php">RadiantHub BD</a>
        </div>
        <div class="together">
            <div class="login">
                <a href="
            <?php
            if (!isset($_SESSION['type'])) {
                echo 'login/login.php';
            } else {
                if ($_SESSION['type'] === 'customer') {
                    echo 'My_profile.php';
                } else {
                    echo 'business_dashboard/saloon_dashboard.php';
                }
            }
            ?>" id="name" class="login_name">
                    <?php
                    if (!isset($_SESSION['type'])) {
                        echo 'Login';
                    } else {
                        if ($_SESSION['type'] === 'customer') {
                            echo $_SESSION['first_name'];
                        } else {
                            echo $_SESSION['shop_name'];
                        }
                    }
                    ?>
                </a>
            </div>
            <div class="relation">
                <button class="arrow_bottom" onclick="toggleDropdown()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="dropdownMenu"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg overflow-hidden z-20 hidden">
                    <a href="../home.php" class="text-2xl font-bold hover:text-pink-600"
                        style="font-size:16px;margin:0 0 0 15px;">Home</a><br>
                    <a href="My_profile.php" class="text-2xl font-bold hover:text-pink-600"
                        style="font-size:16px;margin:0 0 0 15px;">Profile</a><br>
                    <a href="addressofbooking.php" class="text-2xl font-bold hover:text-pink-600"
                        style="font-size:16px;margin:0 0 0 15px;">Book & address</a><br>
                    <a href="myreviews.php" class="text-2xl font-bold hover:text-pink-600"
                        style="font-size:16px;margin:0 0 0 15px;">Review</a><br>
                    <a href="mycancellations.php" class="text-2xl font-bold hover:text-pink-600"
                        style="font-size:16px;margin:0 0 0 15px;">Cancel products</a><br>
                    <?php
                    if (isset($_SESSION['type'])) {
                        echo '<a href="../logout.php" class="text-gray-700 hover:text-pink-600 " style="font-size:16px;margin:0 0 0 15px;">Logout</a>';
                    }
                    ?>
                </div>

            </div>

        </div>

    </div>

    <script>
        function toggleDropdown() {
            document.getElementById('dropdownMenu').classList.toggle('hidden');
        }
    </script>
    <!-- <h2 id="tittlemnm">My Booking</h2> -->
    <section id="undernavbar">
        <div id="sidebar">
            <a href="" id="mma">Manage My Account</a>
            <ul>
            <li><a href="My_profile.php">My Profile</a></li>
                <li><a href="addressofbooking.php">Address of Booking</a></li>
                <li><a href="myreviews.php">My Reviews</a></li>
                <li><a href="message.php" id="mymessage">My Messages<?php if ($_SESSION['unseen'] > 0): ?>
                            <span class="unseen-count" style="color: red;">(<?php echo $_SESSION['unseen']; ?>)</span>
                        <?php endif; ?></a></li>
                <li><a href="mybooking.php" id="mma">My booking</a></li>
                <li><a href="mycancellations.php">My Cancellations</a></li>
                <li><a href="Notifications.php">My Notifications</a></li>
            </ul>
            <?php
            if (isset($_SESSION['type'])) {
                echo '<a href="../logout.php" class="text-gray-700 hover:text-pink-600 " style="font-size:30px;margin:10px 0 0 20px; line-height:50px;">Logout</a>';
            }
            ?>
        </div>
        <div class="box1">
            <h2 class="text-3xl font-bold text-pink-700 mb-6" style="position:relative;left:90px;top:10px;">My cancelletion</h2>
            <?php
            if ($result->num_rows > 0) {
                echo '<div class="overflow-x-auto tables">';
                echo '<table class="min-w-full bg-white border border-gray-200 rounded-lg">';
                echo '<thead class="bg-pink-500 text-white">';
                echo '<tr>';
                echo '<th class="px-2 py-1 text-left">Booking Date</th>';
                echo '<th class="px-2 py-1 text-left">Shop</th>';
                echo '<th class="px-2 py-1 text-left">Shop address</th>';
                echo '<th class="px-2 py-1 text-left">Item</th>';
                echo '<th class="px-2 py-1 text-left">Appointment date</th>';
                echo '<th class="px-2 py-1 text-left">Time</th>';
                echo '<th class="px-2 py-1 text-left">Expert</th>';
                echo '<th class="px-2 py-1 text-left">Expert contact</th>';
                echo '<th class="px-2 py-1 text-left">Status</th>';
                // echo '<th class="px-4 py-2 text-left" colspan="2">Action</th>';

                echo '</tr>';
                echo '</thead>';
                echo '<tbody class="text-gray-700">';
                while ($row = $result->fetch_assoc()) {

                    echo '<tr class="border-t">';
                    echo '<td class="px-2 py-1">' . htmlspecialchars($row['when_booked']) . '</td>';
                    //fetching shops
                    $shop_id = $row['shop_id'];
                    $sql_shop = "select shop_name, shop_state, shop_city, shop_area from barber_shop where shop_id='$shop_id'";
                    $result_shop = mysqli_query($conn, $sql_shop);
                    while ($row_shop = $result_shop->fetch_assoc()) {
                        echo '<td class="px-2 py-1">' . htmlspecialchars($row_shop['shop_name']) . '</td>';
                        echo '<td class="px-2 py-1">' . htmlspecialchars($row_shop['shop_state']) . ',' . htmlspecialchars($row_shop['shop_city']) . ',' . htmlspecialchars($row_shop['shop_area']) . '</td>';
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
                        echo '<td class="px-1 py-1">' . htmlspecialchars($row_worker['mobile_number']) . '</td>';
                    }
                    //status
                    echo '<td class="px-2 py-1">' . htmlspecialchars($row['status']) . '</td>';
                    // echo '<td class="px-4 py-2">' . htmlspecialchars($row['status']) . '</td>';
                    // echo '<td class="px-4 py-2">' . htmlspecialchars($row['status']) . '</td>';
                    echo '<form action="" method="POST">';
                    echo '<input type="hidden" name="booking_id" value="' . $row['id'] . '">';
                    // echo '<td class="mr-5 px-0 py-2">' . '<button type="submit" name="cancel" style="padding: 0; border: none; background: none;"><img src="../image/icon/cancel.png" alt="Cancel" style="width: 40px; height: 40px;"></button>' . '</td>';
                    // echo '<td class="px-4 py-2">' . '<button type="submit" name="change" style="padding: 0; border: none; background: none;"><img src="../image/icon/reschedule.png" alt="Submit" style="width: 40px; height: 40px;"></button>' . '</td>';

                    echo '</form>';
                }
                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            } else {
                echo '<p class="text-gray-500">No bookings found.</p>';
            }
            ?>
        </div>




    </section>

    
</body>

</html>