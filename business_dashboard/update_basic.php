<?php
include '../mysql_connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect POSTed data
    $shop_id = $_SESSION['shop_id']; // Assuming shop_id is stored in session
    $shop_name = $_POST['shop_name'];
    $shop_title = $_POST['shop_title'];
    $shop_info = $_POST['shop_info'];
    $shop_email = $_POST['shop_email'];
    $mobile_number = $_POST['mobile_number'];
    $shop_state = $_POST['shop_state'];
    $shop_city = $_POST['shop_city'];
    $shop_area = $_POST['shop_area'];
    $shop_landmark_1 = $_POST['landmark_1'];
    $shop_landmark_2 = $_POST['landmark_2'];
    $shop_landmark_3 = $_POST['landmark_3'];
    $shop_landmark_4 = $_POST['landmark_4'];
    $shop_landmark_5 = $_POST['landmark_5'];

    // echo "Shop ID: " . $shop_id . "<br>";
    // echo "Shop Name: " . $shop_name . "<br>";
    // echo "Shop Title: " . $shop_title . "<br>";
    // echo "Shop Info: " . $shop_info . "<br>";
    // echo "Shop Email: " . $shop_email . "<br>";
    // echo "Mobile Number: " . $mobile_number . "<br>";
    // echo "Shop State: " . $shop_state . "<br>";
    // echo "Shop City: " . $shop_city . "<br>";
    // echo "Shop Area: " . $shop_area . "<br>";
    // echo "Landmark 1: " . $shop_landmark_1 . "<br>";
    // echo "Landmark 2: " . $shop_landmark_2 . "<br>";
    // echo "Landmark 3: " . $shop_landmark_3 . "<br>";
    // echo "Landmark 4: " . $shop_landmark_4 . "<br>";
    // echo "Landmark 5: " . $shop_landmark_5 . "<br>";
    // SQL Update Query
    $sql_update = "UPDATE barber_shop 
                   SET shop_name = ?, shop_title = ?, shop_info = ?, 
                       shop_email = ?, mobile_number = ?, shop_state = ?, 
                       shop_city = ?, shop_area = ?, shop_landmark_1 = ?, 
                       shop_landmark_2 = ?, shop_landmark_3 = ?, 
                       shop_landmark_4 = ?, shop_landmark_5 = ?
                   WHERE shop_id = ?";

    // Prepare statement
    if ($stmt = $conn->prepare($sql_update)) {
        // Bind parameters to the SQL query
        $stmt->bind_param("sssssssssssssi", $shop_name, $shop_title, $shop_info, $shop_email, $mobile_number, $shop_state, $shop_city, $shop_area, $shop_landmark_1, $shop_landmark_2, $shop_landmark_3, $shop_landmark_4, $shop_landmark_5, $shop_id);

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>alert('You have successfully Updated!');</script>";
            $_SESSION['shop_name'] = $shop_name;
            $_SESSION['shop_title'] = $shop_title;
            $_SESSION['shop_info'] = $shop_info;
            $_SESSION['email'] = $shop_email;
            $_SESSION['mobile_number'] = $mobile_number;
            $_SESSION['shop_state'] = $shop_state;
            $_SESSION['shop_city'] = $shop_city;
            $_SESSION['shop_area'] = $shop_area;
            $_SESSION['shop_landmark_1'] = $shop_landmark_1;
            $_SESSION['shop_landmark_2'] = $shop_landmark_2;
            $_SESSION['shop_landmark_3'] = $shop_landmark_3;
            $_SESSION['shop_landmark_4'] = $shop_landmark_4;
            $_SESSION['shop_landmark_5'] = $shop_landmark_5;
        } else {
            echo "<p>Failed to update shop details: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p>SQL error: " . $conn->error . "</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basic Update</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
    <!-- Include Chart.js for graphs/charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #dashboard {
            height: 100vh;
            scrollbar-width: none;

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
            color: #333;
            font-weight: bold;
        }

        .main-content {
            flex: 1;
            padding: 20px;
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
            font-size: 35px;
            font-weight: bold;
            margin: 0;
            color: rgb(0, 0, 0);
        }

        form {
            /* margin-left: 50px; */
            width: 700px;
        }
        *{
            font-family: cursive;
        }
    </style>
</head>

