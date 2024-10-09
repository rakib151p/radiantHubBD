<?php
require '../mysql_connection.php';
session_start();
$shop_id = $_GET['shop_id'];

// Make sure shop_id is provided, either via GET or some other source
if (isset($shop_id)) {
    // Prepare the SQL query to fetch the shop_name based on the shop_id
    $stmt = $conn->prepare("SELECT shop_name FROM barber_shop WHERE shop_id = ?");
    $stmt->bind_param('i', $shop_id); // 'i' denotes integer data type
    $stmt->execute();
    $stmt->bind_result($shop_name); // Bind the result to $shop_name
    $stmt->fetch(); // Fetch the result

    if ($shop_name) {
        // echo "The shop name is: " . $shop_name;
    } else {
        // echo "Shop not found.";
    }

    $stmt->close();
} else {
    echo "Shop ID not provided.";
}
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
            height: 450px;
            margin: 50px 0 0 80px;
            border: 1px solid #ccc;
            border-radius: 10px
        }

        .messaging {
            border: 1px solid #ccc;
            margin: 10px 0 0 0;
            height: 340px;
            border-radius: 10px;
            background-color: white;
            max-height: 700px;
            overflow-y: scroll;
            -ms-overflow-style: none;
            scrollbar-width: none;
            display: flex;
            flex-direction: column;
        }

        .messaging::-webkit-scrollbar {
            display: none;
        }


        .box_title {
            position: absolute;
            font-size: 2rem;
            color: #C71585;
            font-weight: 900;
            left: 410px;
        }

        .shop_name {
            font-weight: 900;
            position: relative;
            font-size: 30px;
            left: 20px;
            top: 10px;
        }

        #text {
            margin: 10px 0 0 240px;
            height: 40px;
            width: 700px;
            border-radius: 20px;
        }





        .message {
            border-radius: 10px;
            max-width: 70%;
            /* Limit width of messages */
            word-wrap: break-word;
            /* Allow long words to wrap */
        }

        .left_message {
            align-self: flex-start;
            /* Align to the left */
        }

        .right_message {
            align-self: flex-end;
            /* Align to the right */
        }



        #submit {
            background-color: #F1C1D7;
            height: 40px;
            width: 80px;
            border-radius: 10px;
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
        <h3 class="box_title">Sending Messages</h3>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <div class="box1">
            <p class="shop_name"><?php echo $shop_name;?></p>
            <div class="messaging">
                <!-- <div class="message left_message bg-blue-100 border border-blue-400 text-700-blue my-5 px-4 py-3">
                    Lorem ipsum dolor sit
                </div>
                <div class="message right_message bg-green-100 border border-green-400 text-700-green my-5 px-4 py-3">
                    Lorem ipsum dolor sit amet consectetur
                </div> -->

            </div>
            <input type="text" placeholder="Text Here" id="text">
            <input type=submit value="Send" id="submit">
        </div>
        <script>
            var shop_id = <?php echo json_encode($shop_id); ?>; // Get the shop_id from PHP

            // Fetch and display messages
            function fetchMessages() {
                $.ajax({
                    url: 'load_message.php',
                    type: 'GET',
                    data: { shop_id: shop_id },
                    dataType: 'json',
                    success: function (response) {
                        if (response.error) {
                            alert(response.error); // Display error
                        } else {
                            $('.messaging').empty(); // Clear the message container
                            response.forEach(function (message) {
                                var messageClass = message.from_whom === 'customer' ? 'right_message bg-green-100' : 'left_message bg-red-100'
                                var messageHtml = `<div class="message ${messageClass}  border my-5 px-4 py-3">${message.message}</div>`;
                                
                                $('.messaging').append(messageHtml);
                            });
                            $('.messaging').scrollTop($('.messaging')[0].scrollHeight); // Scroll to the bottom
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText); // Log error response for debugging
                    }
                });
            }


            // Send message using AJAX
            $('#submit').click(function () {
                var message = $('#text').val();
                if (message.trim() !== '') {
                    $.ajax({
                        url: 'send_message.php',
                        type: 'POST',
                        data: { message: message, shop_id: shop_id },
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {
                                $('#text').val(''); // Clear the input
                                fetchMessages(); // Refresh the message list
                            } else {
                                alert('Error sending message');
                            }
                        }
                    });
                }
            });

            // Poll the server for new messages every 3 seconds
            setInterval(fetchMessages, 3000);

            // Fetch messages on page load
            fetchMessages();
        </script>

    </section>
</body>

</html>