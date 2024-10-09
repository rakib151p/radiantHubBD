<?php
include '../mysql_connection.php';
session_start();
$shop_id = $_SESSION['shop_id'];
$customer_id = $_GET['customer_id'];
$sql_update = "UPDATE `message_table` SET `status` = 1 WHERE `shop_id` = '$shop_id'";
$result = mysqli_query($conn, $sql_update);
if ($result) {
    $_SESSION['message'] = 0;
} else {
    echo "Error updating record: " . mysqli_error($conn);
}
//customer details fetch
$sql_customer = "SELECT * FROM customer WHERE customer_id = ?";
$stmt_customer = $conn->prepare($sql_customer);
$stmt_customer->bind_param("i", $customer_id);
$stmt_customer->execute();
$result_cust = $stmt_customer->get_result();

$customer_name = '';
if ($result_cust->num_rows > 0) {
    $row_cust = $result_cust->fetch_assoc();
    $customer_name = $row_cust['first_name'] . ' ' . $row_cust['last_name'];
} else {
    echo "Error fetching customer record: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
    <!-- Include jQuery for AJAX functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            font-family: cursive;
        }

        #dashboard {
            height: 100vh;
        }

        .sidebar {
            background-color: #1F2937;
        }

        .header {
            background-color: #00E081;
            color: white;
        }

        .message-area {
            display: flex;
            flex-direction: column;
            height: 650px;
            overflow-y: scroll;
        }

        .message-area::-webkit-scrollbar {
            width: 0;
        }

        .message-input {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 30px;
            padding: 10px;
            background-color: white;
        }

        .message-input input {
            border: none;
            outline: none;
            width: 100%;
        }

        .header {
            display: flex;
            background-color: #00E081;
            color: white;
            justify-content: space-between;
            align-items: center;
            margin: 4px 0 0 4px;
            margin-bottom: 20px;
            width: 1625px;
            box-shadow: 1px 1px 5px 3px rgba(0, 0, 0, 0.1);
            /* border:1px solid black; */
            height: 100px;
        }

        .header h2 {
            font-size: 20px;
            font-weight: bold;
            margin: 10px;
            color: rgb(0, 0, 0);
        }
    </style>
</head>
<body class="bg-gray-200 font-sans leading-normal tracking-normal">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar bg-gray-800 text-gray-100 w-64 space-y-6 py-7 px-2" id="dashboard">
            <div class="flex items-center justify-center">
                <span class="text-2xl font-extrabold uppercase" style="margin-right:40px"><a href="../home.php">RadiantHub</a></span>
            </div>
            <nav class="mt-10">
                <a href="saloon_dashboard.php" class="block py-2.5 px-4 rounded-lg mt-2 text-gray-100 hover:bg-pink-500">Dashboard</a>
                <a href="saloon_gallery.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Photo Gallery</a>
                <a href="saloon_calender.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Calendar</a>
                <a href="booking_slots.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Appointments</a>
                <a href="message.php" class="block bg-pink-600 py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500 ">Messages</a>
                <a href="staffs.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Staffs</a>
                <a href="Customers.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Customers</a>
                <a href="Reviews.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Reviews</a>
                <a href="Notifications.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">
                    Notifications&nbsp;
                    <?php if ($_SESSION['notification'] > 0) echo '<span class="right-0 bg-yellow-500 text-yellow-900 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">' . $_SESSION['notification'] . '</span>'; ?>
                </a>
                <a href="saloon_setting.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Settings</a>
            </nav>
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="main-content p-4 flex-1">
                <div class="header flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold ml-10">&nbsp;<?php echo $customer_name; ?> </h2>
                    <div class="notification flex items-center mr-10">
                        <img src="../image/shop/<?php echo $_SESSION['shop_id']; ?>.jpeg" alt="Notification Icon" class="w-8 h-8 rounded-full mr-2">
                        <h3 class="text-base font-bold"><?php echo $_SESSION['shop_name']; ?>&nbsp;</h3>
                        <a href="../logout.php"><img src="../image/icon/logout.png" alt="logout" class="w-8 h-8 rounded-full mr-2"></a>
                    </div>
                </div>

                <div class="message-area px-4 py-6 bg-white rounded-lg shadow">
                    <div id="messageContainer" class="grid grid-cols-12 gap-y-2">
                        <!-- Messages will be loaded here via AJAX -->
                    </div>
                </div>

                <div class="message-input mt-4 px-4 py-2 bg-gray-100">
                    <form id="messageForm">
                        <input type="hidden" name="shop_id" value="<?php echo $shop_id; ?>">
                        <input type="hidden" id="customer_id" name="customer_id" value="<?php echo $customer_id; ?>"> <!-- Change customer_id as per your session -->
                        <input type="text" name="message" class="w-full px-4 py-2 rounded-full text-sm" placeholder="Type your message...">
                        <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-full">Send</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Load messages via AJAX
        function loadMessages() {
            var customer_id = $('#customer_id').val(); // Get the customer_id value
            $.ajax({
                url: 'fetch_messages.php',
                method: 'GET',
                data: { customer_id: customer_id},
                dataType: 'html',
                success: function(response) {
                    $('#messageContainer').html(response);
                }
            });
        }

        // Send message via AJAX
        $('#messageForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'send_message.php',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    loadMessages();
                    $('#messageForm')[0].reset(); // Clear the input field after sending
                }
            });
        });

        // Initial load of messages
        loadMessages();

        // Reload messages every 3 seconds for real-time updates
        setInterval(loadMessages, 3000);
    </script>
</body>

</html>