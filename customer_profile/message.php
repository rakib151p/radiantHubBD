<?php
require '../mysql_connection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login/login.php");
    exit();
}

$email = $_SESSION['email'];
$sql = "SELECT * FROM customer WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// If user not found, redirect to login
if (!$row) {
    header("Location: login/login.php");
    exit();
}
// Update all messages to status 1 for the logged-in customer
$customer_id = $_SESSION['customer_id'];

$sql = "UPDATE message_table SET customer_status = 1 WHERE customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);

if ($stmt->execute()) {
    // echo "All messages marked as seen (status set to 1).";
    $_SESSION['unseen']=0;
} else {
    echo "Error updating messages: " . $stmt->error;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Inbox</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F4F4F4;
            font-family: 'Inter', sans-serif;
        }


        .text-gray-700 {
            font-weight: bolder;
        }

        .t {
            font-family: 'Times New Roman', Times, serif;
            font-size: 50px;
            color: #C71585;
        }

        #tittlemnm {
            font-size: x-large;
            color: #C71585;
            margin: 50px 0 0 340px;
        }

        #undernavbar {
            display: flex;
            border-radius: 20px;
            margin-bottom: 80px;
            margin-top: 50px;
            /* Increased margin-bottom for more space */
        }

        #box1 {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            /* margin-bottom: 10px; */
            margin-top: 78px;
            margin-left: 80px;
            height: 360px;
            width: 55%;
        }



        #profileImage {
            margin: 0 20px 0 0;
            height: 200px;
            width: 200px;
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
            margin-top: 40px;
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
        }

        #whiteboard {
            width: 700px;
            margin: 30px 0 40px 100px;
            border-radius: 20px;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
        }

        #profile_edit {
            margin-left: 20px;
            margin-top: 2px;
            padding: 10px 0 0 8px;
            height: auto;
            width: 100%;
            border-radius: 10px;
        }


        #left {
            /* background-color: #C71585; */
            height: 500px;
            width: 45%;
            float: left;
        }

        #right {

            height: 500px;
            width: 55%;
            position: relative;
            bottom: 10px;
            float: right;
        }

        #btn {
            position: relative;
            right: 340px;
        }

        #name {
            margin: 0 0 0 670px;
        }



        .arrow {
            position: absolute;
            right: 100px;
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

            margin-top: 2px;
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

        * {
            font-family: cursive;
        }

        .gri {
            position: relative;
            bottom: 70px;

        }

        #btn {
            position: relative;
            bottom: 70px;
            height: 45px;
        }

        .box_title {
            position: absolute;
            left: 410px;
            font-size: 2rem;
            color: #C71585;
            font-weight: 900;
        }

        #cust_mes {

            height: auto;
            width: 400px;
        }

        #shop_mes {

            height: auto;
            width: 400px;
            margin-left: 630px;

        }

        #box1 {
            height: 900px;
            width: 1100px;
            overflow-y: scroll;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        #box1::-webkit-scrollbar {
            display: none;
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



    <!-- <h2 id=" tittlemnm">My Profile</h2> -->
    <section id="undernavbar">
        <div id="sidebar">
            <a href="#" id="mma">Manage My Account</a>
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
        <h3 class="box_title">My Messages</h3>
        <div class="main-content p-4 flex-1" id="box1">
            
        <?php
                // $shop_id = 121;
                //SQL query to fetch all the conversations among customer and shops
                $customer_id=$_SESSION['customer_id'];
                $sql_user = "SELECT 
                                m.message_id,
                                m.shop_id,
                                m.customer_id,
                                m.message,
                                m.date_and_time,
                                shop.shop_name,
                                shop.mobile_number,
                                shop.shop_email
                            FROM 
                                message_table m
                            JOIN 
                                barber_shop shop ON shop.shop_id = m.shop_id
                            WHERE 
                                m.date_and_time = (
                                    SELECT 
                                        MAX(mt.date_and_time)
                                    FROM 
                                        message_table mt
                                    WHERE 
                                        mt.shop_id = m.shop_id AND mt.customer_id = '$customer_id'
                                ) 
                                AND m.customer_id = '$customer_id'
                            ORDER BY 
                                m.date_and_time DESC";


                // $sql_notify = "SELECT * FROM notifications_by_admin WHERE shop_id='$shop_id' ORDER BY date_time desc";
                $result_notify = mysqli_query($conn, $sql_user);
                if ($result_notify->num_rows > 0) {
                    while ($row_notify = $result_notify->fetch_assoc()) {
                        echo '<a href="reply_message.php?shop_id='.$row_notify['shop_id'].'">';
                        echo '<div class="bg-green-100 border border-green-400 text-green-700 my-5 px-4 py-3 rounded relative"
                        role="alert"><h4 class="font-bold text-lg">' . $row_notify['shop_name'] . '</h4>';
                        // echo '<p class="mt-2">' . $row_notify['message'] . '</p>';
                        echo '<p class="mb-0">' . $row_notify['message'] . '
                                    </p><span id="current-time" class="absolute bottom-0 right-0 mb-2 mr-2 text-sm text-gray-500">' . $row_notify['date_and_time'] . '</span></div></a>';
                    }
                }
                ?>

        </div>
    </section>

</body>

</html>

</html>