
<?php
require '../mysql_connection.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_SESSION['customer_id'];
    $shop_id = $_POST['shop_id'];
    $shop_name = '';
    $worker_id = $_POST['worker_id'];
    $item_id = $_POST['item_id'];
    $selected_time_id = $_POST['selected_time_id'];
    $selected_time = date("g:i A", strtotime($_POST['selected_time_id']));
    $selected_date = date("d M Y", strtotime($_POST['selected_date_id']));
    $date = new DateTime($selected_date);
    // $date->modify('+1 day');
    $selected_date = $date->format('Y-m-d');
    
    $item_name = '';
    $item_price = '';
    $item_description = '';
    $worker_name = '';
    $customer_name = '';
    $customer_email = '';
    $customer_mobile = '';
    $shop_city = '';
    $shop_state = '';
    $shop_rating = '';
    $worker_experience = '';
    $worker_expertise = '';
    $shop_mobile = '';

    //SQL query to fetch all the details after a successful booking
    $sql = "SELECT 
                item_table.item_name,
                item_table.item_description,
                shop_service_table.item_price,
                barber_shop.shop_name,
                barber_shop.shop_city,
                barber_shop.shop_state,
                barber_shop.shop_rating,
                barber_shop.mobile_number AS shop_mobile,
                shop_worker.worker_name,
                shop_worker.experience,
                shop_worker.expertise,
                CONCAT(customer.first_name, ' ', customer.last_name) AS customer_name,
                customer.email AS customer_email,
                customer.mobile_number AS customer_mobile
            FROM 
                shop_service_table
            JOIN 
                barber_shop 
                ON barber_shop.shop_id = shop_service_table.shop_id
            JOIN 
                item_table 
                ON item_table.item_id = shop_service_table.item_id
            JOIN 
                shop_worker 
                ON shop_worker.worker_id = '$worker_id' 
                AND shop_worker.shop_id = shop_service_table.shop_id
            JOIN 
                customer 
                ON customer.customer_id = '$customer_id'
            WHERE 
                shop_service_table.shop_id = '$shop_id' 
                AND shop_service_table.item_id = '$item_id'";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $item_name = $row['item_name'];
            $item_price = $row['item_price'];
            $item_description = $row['item_description'];
            $shop_name = $row['shop_name'];
            $shop_city = $row['shop_city'];
            $shop_state = $row['shop_state'];
            $shop_rating = $row['shop_rating'];
            $shop_mobile = $row['shop_mobile'];
            $worker_name = $row['worker_name'];
            $worker_experience = $row['experience'];
            $worker_expertise = $row['expertise'];
            $customer_name = $row['customer_name'];
            $customer_email = $row['customer_email'];
            $customer_mobile = $row['customer_mobile'];
            // echo "Item Name: " . htmlspecialchars($item_name) . "<br>";
            // echo "Item Price: " . htmlspecialchars($item_price) . "<br>";
            // echo "Item Description: " . htmlspecialchars($item_description) . "<br>";
            // echo "Shop Name: " . htmlspecialchars($shop_name) . "<br>";
            // echo "Shop City: " . htmlspecialchars($shop_city) . "<br>";
            // echo "Shop State: " . htmlspecialchars($shop_state) . "<br>";
            // echo "Shop Rating: " . htmlspecialchars($shop_rating) . "<br>";
            // echo "Shop Mobile: " . htmlspecialchars($shop_mobile) . "<br>";
            // echo "Worker Name: " . htmlspecialchars($worker_name) . "<br>";
            // echo "Worker Experience: " . htmlspecialchars($worker_experience) . " years<br>";
            // echo "Worker Expertise: " . htmlspecialchars($worker_expertise) . "<br>";
            // echo "Customer Name: " . htmlspecialchars($customer_name) . "<br>";
            // echo "Customer Email: " . htmlspecialchars($customer_email) . "<br>";
            // echo "Customer Mobile: " . htmlspecialchars($customer_mobile) . "<br>";
        }
    } else {
        echo 'No results found.';
    }

    // $date = $_POST['selected_date_id'];
    $date=$selected_date;
    $insertSql = "INSERT INTO bookings (date, item_id, status, shop_id, customer_id, booking_time, worker_id) 
                      VALUES ('$date', '$item_id', 'pending', '$shop_id', '$customer_id', '$selected_time_id', '$worker_id')";
    if (isset($_SESSION['check']) && mysqli_query($conn, $insertSql)) {
        // Successfully inserted booking
        $bookingId = mysqli_insert_id($conn); // Get the last inserted ID if needed
        unset($_SESSION['check']);
        $message = "You have a new booking for " . $item_name . ' on ' . $date . ' at ' . $selected_time_id;
        $notify_sql = "INSERT INTO `notifications_by_admin`(`shop_id`, `message`, `subject`) VALUES ('$shop_id','$message','New booking')";
        $result_booking = mysqli_query($conn, $notify_sql);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - RadiantHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <!-- Container for the success message -->
    <div class="min-h-screen flex flex-col justify-center items-center">
        <div class="bg-pink-100 rounded-lg shadow-lg p-10 max-w-md text-center">
            <!-- Success Icon -->
            <div class="text-green-500 mb-6">
                <img src="../image/icon/success.png" alt="" class="w-16 h-16 mx-auto">
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Order Confirmed!</h1>
            <p class="text-gray-600 mb-6">
                Thank you for choosing RadiantHub. Your appointment has been successfully booked at your selected salon.
            </p>
            <div class="bg-gray-50 rounded-lg p-4 text-left mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Booking Details</h2>
                <p><strong>Salon Name:</strong> <?php echo $shop_name; ?> (<?php echo $shop_city; ?>,
                    <?php echo $shop_state; ?>)</p>
                <p><strong>Service:</strong> <?php echo $item_name; ?> (<?php echo $item_description; ?>)</p>
                <p><strong>Date:</strong> <?php echo $selected_date; ?></p>
                <p><strong>Time:</strong> <?php echo $selected_time; ?></p>
                <p><strong>Worker Name:</strong> <?php echo $worker_name; ?></p>
                <p><strong>Worker Expertise:</strong> <?php echo $worker_expertise; ?></p>
                <p><strong>Total Amount:</strong> BDT <?php echo $item_price; ?></p>
            </div>
            <a href="../home.php"
                class="mr-2 inline-block px-6 py-2 bg-pink-400 text-white font-semibold rounded-lg shadow-md hover:bg-pink-600 transition duration-300">Back
                to Home</a>
            <?php
            $pdfUrl = "payment_receipt.php?" .
                "shop_name=" . urlencode($shop_name) .
                "&item_name=" . urlencode($item_name) .
                "&selected_date=" . urlencode($selected_date) .
                "&selected_time=" . urlencode($selected_time) .
                "&item_price=" . urlencode($item_price) .
                "&customer_name=" . urlencode($customer_name) .
                "&customer_email=" . urlencode($customer_email) .
                "&customer_mobile=" . urlencode($customer_mobile);
            ?>
            <a href="<?php echo $pdfUrl; ?>"
                class="ml-2 inline-block px-6 py-2 bg-pink-400 text-white font-semibold rounded-lg shadow-md hover:bg-pink-600 transition duration-300">Print
                payment slip</a>
        </div>
    </div>

</body>

</html>