<body class="bg-gray-200 font-sans leading-normal tracking-normal">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-gray-100 w-64 space-y-6 py-7 px-2" id="dashboard">
            <div class="flex items-center justify-center">
                <span class="text-2xl font-extrabold uppercase" style="margin-right:40px"><a href="../home.php">RadiantHub</a> </span>
            </div>
            <nav class="mt-10">
                <a href="saloon_dashboard.php"
                    class="block py-2.5 px-4 rounded-lg mt-2 text-gray-100 hover:bg-pink-500">Dashboard</a>
                <a href="saloon_gallery.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Photo
                    Gallery</a>
                <a href="saloon_calender.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Calendar</a>
                <a href="booking_slots.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Appointments</a>
                <a href="message.php"
                    class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Message&nbsp;<?php if ($_SESSION['message'] > 0)
                        echo '<span class="right-0 bg-yellow-500 text-yellow-900 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">' . $_SESSION['message'] . '</span>'; ?></a>
                <a href="staffs.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Staffs</a>
                <a href="Customers.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Customers</a>
                <a href="Reviews.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Reviews</a>
                <a href="Notifications.php"
                    class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Notifications&nbsp;<?php if ($_SESSION['notification'] > 0)
                        echo '<span class="right-0 bg-yellow-500 text-yellow-900 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">' . $_SESSION['notification'] . '</span>'; ?></a>
                <a href="saloon_setting.php"
                    class="block bg-pink-600 py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Settings</a>

            </nav>
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="main-content p-4 flex-1">
                <div class="header flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold" style="margin:0 0 0 40px;">Setting</h2>
                    <div class="notification flex items-center" style="margin-right:40px;">
                        <img src="../image/shop/<?php echo $_SESSION['shop_id']; ?>.jpeg" alt="Notification Icon"
                            class="w-8 h-8 rounded-full mr-2">
                        <h3 class="text-base font-bold"><?php echo $_SESSION['shop_name']; ?>&nbsp;</h3>
                        <a href="../logout.php"><img src="../image/icon/logout.png" alt="logout"
                                class="w-8 h-8 rounded-full mr-2"></a>
                    </div>
                </div>
                <div class="flex justify-center items-center min-h-screen">
                    <form action="" method="POST" class="bg-white p-6 rounded-lg shadow-md">
                        <div class="mb-4">
                            <label for="shop_name" class="block text-sm font-medium">Shop Name:</label>
                            <input type="text" name="shop_name" id="shop_name"
                                value="<?php echo $_SESSION['shop_name']; ?>" required
                                class="border border-gray-300 p-2 w-full">
                        </div>
                        <div class="mb-4">
                            <label for="shop_title" class="block text-sm font-medium">Shop Title:</label>
                            <input type="text" name="shop_title" id="shop_title"
                                value="<?php echo $_SESSION['shop_title']; ?>" required
                                class="border border-gray-300 p-2 w-full">
                        </div>
                        <div class="mb-4">
                            <label for="shop_info" class="block text-sm font-medium">Shop Info:</label>
                            <textarea name="shop_info" id="shop_info" rows="3"
                                class="border border-gray-300 p-2 w-full"><?php echo $_SESSION['shop_info']; ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="shop_email" class="block text-sm font-medium">Email:</label>
                            <input type="email" name="shop_email" id="shop_email"
                                value="<?php echo $_SESSION['shop_email']; ?>" required
                                class="border border-gray-300 p-2 w-full">
                        </div>
                        <div class="mb-4">
                            <label for="mobile_number" class="block text-sm font-medium">Mobile Number:</label>
                            <input type="text" name="mobile_number" id="mobile_number"
                                value="<?php echo $_SESSION['mobile_number']; ?>" required
                                class="border border-gray-300 p-2 w-full">
                        </div>
                        <div class="mb-4">
                            <label for="shop_state" class="block text-sm font-medium">State:</label>
                            <input type="text" name="shop_state" id="shop_state"
                                value="<?php echo $_SESSION['shop_state']; ?>" required
                                class="border border-gray-300 p-2 w-full">
                        </div>
                        <div class="mb-4">
                            <label for="shop_city" class="block text-sm font-medium">City:</label>
                            <input type="text" name="shop_city" id="shop_city"
                                value="<?php echo $_SESSION['shop_city']; ?>" required
                                class="border border-gray-300 p-2 w-full">
                        </div>
                        <div class="mb-4">
                            <label for="shop_area" class="block text-sm font-medium">Area:</label>
                            <input type="text" name="shop_area" id="shop_area"
                                value="<?php echo $_SESSION['shop_area']; ?>" required
                                class="border border-gray-300 p-2 w-full">
                        </div>
                        <div class="mb-4">
                            <label for="landmarks" class="block text-gray-700 font-bold mb-2">Landmark Addresses (At
                                least 3 required)
                                *</label>
                            <input type="text" id="landmark1" name="landmark_1"
                                class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600 mb-2"
                                value="<?php echo $_SESSION['shop_landmark_1']; ?>" required />
                            <input type="text" id="landmark2" name="landmark_2"
                                class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600 mb-2"
                                value="<?php echo $_SESSION['shop_landmark_2']; ?>" required />
                            <input type="text" id="landmark3" name="landmark_3"
                                class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600 mb-2"
                                value="<?php echo $_SESSION['shop_landmark_3']; ?>" required />
                            <input type="text" id="landmark4" name="landmark_4"
                                class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600 mb-2"
                                value="<?php echo $_SESSION['shop_landmark_4']; ?>" />
                            <input type="text" id="landmark5" name="landmark_5"
                                class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600 mb-2"
                                value="<?php echo $_SESSION['shop_landmark_5']; ?>" />
                        </div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Update Shop</button>
                    </form>


                </div>


            </div>
        </main>
</body>

</html>