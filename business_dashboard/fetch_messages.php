<?php
include '../mysql_connection.php';
session_start();
$shop_id = $_SESSION['shop_id'];
$customer_id = $_GET['customer_id']; // Assuming customer ID is passed via GET

$query = "SELECT * FROM message_table WHERE shop_id = '$shop_id' AND customer_id = '$customer_id' ORDER BY date_and_time ASC";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    if ($row['from_whom'] == 'customer') {
        // Message from customer (Left Side)
        echo '
        <div class="col-start-1 col-end-8 p-3 rounded-lg">
            <div class="flex flex-row items-center">
                <div class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 flex-shrink-0">C</div>
                <div class="relative ml-3 text-sm bg-white py-2 px-4 shadow rounded-xl">
                    <div>' . htmlspecialchars($row['message']) . '</div>
                    <div class="text-xs text-gray-500 mt-1">' . date('H:i', strtotime($row['date_and_time'])) . '</div>
                </div>
            </div>
        </div>';
    } else {
        // Message from shop (Right Side)
        echo '
        <div class="col-start-6 col-end-13 p-3 rounded-lg">
            <div class="flex items-center justify-start flex-row-reverse">
                <div class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 flex-shrink-0">S</div>
                <div class="relative mr-3 text-sm bg-blue-100 py-2 px-4 shadow rounded-xl">
                    <div>' . htmlspecialchars($row['message']) . '</div>
                    <div class="text-xs text-gray-500 mt-1">' . date('H:i', strtotime($row['date_and_time'])) . '</div>
                </div>
            </div>
        </div>';
    }
}
?>
