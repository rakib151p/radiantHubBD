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

// Set session variables
$customer_id = $_SESSION['customer_id'] = $row['customer_id'];
$first_name = $_SESSION['first_name'] = $row['first_name'];
$last_name = $_SESSION['last_name'] = $row['last_name'];
$email = $_SESSION['email'] = $row['email'];
$password = $_SESSION['password'] = $row['password'];
$mobile_number = $_SESSION['mobile_number'] = $row['mobile_number'];
$gender = $_SESSION['gender'] = $row['gender'];
$district = $_SESSION['district'] = $row['district'];
$upazilla = $_SESSION['upazilla'] = $row['upazilla'];
$area = $_SESSION['area'] = $row['area'];
$_SESSION['latitude'] = $row['latitude'];
$_SESSION['longitude'] = $row['longitude'];

// Calculate registration days
$registration_date = strtotime($row['registration_date']);
$current_time = time();
$registered_days = floor(abs($registration_date - $current_time) / (60 * 60 * 24));
$_SESSION['registered'] = $registered_days;

//unseen message count
$sql_message = "SELECT count(*) as unseen FROM message_table where customer_id='$customer_id' AND customer_status=0";
$result_message = mysqli_query($conn, $sql_message);
$row_message = $result_message->fetch_assoc();
$_SESSION['unseen'] = $row_message['unseen'];
// echo $_SESSION['unseen'];

// Determine the image path
// $existing_image = !empty($row['image']) ? '../image/customer/' . $row['image'] : '../image/customer/default-profile.png';
$existing_image = "../image/customer/" . $row['image'];
if (!file_exists($existing_image)) {
    $existing_image = '../image/customer/default-profile.png';
}

$filePath = $existing_image;
// echo $filePath;
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // echo 'rakib';
    // Initialize variables with existing values
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $mobile_number = $_SESSION['mobile_number'];
    $password = $_SESSION['password'];
    $email = $_SESSION['email'];
    $image_updated = false;
    $new_image_name = $row['image']; // Default to existing image

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $originalName = $_FILES['image']['name'];
        $tempName = $_FILES['image']['tmp_name'];
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        // Validate the image file type
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($extension, $allowed_extensions)) {
            $new_image_name = $row['customer_id'] . '.' . $extension; // Consistent file naming
            $upload_dir = '../image/customer/' . $new_image_name;

            // Move the uploaded file
            if (move_uploaded_file($tempName, $upload_dir)) {
                $image_updated = true;
            } else {
                echo "<script>alert('Failed to upload the image.');</script>";
            }
        } else {
            echo "<script>alert('Invalid image format. Allowed formats: jpg, jpeg, png, gif.');</script>";
        }
    }

    // Update other fields if provided
    if (isset($_POST['first_name']) && !empty(trim($_POST['first_name']))) {
        $first_name = htmlspecialchars(trim($_POST['first_name']));
    }
    if (isset($_POST['last_name']) && !empty(trim($_POST['last_name']))) {
        $last_name = htmlspecialchars(trim($_POST['last_name']));
    }
    if (isset($_POST['mobile_number']) && !empty(trim($_POST['mobile_number']))) {
        $mobile_number = htmlspecialchars(trim($_POST['mobile_number']));
    }
    if (isset($_POST['password']) && strlen($_POST['password']) >= 6) {
        // $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
        $password = $_POST['password'];
    }
    if (isset($_POST['email']) && !empty(trim($_POST['email']))) {
        $new_email = trim($_POST['email']);

        // Validate email format
        if (filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            // Check if the email is already in use by another user
            $sql_check = "SELECT * FROM customer WHERE email = ? AND customer_id != ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("si", $new_email, $_SESSION['customer_id']);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                echo "<script>
                        window.onload = function() {
                            alert('The email address is already in use. Please choose a different one.');
                        };
                     </script>";
            } else {
                $email = $new_email;
                $_SESSION['email'] = $email;
            }
        } else {
            echo "<script>
                    window.onload = function() {
                        alert('Please enter a valid email address.');
                    };
                 </script>";
        }
    }

    // If image was updated, set the new image name
    if ($image_updated) {
        $_SESSION['image'] = $new_image_name;
    }

    // Prepare the UPDATE statement
    $sql_update = "UPDATE customer SET first_name = ?, last_name = ?, mobile_number = ?, email = ?, password = ?, image = ? WHERE customer_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssssi", $first_name, $last_name, $mobile_number, $email, $password, $new_image_name, $_SESSION['customer_id']);

    if ($stmt_update->execute()) {
        // Update session variables
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['mobile_number'] = $mobile_number;
        $_SESSION['password'] = $password;
        $_SESSION['image'] = $new_image_name;

        echo "<script>
                window.onload = function() {
                    alert('Successfully updated.');
                };
             </script>";
    } else {
        echo "<script>
                window.onload = function() {
                    alert('Error updating record: " . mysqli_error($conn) . "');
                };
             </script>";
    }

    // Refresh the page to reflect changes
    header("Refresh:0");
    exit();
}

