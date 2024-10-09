<?php
include '../mysql_connection.php';
session_start();
date_default_timezone_set('Asia/Dhaka');
$date = date('Y-m-d');
if ($_SERVER['REQUEST_METHOD'] == 'GET'&&isset($_GET['date'])) {
    $date = $_GET['date'];
}
// echo $date;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saloon Appointments</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
    <!-- Include Chart.js for graphs/charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .flex-1 {
            overflow-y: auto;
            /* Allow scrolling inside the main content area */
        }

        #dashboard {
            height: 100vh;
            overflow-y: auto;
            /* Enable scrolling for the sidebar if needed */
        }


        .sidebar {
            background-color: white;
            margin: 30px 0 0 50px;
            display: grid;
            width: 1200px;
            height: 430px;
            grid-template-columns: repeat(auto-fill, minmax(200px, auto));
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            color: #333;
            font-weight: bold;
            font-size: 2rem;
        }

        .container {
            display: flex;
            gap: 20px;
            padding: 30px;
        }

        .schedule-container {
            width: 250px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
        }

        .worker-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 20px;
            color: #333;
        }

        .time-slot {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .time-slot h5 {
            margin: 0;
            font-size: 16px;
            color: #444;
        }

        .time-slot p {
            margin: 8px 0;
            font-size: 14px;
            color: #777;
        }

        .book-button,
        .cancel-button {
            background-color: #4caf50;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            margin-bottom: 5px;
            transition: all 0.2s ease-in-out;
        }

        .cancel-button {
            background-color: #f44336;
        }

        .book-button:hover,
        .cancel-button:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .time-slot.booked {
            background-color: #e0f2f7;
        }

        .workers_photo {
            width: 210px;
            height: 210px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }

        .name {
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
            color: #444;
            text-align: center;
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
            margin-left: 4px;
            margin-top: 3.5px;
        }

        .header h2 {
            font-size: 35px;
            font-weight: bold;
            margin: 0;
            color:rgb(0, 0, 0);
        }

        button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>

<body class="bg-gray-200 font-sans leading-normal tracking-normal">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-gray-100 w-64 space-y-6 py-7 px-2" id="dashboard">
            <div class="flex items-center justify-center">
                <span class="text-2xl font-extrabold uppercase" style="margin-right:40px">RadiantHub</span>
            </div>
            <nav class="mt-10">
                <a href="saloon_dashboard.php"
                    class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500 text-gray-100">Dashboard</a>
                <a href="saloon_gallery.php" class="block py-2.5 px-4 rounded-lg mt-2 hovar:bg-pink-500">Photo
                    Gallery</a>
                <a href="saloon_calender.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Calendar</a>
                <a href="booking_slots.php" class="block py-2.5 px-4 rounded-lg mt-2 bg-pink-600">Appointments</a>
                <a href="staffs.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Staffs</a>
                <a href="Customers.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Customers</a>
                <a href="Reviews.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Reviews</a>
                <a href="Notifications.php"
                    class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Notifications&nbsp;<?php if ($_SESSION['notification'] > 0)
                        echo '<span class="right-0 bg-yellow-500 text-yellow-900 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">' . $_SESSION['notification'] . '</span>'; ?></a>
                <a href="saloon_setting.html" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Settings</a>
                <a href="Help&Support.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Help &
                    Support</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <div class="main-content p-4 flex-1">
                <div class="header flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold" style="margin:0 0 0 40px;">Workers Schedule</h2>
                    <div class="notification flex items-center" style="margin-right:40px;">
                        <img src="../image/shop/<?php echo $_SESSION['shop_id']; ?>.jpeg" alt="Notification Icon"
                            class="w-8 h-8 rounded-full mr-2">
                        <h3 class="text-base font-bold"><?php echo $_SESSION['shop_name']; ?>&nbsp;</h3>
                        <a href="../logout.php"><img src="../image/icon/logout.png" alt="logout"
                                class="w-8 h-8 rounded-full mr-2"></a>
                    </div>
                </div>
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                    <div class="container">
                        <!-- Jishan's Schedule -->
                        <?php
                        $shop_id = $_SESSION['shop_id'];
                        $sql_worker = "SELECT* FROM shop_worker WHERE shop_id='$shop_id'";
                        $result_worker = mysqli_query($conn, $sql_worker);
                        while ($row_worker = $result_worker->fetch_assoc()) {

                            echo '<div class="schedule-container">
                            <img src="../image/worker/' . $row_worker['worker_picture'] . '" alt="Jishan\'s Photo" class="workers_photo">
                            <h3 class="worker-title">' . $row_worker['worker_name'] . '\'s Schedule</h3>';
                            $worker_id = $row_worker['worker_id'];
                            $sql_schedule = "SELECT * FROM bookings WHERE date='$date' AND worker_id='$worker_id'";
                            $result_schedule = mysqli_query($conn, $sql_schedule);
                            while ($row_schedule = $result_schedule->fetch_assoc()) {
                                $book_time = new DateTime($row_schedule['booking_time']);
                                $end_time = clone $book_time;
                                $end_time->modify('+1 hour');
                                $current_time = new DateTime();

                                // Determine if "Completed" button should be enabled
                                $is_completed_enabled = $current_time > $end_time && $row_schedule['status'] !== 'completed' ? 'enabled' : 'disabled';

                                // Pass booking ID for actions
                                $booking_id = $row_schedule['id']; // Ensure 'booking_id' exists in your bookings table
                        
                                echo '<div class="time-slot">
                                    <h5>' . $book_time->format('H:i') . ' to ' . $end_time->format('H:i') . '</h5>
                                    <p>&nbsp;</p>
                                    <button class="book-button" data-booking-id="' . $booking_id . '" ' . ($is_completed_enabled == 'enabled' ? '' : 'disabled') . '>Completed</button>
                                    <button class="report-button" data-booking-id="' . $booking_id . '">Report</button>
                                    <button class="see-more-button" data-booking-id="' . $booking_id . '">See details</button>
                                </div>';
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                </main>
                <!-- See More Modal -->
                <div id="seeMoreModal"
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div class="bg-white rounded-lg w-1/4 p-6">
                        <h2 class="text-xl font-bold mb-4">Booking Details</h2>
                        <div id="bookingDetails">
                            <!-- Booking details will be loaded here via AJAX -->
                        </div>
                        <button id="closeSeeMore" class="mt-4 px-4 py-2 bg-red-500 text-white rounded">Close</button>
                    </div>
                </div>

                <!-- Report Modal -->
                <div id="reportModal"
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div class="bg-white rounded-lg w-1/3 p-6">
                        <h2 class="text-xl font-bold mb-4">Report Booking</h2>
                        <form id="reportForm">
                            <input type="hidden" id="reportBookingId" name="booking_id">
                            <textarea id="reportReason" name="reason" class="w-full border rounded p-2" rows="4"
                                placeholder="Enter your report reason"></textarea>
                            <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Submit
                                Report</button>
                            <button type="button" id="closeReport"
                                class="mt-4 px-4 py-2 bg-red-500 text-white rounded">Cancel</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Handle Completed Button Click
                document.querySelectorAll('.book-button').forEach(button => {
                    button.addEventListener('click', () => {
                        const bookingId = button.getAttribute('data-booking-id');
                        // alert(bookingId);
                        if (confirm('Are you sure you want to mark this booking as completed?')) {
                            fetch('update_status.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ booking_id: bookingId })
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        alert('Booking marked as completed.');
                                        button.disabled = true;
                                        // Optionally, update UI elements as needed
                                    } else {
                                        alert('Error: ' + data.message);
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        }
                    });
                });

                // Handle See More Button Click
                document.querySelectorAll('.see-more-button').forEach(button => {
                    button.addEventListener('click', () => {
                        const bookingId = button.getAttribute('data-booking-id');
                        fetch('get_booking_details.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ booking_id: bookingId })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const details = data.details;
                                    let html = `<p><strong>Customer Name:</strong> ${details.first_name}&nbsp;${details.last_name}</p>
                                <p><strong>Service:</strong> ${details.item_name}</p>
                                <p><strong>Price:</strong> ${details.item_price}</p>
                                <p><strong>Booked for:</strong> ${details.booked_time}&nbsp;${details.booked_date}</p>
                                <p><strong>Status:</strong> ${details.status}</p>
                                <p><strong>Appointed at:</strong> ${details.booking_time}</p>`;
                                    document.getElementById('bookingDetails').innerHTML = html;
                                    document.getElementById('seeMoreModal').classList.remove('hidden');
                                } else {
                                    alert('Error: ' + data.message);
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    });
                });

                // Handle Report Button Click
                document.querySelectorAll('.report-button').forEach(button => {
                    button.addEventListener('click', () => {
                        const bookingId = button.getAttribute('data-booking-id');
                        document.getElementById('reportBookingId').value = bookingId;
                        document.getElementById('reportModal').classList.remove('hidden');
                    });
                });

                // Close See More Modal
                document.getElementById('closeSeeMore').addEventListener('click', () => {
                    document.getElementById('seeMoreModal').classList.add('hidden');
                });

                // Close Report Modal
                document.getElementById('closeReport').addEventListener('click', () => {
                    document.getElementById('reportModal').classList.add('hidden');
                });

                // Handle Report Form Submission
                document.getElementById('reportForm').addEventListener('submit', (e) => {
                    e.preventDefault();
                    const bookingId = document.getElementById('reportBookingId').value;
                    const reason = document.getElementById('reportReason').value.trim();

                    if (reason === '') {
                        alert('Please enter a reason for the report.');
                        return;
                    }

                    fetch('report_booking.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ booking_id: bookingId, reason: reason })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Report submitted successfully.');
                                document.getElementById('reportModal').classList.add('hidden');
                                document.getElementById('reportForm').reset();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        </script>

</body>

</html>