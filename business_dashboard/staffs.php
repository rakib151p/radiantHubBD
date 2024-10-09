<?php
include '../mysql_connection.php';
session_start();
$shop_id = $_SESSION['shop_id'];

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    $worker_id = $_POST['worker_id'];
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $mobile_number = $_POST['mobile_number'];
    $image = $_FILES['image']['name'];

    // File upload logic (if new image is uploaded)
    if (!empty($image)) {
        $target_dir = "../image/worker/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        // Update the database with the new image
        $sql_update = "UPDATE shop_worker SET worker_name='$name', email='$email', mobile_number='$mobile_number', worker_picture='$image' WHERE worker_id='$worker_id'";
    } else {
        // If no new image is uploaded, update without changing the image
        $sql_update = "UPDATE shop_worker SET worker_name='$name', email='$email', mobile_number='$mobile_number' WHERE worker_id='$worker_id'";
    }

    // Execute the update query
    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='staffs.php';</script>";
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staffs</title>
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
            color:rgb(0, 0, 0);
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
                <a href="staffs.php" class="block  bg-pink-600 py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Staffs</a>
                <a href="Customers.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Customers</a>
                <a href="Reviews.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Reviews</a>
                <a href="Notifications.php"
                    class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Notifications&nbsp;<?php if ($_SESSION['notification'] > 0)
                        echo '<span class="right-0 bg-yellow-500 text-yellow-900 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">' . $_SESSION['notification'] . '</span>'; ?></a>
                <a href="saloon_setting.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Settings</a>
                
            </nav>
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="main-content p-4 flex-1">
                <div class="header flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold" style="margin:0 0 0 40px;">Our staffs</h2>
                    <div class="notification flex items-center" style="margin-right:40px;">
                        <img src="../image/shop/<?php echo $_SESSION['shop_id'];?>.jpeg" alt="Notification Icon" class="w-8 h-8 rounded-full mr-2">
                        <h3 class="text-base font-bold"><?php echo $_SESSION['shop_name'];?>&nbsp;</h3>
                        <a href="../logout.php"><img src="../image/icon/logout.png" alt="logout" class="w-8 h-8 rounded-full mr-2"></a>
                    </div>
                </div>

                <!-- Members List -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                    <?php
                    $cnt = 1;
                    $sql = "SELECT * FROM shop_worker WHERE shop_id='$shop_id' ORDER BY worker_id";
                    $result_worker = mysqli_query($conn, $sql);
                    while ($row_worker = $result_worker->fetch_assoc()) {
                        echo '<div class="bg-white rounded-lg shadow-md p-6">';
                        echo '<h3 class="text-lg font-semibold text-gray-800 mb-2">Member:' . $cnt++ . '</h3>';
                        echo '<p class="text-gray-600"><span class="font-semibold"><img src="../image/worker/' . $row_worker['worker_picture'] . '" alt="" width="250" height="250"></span></p>';
                        echo '<p class="text-gray-600"><span class="font-semibold">Name:</span>' . $row_worker['worker_name'] . '</p>';
                        echo '<p class="text-gray-600"><span class="font-semibold">Email:</span>' . $row_worker['email'] . '</p>';
                        echo '<p class="text-gray-600"><span class="font-semibold">Phone:</span> ' . $row_worker['mobile_number'] . '</p>';
                        echo '<p class="text-gray-600">Expertise:</p>';
                        echo '<ol class="text-gray-600">'; // Removed <span>
                        $worker_id = $row_worker['worker_id'];
                        $sql_expertise = "SELECT * FROM (SELECT * FROM worker_expertise WHERE worker_id='$worker_id') as a JOIN item_table ON a.item_id=item_table.item_id";
                        $result_item = mysqli_query($conn, $sql_expertise);
                        $tmp = 1;
                        while ($row_item = $result_item->fetch_assoc()) {
                            echo '<li>' . $tmp++ . ' :' . $row_item['item_name'] . '</li>';
                        }
                        echo '</ol>';
                        echo '<div class="flex space-x-4">
                        <button id="btn" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" 
                        onclick="openEditModal(\'' . $worker_id . '\', \'' . addslashes($row_worker['worker_name']) . '\', \'' . addslashes($row_worker['email']) . '\', \'' . $row_worker['mobile_number'] . '\')">EDIT PROFILE</button>
                    </div></div>';
                    }

                    ?>
                </div>
            </div>
        </main>
        <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
            <div class="bg-white rounded-lg shadow-md p-6 w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">Edit Profile</h2>
                <form action="staffs.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="worker_id" name="worker_id">
                    <!-- Other form fields -->
                    <div class="mb-4">
                        <label for="editName" class="block text-gray-700">Full name</label>
                        <input type="text" id="editName" name="full_name"
                            class="w-full p-2 border border-gray-300 rounded mt-1">
                    </div>

                    <div class="mb-4">
                        <label for="editEmail" class="block text-gray-700">Email Address</label>
                        <input type="email" id="editEmail" name="email"
                            class="w-full p-2 border border-gray-300 rounded mt-1">
                        <p id="emailError" style="color:red; display:none;">Please enter a valid email address.</p>
                    </div>

                    <div class="mb-4">
                        <label for="editPhone" class="block text-gray-700">Mobile</label>
                        <input type="text" id="editPhone" name="mobile_number"
                            class="w-full p-2 border border-gray-300 rounded mt-1">
                    </div>

                    <div class="mb-4">
                        <label for="editImage" class="block text-gray-700">Profile Photo</label>
                        <input name="image" type="file" id="editImage"
                            class="w-full p-2 border border-gray-300 rounded mt-1" accept=".jpg,.jpeg,.png">
                    </div>

                    <div class="flex justify-end">
                        <button type="button"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2"
                            onclick="closeEditModal()">Cancel</button>
                        <button type="submit" name="submit"
                            class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded">Save</button>
                    </div>
                </form>

            </div>
        </div>

        <script>
            function openEditModal(worker_id, name, email, mobile) {
                document.getElementById('worker_id').value = worker_id;
                document.getElementById('editName').value = name;
                document.getElementById('editEmail').value = email;
                document.getElementById('editPhone').value = mobile;
                document.getElementById('editModal').classList.remove('hidden');
            }
            document.getElementById('editEmail').addEventListener('input', function () {
                var emailField = document.getElementById('editEmail');
                var emailError = document.getElementById('emailError');
                var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (emailField.value.match(emailPattern)) {
                    emailError.style.display = 'none';
                } else {
                    emailError.style.display = 'block';
                }
            });

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
            }

            function previewImage(event) {
                const reader = new FileReader();
                reader.onload = function () {
                    const output = document.getElementById('profileImage');
                    output.src = reader.result;
                }
                reader.readAsDataURL(event.target.files[0]);
            }

            function saveProfile() {
                const name = document.getElementById('editName').value;
                const email = document.getElementById('editEmail').value;
                const phone = document.getElementById('editPhone').value;
                document.getElementById('profileName').innerText = name || "Please enter your name";
                document.getElementById('profileEmail').innerText = email || "Please enter your email";
                document.getElementById('profilePhone').innerText = phone || "Please enter your mobile";

                closeEditModal();
            }
        </script>
</body>

</html>