// Determine the correct image path after potential updates
// $filePath = '../image/customer/' . ($_SESSION['image'] ?? 'default-profile.png');
// if (!file_exists($filePath)) {
//     $filePath = '../image/customer/default-profile.png';
// }
// echo $filePath;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
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

        footer {
            background-color: #2D3748;
            color: #F7FAFC;
            padding: 20px;
            height: 358px;
            background-color: #1F2937;
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
        <h3 class="box_title">My Profile</h3>
        <div id="box1">
            <div id="left">
                <div class="mb-6 flex justify-center">
                    <img id="profileImage" src="<?php echo $filePath; ?>" alt=""
                        class="w-400 h-400 rounded-full mx-auto mb-4">
                </div>

            </div>
            <div id="right">

                <div id="profile_edit">
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-6">
                        <div>
                            <p class="font-semibold">Full name</p>
                            <p id="profileName" class="text-gray-600 text-3">
                                <?php echo $first_name . ' ' . $last_name; ?>
                            </p>
                        </div>
                        <div>
                            <p class="font-semibold">Email Address</p>
                            <p id="profileEmail" class="text-gray-600 text-2"><?php echo $email; ?></p>
                        </div>
                        <div>
                            <p class="font-semibold">Gender</p>
                            <p id="profileGender" class="text-gray-600 text-2"><?php echo $gender; ?></p>
                        </div>
                        <div>
                            <p class="font-semibold">Mobile</p>
                            <p id="profilePhone" class="text-gray-600 text-2"><?php echo $mobile_number; ?></p>
                        </div>
                        <div>
                            <p class="font-semibold">Registered:</p>
                            <p id="profilePhone" class="text-gray-600 text-2">
                                <?php echo $_SESSION['registered'] . ' days'; ?>
                            </p>
                        </div>

                    </div>
                    <div class="flex space-x-4">
                        <button id="btn" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded"
                            onclick="openEditModal()">EDIT PROFILE</button>
                    </div>

                </div>
            </div>

        </div>

    </section>
    <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-md p-6 w-full max-w-md conatin">
            <h2 class="text-xl font-bold mb-4">Edit Profile</h2>
            <form action="My_profile.php" method="POST" enctype="multipart/form-data">
                <div>
                    <label for="editName" class="block text-gray-700">First name</label>
                    <input type="text" id="editName" name="first_name"
                        class="w-full p-2 border border-gray-300 rounded mt-1">
                </div>
                <div>
                    <label for="editName" class="block text-gray-700">Last name</label>
                    <input type="text" id="editName" name="last_name"
                        class="w-full p-2 border border-gray-300 rounded mt-1">
                </div>
                <div>
                    <label for="editEmail" class="block text-gray-700">Email Address</label>
                    <input type="email" id="editEmail" name="email"
                        class="w-full p-2 border border-gray-300 rounded mt-1">
                    <p id="emailError" style="color:red; display:none;">Please enter a valid email address.</p>
                </div>
                <div>
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" id="password" name="password"
                        class="w-full p-2 border border-gray-300 rounded mt-1" minlength="6">
                </div>
                <div>
                    <label for="retype_password" class="block text-gray-700">Re-type password</label>
                    <input type="password" id="retype_password" name="retype_password"
                        class="w-full p-2 border border-gray-300 rounded mt-1">
                </div>
                <div>
                    <label for="editPhone" class="block text-gray-700">Mobile</label>
                    <input type="text" id="editPhone" name="mobile_number"
                        class="w-full p-2 border border-gray-300 rounded mt-1">
                </div>
                <!-- <div class="mb-4">
                    <label for="editGender" class="block text-gray-700">Gender</label>
                    <select id="editGender" class="w-full p-2 border border-gray-300 rounded mt-1">
                        <option value="" disabled selected>Select your gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div> -->
                <div>
                    <label for="editImage" class="block text-gray-700">Profile Photo</label>
                    <input name="image" type="file" id="editImage"
                        class="w-full p-2 border border-gray-300 rounded mt-1" accept=".jpg"
                        onchange="previewImage(event)">
                </div>
                <div class="flex justify-end">
                    <button type="button"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2"
                        onclick="closeEditModal()">Cancel</button>
                    <button type="submit" name="submit"
                        class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded"
                        onclick="saveProfile()">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('retype_password').addEventListener('input', function () {
            const password = document.getElementById('password').value;
            const retypePassword = this.value;

            if (password !== retypePassword) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
        document.getElementById('password').addEventListener('input', function () {
            const password = this.value;
            const retypePassword = document.getElementById('retype_password').value;
            if (password !== retypePassword) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
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

        function openEditModal() {
            document.getElementById('editModal').classList.remove('hidden');
        }

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
            const gender = document.getElementById('editGender').value;

            document.getElementById('profileName').innerText = name || "Please enter your name";
            document.getElementById('profileEmail').innerText = email || "Please enter your email";
            document.getElementById('profilePhone').innerText = phone || "Please enter your mobile";
            document.getElementById('profileGender').innerText = gender || "Please enter your gender";

            closeEditModal();
        }
    </script>

</body>

</html>