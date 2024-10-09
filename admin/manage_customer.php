<?php
include '../mysql_connection.php';

$searchEmail = isset($_GET['email']) ? $_GET['email'] : '';

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 50;  // Maximum records per page
$offset = ($page - 1) * $limit;  // Calculate offset

// Prepare SQL query to search both by email (using LIKE) or by customer_id (using exact match)
$sql = "SELECT * FROM `customer` WHERE `email` LIKE '%$searchEmail%' LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Count total records for pagination
$total_sql = "SELECT COUNT(*) FROM `customer` WHERE `email` LIKE '%$searchEmail%'";
$total_result = mysqli_query($conn, $total_sql);
$total_records = mysqli_fetch_array($total_result)[0];
$total_pages = ceil($total_records / $limit);



if (isset($_GET['customer_id']) && isset($_GET['status'])) {
    $id = $_GET['customer_id'];
    $status = $_GET['status'] == 1 ? 0 : 1;

    $update_sql = "UPDATE customer SET status=$status WHERE customer_id=$id";
    $update_result = mysqli_query($conn, $update_sql);

    if ($update_result) {
        header('Location: manage_customer.php');
        exit();
    } else {
        echo "Error updating shop status!";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit'])) {
        // Get the form values
        $to = mysqli_real_escape_string($conn, $_POST['to']);
        $subject = mysqli_real_escape_string($conn, $_POST['subject']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);

        // Corrected SQL query (no quotes around column names, and string values are enclosed in quotes)
        $legal_sql = "INSERT INTO notifications_by_admin (shop_id, message, date_time, subject) 
                      VALUES ('$to', '$message', NOW(), '$subject')";

        // Execute the query
        if (mysqli_query($conn, $legal_sql)) {
            echo "Notification inserted successfully!";
        } else {
            echo "Error: " . mysqli_error($conn); // Output the error if any
        }
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

        #legal_notice {
            display: none;
            position: fixed;
            top: 50%;
            left: 35%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            /* Ensures it's on top of other elements */
            background-color: white;
            padding: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.5);
            border-radius: 8px;
        }

        #legal_notice-overlay {
            display: none;
            /* Initially hidden */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* Darken background when modal is open */
            z-index: 999;
            /* Ensure it's behind the modal */
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
                        style="color:white;font-size:20px;">Managed legal notice</a></div>

            </ul>
        </div>
        <div class="main-content p-4 flex-1">
            <div class="header flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold" style="margin:0 0 0 40px;">Manage Customer</h2>
                <div class="notification flex items-center" style="margin-right:40px;">
                    <img src="1-change1.jpg" alt="Notification Icon" class="w-8 h-8 rounded-full mr-2">
                    <span class="text-gray-500 mr-2">RadientHub</span>
                    <h3 class="text-base font-bold">Admin</h3>
                </div>
            </div>


            <!-- Search Form -->
            <form class="search-form mb-4" method="GET" action="">
                <input type="text" name="email" placeholder="Search by email or Shop ID"
                    value="<?php echo htmlspecialchars($searchEmail); ?>">
                <button type="submit">Search</button>
            </form>
            <!-- Start Shop Details Table -->




            <!-- Modal Structure   for Shop details -->
            <!-- Modal Structure for Shop details -->
            <div id="customerModal" class="modal" style="display: none;"> <!-- Added display:none to hide initially -->
                <div class="modal-content">
                    <span class="close" style="cursor: pointer;">&times;</span> <!-- Ensure this has the class 'close' -->
                    <h2 style="text-align:center;font-size:30px;font-weight:900;">Customer Details</h2>
                    <div class="customer-info-container">
                        <!-- Shop Information -->
                        <div class="customer-info">
                            <p><strong>Customer First Name:</strong> <span id="customer_f_name"></span></p>
                            <p><strong>Customer Second Name:</strong> <span id="customer_l_name"></span></p>
                            <p><strong>Age:</strong> <span id="age"></span></p>
                            <p><strong>Gender:</strong> <span id="gender"></span></p>
                        </div>
                        <!-- Contact Information -->
                        <div class="contact-info">
                            <p><strong>Email:</strong> <span id="customer_email"></span></p>
                            <p><strong>Mobile Number:</strong> <span id="mobile_number"></span></p>
                        </div>
                        <!-- Location -->
                        <div class="location">
                            <p><strong>Registration:</strong> <span id="registration"></span></p>
                            <p><strong>District:</strong> <span id="district"></span></p>
                            <p><strong>Upazila:</strong> <span id="upazilla"></span></p>
                            <p><strong>Area:</strong> <span id="area"></span></p>
                        </div>
                    </div>
                </div>
            </div>





            <!-- <h2 class="text-2xl font-bold mb-4">Manage Shops</h2> -->
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 p-2">Customer ID</th>
                        <th class="border border-gray-300 p-2">First Name</th>
                        <th class="border border-gray-300 p-2">Last Name</th>
                        <th class="border border-gray-300 p-2">age</th>
                        <th class="border border-gray-300 p-2">Mobile Number</th>
                        <th class="border border-gray-300 p-2">Email</th>
                        <th class="border border-gray-300 p-2">Gender</th>
                        <th class="border border-gray-300 p-2">District</th>
                        <th class="border border-gray-300 p-2">Status</th>
                        <th colspan="5" class="border border-red-300 p-2 bg-blue-500">Take actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td class='border border-gray-300 p-2'>{$row['customer_id']}</td>";
                            echo "<td class='border border-gray-300 p-2'>{$row['first_name']}</td>";
                            echo "<td class='border border-gray-300 p-2'>{$row['last_name']}</td>";
                            echo "<td class='border border-gray-300 p-2'>{$row['age']}</td>";
                            echo "<td class='border border-gray-300 p-2'>{$row['mobile_number']}</td>";
                            echo "<td class='border border-gray-300 p-2'>{$row['email']}</td>";
                            echo "<td class='border border-gray-300 p-2'>{$row['gender']}</td>";
                            echo "<td class='border border-gray-300 p-2'>{$row['district']}</td>";
                            echo "<td class='border border-gray-300 p-2'>";
                            if ($row['status'] == 1) {
                                // For 'Active' status, green button
                                echo "<button class='bg-green-500 text-white font-bold py-1 px-3 rounded'>Active</button>";
                            } else {
                                // For 'Inactive' status, red button
                                echo "<button class='bg-red-500 text-white font-bold py-1 px-3 rounded'>Inactive</button>";
                            }
                            echo "</td>";

                            // View button with data attributes
                            echo "<td class='border border-gray-300 p-2'>
        <button class='view-btn bg-blue-900 text-white font-bold py-1 px-3 rounded' 
            data-id='{$row['customer_id']}' 
            data-firstname='{$row['first_name']}' 
            data-lastname='{$row['last_name']}' 
            data-age='{$row['age']}' 
            data-gender='{$row['gender']}' 
            data-email='{$row['email']}' 
            data-mobilenumber='{$row['mobile_number']}' 
            data-registrationnumber='{$row['registration_date']}' 
            data-district='{$row['district']}' 
            data-upazilla='{$row['upazilla']}' 
            data-area='{$row['area']}'>View</button>
        </td>";

                            // Legal notice button
                            echo "<td class='border border-gray-300 p-2'>
        <button class='legal-notice-btn bg-blue-500 text-white font-bold py-1 px-3 rounded' 
                onclick='showLegalNotice({$row['customer_id']})'>
            Legal Notice
        </button>
      </td>";

                            // Terminate/Reactive link with proper PHP echo
                            echo "<td class='border border-gray-300 p-2'>
        <a href='#' 
           onclick='confirmTermination({$row['customer_id']}, {$row['status']})'
           class='" . ($row['status'] == 1 ? "bg-green-500" : "bg-red-500") . " text-white font-bold py-1 px-3 rounded'>
           " . ($row['status'] == 1 ? 'Terminate' : 'Reactive') . "
        </a>
    </td>";

                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='11' class='border border-gray-300 p-2 text-center'>No shops found</td></tr>";
                    }
                    ?>

                </tbody>
            </table>
            <!-- Legal Notice Form -->
            <!-- Dark Overlay -->
            <div id="overlay" class="fixed inset-0 bg-black opacity-50 z-40 hidden"></div>

            <!-- Legal Notice Form -->
            <div id="legal_notice" class="max-w-md mx-auto bg-white p-4 shadow-md z-50 fixed inset-0 m-auto hidden" style="width: 50%; height: fit-content;">
                <h2 class="text-xl font-semibold mb-4">Legal Notice</h2>
                <form action="manage_customer.php" method="POST">
                    <div class="mb-4">
                        <label for="to" class="block text-sm font-medium">To:</label>
                        <input type="text" id="to" name="To" required class="border border-gray-300 p-2 w-full"
                            disabled>
                        <input type="hidden" id="To" name="to">
                    </div>
                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-medium">Subject:</label>
                        <input type="text" id="subject" name="subject" required class="border border-gray-300 p-2 w-full">
                    </div>
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium">Message:</label>
                        <textarea id="message" name="message" rows="5" required class="border border-gray-300 p-2 w-full"></textarea>
                    </div>
                    <div class="flex justify-between">
                        <button type="submit" class="bg-blue-500 text-white p-2 rounded" name="submit">Send</button>
                        <button type="button" class="bg-red-500 text-white p-2 rounded" onclick="cancelEmail()">Cancel</button>
                    </div>
                </form>
            </div>

            <script>
               
                function cancelEmail() {
                    if (confirm('Are you sure you want to cancel the email?')) {
                        // Clear the input fields
                        document.getElementById('to').value = '';
                        document.getElementById('subject').value = '';
                        document.getElementById('message').value = '';

                        // Hide the form and overlay after cancellation
                        document.getElementById('legal_notice').style.display = 'none';
                        document.getElementById('overlay').classList.add('hidden');
                    }
                }

                // Function to show the legal notice form and overlay
                function showLegalNotice(customer_id) {
                    document.getElementById('to').value = 'customer ID: ' + customer_id;;
                    document.getElementById('To').value = customer_id;;
                    document.getElementById('legal_notice').style.display = 'block';
                    document.getElementById('overlay').classList.remove('hidden');
                }

                // Function to hide the legal notice form and overlay when clicking outside
                window.onclick = function(event) {
                    var legalNoticeDiv = document.getElementById('legal_notice');
                    var overlayDiv = document.getElementById('overlay');
                    if (event.target == overlayDiv) {
                        legalNoticeDiv.style.display = 'none';
                        overlayDiv.classList.add('hidden');
                    }
                }
            </script>








            <!-- End Shop Details Table -->
            <div id="confirmationModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="modalTitle" style="font-weight:900; font-family:cursive;">Cancellation Not Allowed</h3>
                        <p id="modalMessage">Since the remaining time is not more than 24 hours, you cannot cancel now.</p>
                    </div>
                    <div class="modal-footer">
                        <button onclick="closeModal()" style="color:red">Cancel</button>
                        <button id="confirmBtn" onclick="confirmAction()" style="color:red;">OK</button>
                    </div>
                </div>

            </div>

            <script>
                let customerId;
                let actionStatus;

                function confirmTermination(id, status) {
                    customerId = id;
                    actionStatus = status;

                    // Show modal
                    document.getElementById('confirmationModal').style.display = 'block';

                    // Customize modal content based on status (terminate/reactivate)
                    if (status == 1) {
                        document.getElementById('modalTitle').textContent = 'Termination Confirmation';
                        document.getElementById('modalMessage').textContent = 'Are you sure you want to terminate this Customer?';
                    } else {
                        document.getElementById('modalTitle').textContent = 'Reactivation Confirmation';
                        document.getElementById('modalMessage').textContent = 'Are you sure you want to reactivate this Customer?';
                    }
                }

                function closeModal() {
                    document.getElementById('confirmationModal').style.display = 'none';
                }

                function confirmAction() {
                    // Perform AJAX request or form submission
                    window.location.href = `manage_customer.php?customer_id=${customerId}&status=${actionStatus}`;
                }
            </script>



            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Get the modal, close button, and all view buttons
                    var modal = document.getElementById('customerModal');
                    var closeBtn = document.querySelector('.close'); // Ensure it targets the right close button

                    // Add event listener to close button
                    closeBtn.addEventListener('click', function() {
                        modal.style.display = 'none'; // Hide the modal when the close button is clicked
                    });

                    // Close the modal if clicked outside of it
                    window.addEventListener('click', function(event) {
                        if (event.target == modal) {
                            modal.style.display = 'none'; // Hide the modal if clicked outside
                        }
                    });

                    // Listen for clicks on "View" buttons and show the modal
                    document.querySelectorAll('.view-btn').forEach(function(button) {
                        button.addEventListener('click', function() {
                            // Get shop data from the button's data attributes
                            var customerfName = button.getAttribute('data-firstname');
                            var customerlname = button.getAttribute('data-lastname');
                            var age = button.getAttribute('data-age');
                            var gender = button.getAttribute('data-gender');
                            var email = button.getAttribute('data-email');
                            var mobilenumber = button.getAttribute('data-mobilenumber');
                            var registration = button.getAttribute('data-registrationnumber');
                            var district = button.getAttribute('data-district');
                            var upazila = button.getAttribute('data-upazilla');
                            var area = button.getAttribute('data-area');

                            // Populate the modal with data
                            document.getElementById('customer_f_name').textContent = customerfName;
                            document.getElementById('customer_l_name').textContent = customerlname;
                            document.getElementById('age').textContent = age;
                            document.getElementById('gender').textContent = gender;
                            document.getElementById('customer_email').textContent = email;
                            document.getElementById('mobile_number').textContent = mobilenumber;
                            document.getElementById('registration').textContent = registration;
                            document.getElementById('district').textContent = district;
                            document.getElementById('upazilla').textContent = upazila;
                            document.getElementById('area').textContent = area;

                            // Show the modal
                            modal.style.display = 'block';
                        });
                    });
                });
            </script>

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