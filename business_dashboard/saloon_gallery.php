<?php
include '../mysql_connection.php';
session_start();

if (isset($_POST['delete_image'])) {
    $img_id = $_POST['img_id'];

    // Get the image path from the database
    $sql = "SELECT image FROM shop_gallery WHERE img_id = '$img_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $filePath = $row['image'];

    // Delete the image file from the server
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // Delete the image entry from the database
    $sql = "DELETE FROM shop_gallery WHERE img_id = '$img_id'";
    if (mysqli_query($conn, $sql)) {
        // Image deleted successfully, redirect back to the gallery page
        // header("Location: saloon_gallery.php");
    } else {
        echo "Database Error: " . mysqli_error($conn);
    }
}

// File upload logic remains unchanged
if (isset($_FILES['image'])) {
    $shop_id = $_POST['shop_id'];
    $image = $_FILES['image'];

    // File upload path
    $targetDir = "../image/shop_gallery/";
    $fileName = basename($image["name"]);
    $targetFilePath = $targetDir . $fileName;

    // Check if the directory exists, if not, create it
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Create directory with appropriate permissions
    }

    // Upload file to server
    if (move_uploaded_file($image["tmp_name"], $targetFilePath)) {
        // Insert image file name into the database
        $sql = "INSERT INTO shop_gallery (shop_id, image) VALUES ('$shop_id', '$targetFilePath')";
        if (mysqli_query($conn, $sql)) {
            // Redirect back to the gallery page
            // header("Location: saloon_gallery.php");
        } else {
            echo "Database Error: " . mysqli_error($conn);
        }
    } else {
        echo "Error uploading file.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our gallery</title>
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
                <a href="saloon_gallery.php"
                    class="block bg-pink-600 py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Photo
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
                <a href="saloon_setting.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Settings</a>

            </nav>
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="main-content p-4 flex-1">
                <div class="header flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold" style="margin:0 0 0 40px;">Gallery</h2>
                    <div class="notification flex items-center" style="margin-right:40px;">
                        <img src="../image/shop/<?php echo $_SESSION['shop_id']; ?>.jpeg" alt="Notification Icon"
                            class="w-8 h-8 rounded-full mr-2">
                        <h3 class="text-base font-bold"><?php echo $_SESSION['shop_name']; ?>&nbsp;</h3>
                        <a href="../logout.php"><img src="../image/icon/logout.png" alt="logout"
                                class="w-8 h-8 rounded-full mr-2"></a>
                    </div>
                </div>
                <div class="mb-4">
                    <!-- Add Image Button -->
                    <button id="addImageBtn"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        + Add Image
                    </button>
                </div>

                <!-- Hidden form for uploading images -->
                <form id="uploadForm" action="" method="POST" enctype="multipart/form-data"
                    class="hidden">
                    <input type="hidden" name="shop_id" value="<?php echo $_SESSION['shop_id']; ?>">
                    <input type="file" name="image" id="imageInput" accept="image/*" class="hidden"
                        onchange="this.form.submit()">
                </form>

                <div class="grid grid-cols-2 gap-4">
    <?php
    $shop_id = $_SESSION['shop_id'];
    $sql = "SELECT * FROM shop_gallery WHERE shop_id = '$shop_id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="bg-white rounded-lg shadow-lg overflow-hidden relative">';
            echo '<img src="' . $row['image'] . '" alt="Shop Image" class="w-full h-450px object-cover">';
            
            // Delete button
            echo '<form action="" method="POST" style="position: absolute; top: 10px; right: 10px;">';
            echo '<input type="hidden" name="img_id" value="' . $row['img_id'] . '">';
            echo '<button type="submit" name="delete_image" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 rounded">Delete</button>';
            echo '</form>';

            echo '</div>';
        }
    } else {
        echo '<p>No images available for this shop.</p>';
    }
    ?>
</div>

            </div>
        </main>

        <script>
    // When the add image button is clicked, trigger the file input dialog
    document.getElementById('addImageBtn').addEventListener('click', function() {
        document.getElementById('imageInput').click();
    });
</script>
</body>

</html>