<?php
require '../mysql_connection.php';
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RadiantHub BD</title>
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
            background-color: #FFFFFF;
            width: 1200px;
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

        .box1 {
            width: 1200px;
            margin: 50px 0 0 80px;

        }

        .box_title {
            position: absolute;
            left: 410px;
            font-size: 2rem;
            color: #C71585;
            font-weight: 900;
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
        <h3 class="box_title">My Notification</h3>
        <div class="box1">
            <?php
            // $shop_id = 121;
            $customer_id = $_SESSION['customer_id'];
            $sql_notify = "SELECT * FROM customer_notifications WHERE customer_id='$customer_id' ORDER BY date_and_time desc";
            $result_notify = mysqli_query($conn, $sql_notify);
            if ($result_notify->num_rows > 0) {
                while ($row_notify = $result_notify->fetch_assoc()) {
                    echo '<div class="bg-pink-100 border border-pink-400 text-pink-700 my-5 px-4 py-3 rounded relative w-4000"
                    role="alert"><h4 class="font-bold text-lg">' . $row_notify['subject'] . '</h4>';
                    echo '<p class="mt-2">' . $row_notify['message'] . '</p>';
                    echo '<p class="mb-0">Admin, RadientHub BD
                                    </p><span id="current-time" class="absolute bottom-0 right-0 mb-2 mr-2 text-sm text-gray-500">' . $row_notify['date_and_time'] . '</span></div>';
                }
            }
            ?>
        </div>
    </section>
</body>

</html>