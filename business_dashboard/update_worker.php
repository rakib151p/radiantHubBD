<?php
include '../mysql_connection.php';
session_start();
$shop_id = $_SESSION['shop_id'];
//fetch all worker
$sql = "SELECT * FROM shop_worker WHERE shop_id = $shop_id";
$reworker = mysqli_query($conn, $sql);
//fetch available items of the shop
$sql = "SELECT * FROM shop_service_table JOIN item_table ON item_table.item_id=shop_service_table.item_id WHERE shop_id='$shop_id'";
$re = mysqli_query($conn, $sql);

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addWorker'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $experience = $_POST['experience'];
    $expertise_array = $_POST['expertise']; // Array of expertise
    $expertise = implode(", ", $expertise_array);
    //finding the max worker id till now
    $sql_worker = "SELECT MAX(worker_id) AS max_worker_id FROM shop_worker";
    $result_max = mysqli_query($conn, $sql_worker);
    $row = mysqli_fetch_assoc($result_max);
    $max_worker_id = $row['max_worker_id'];
    $max_worker_id++;
    //insert all details to worker table
    $worker_insert_details = "INSERT INTO shop_worker(worker_id,shop_id, worker_name, experience, expertise, email, mobile_number)
                              VALUES ($max_worker_id, $shop_id, '$name', '$experience', '$expertise', '$email', '$phone')";
    if (mysqli_query($conn, $worker_insert_details)) {

        foreach ($expertise_array as $expert) {
            // echo $expert.'<br>';
            $sql_update = "SELECT item_id FROM item_table WHERE item_name='$expert'";
            $result_sql_update = mysqli_query($conn, $sql_update);
            $row_result = $result_sql_update->fetch_assoc();
            $item_id = $row_result['item_id'];
            $sql_expert_update = "INSERT INTO worker_expertise(worker_id,item_id) values ($max_worker_id,$item_id)";
            $final_update = mysqli_query($conn, $sql_expert_update);
        }
        // echo '<script>alert("Your New member has been successfully added!");</script>';
        echo '<script>
            alert("Your new member has been successfully added!");
            </script>';
        header('Location: update_worker.php');
        exit();
    } else {
        echo mysqli_error($conn);
    }


}

if (isset($_POST['delete'])) {
    $id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_delete']);

    echo '<script>
        if (confirm("Are you sure you want to delete this worker?")) {
            // User clicked "OK", proceed with deletion
            window.location.href = "update_worker.php?confirm=yes&id_to_delete=' . $id_to_delete . '";
        } else {
            // User clicked "Cancel", redirect back
            window.location.href = "update_worker.php";
        }
    </script>';
}

if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes' && isset($_GET['id_to_delete'])) {
    $id_to_delete = mysqli_real_escape_string($conn, $_GET['id_to_delete']);
    $delete_expertise = "DELETE from worker_expertise WHERE worker_id = $id_to_delete";
    $sql = "DELETE FROM shop_worker WHERE worker_id = $id_to_delete";

    if (mysqli_query($conn, $delete_expertise) && mysqli_query($conn, $sql)) {
        header('Location: update_worker.php');
        exit();
    } else {
        echo 'Query error: ' . mysqli_error($conn);
    }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker update</title>
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

        * {
            font-family: cursive;
        }

        #workerslist {
            position: relative;
            height: 600px;
            width: 700px;
            margin: 50px 0 0 0;

        }

        #addWorkerModal {
            padding: 20px;
            /* Optional padding around the modal */
        }

        #workerForm {
            width: 400px;
        }
    </style>
</head>

<body class="bg-gray-200 font-sans leading-normal tracking-normal">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-gray-100 w-64 space-y-6 py-7 px-2" id="dashboard">
            <div class="flex items-center justify-center">
                <span class="text-2xl font-extrabold uppercase" style="margin-right:40px"><a
                        href="../home.php">RadiantHub</a> </span>
            </div>
            <nav class="mt-10">
                <a href="saloon_dashboard.php"
                    class="block py-2.5 px-4 rounded-lg mt-2 text-gray-100 hover:bg-pink-500">Dashboard</a>
                <a href="saloon_gallery.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Photo
                    Gallery</a>
                <a href="saloon_calender.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Calendar</a>
                <a href="booking_slots.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Appointments</a>
                <a href="my_messages.php" class="block py-2.5 px-4 rounded-lg mt-2 hover:bg-pink-500">Messages</a>
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
                    <h2 class="text-2xl font-bold" style="margin:0 0 0 40px;">Our staffs</h2>
                    <div class="notification flex items-center" style="margin-right:40px;">
                        <img src="../image/shop/<?php echo $_SESSION['shop_id']; ?>.jpeg" alt="Notification Icon"
                            class="w-8 h-8 rounded-full mr-2">
                        <h3 class="text-base font-bold"><?php echo $_SESSION['shop_name']; ?>&nbsp;</h3>
                        <a href="../logout.php"><img src="../image/icon/logout.png" alt="logout"
                                class="w-8 h-8 rounded-full mr-2"></a>
                    </div>
                </div>
                <button id="addWorkerBtn" class="bg-blue-500 text-white px-4 py-2 rounded">Add Worker</button>
                <!-- Members List -->
                <style>
                    #workersList {
                        display: grid;
                        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                        gap: 20px;
                    }
                </style>
                <div id="workersList" class="p-4">
                    <?php
                    while ($row_worker = mysqli_fetch_assoc($reworker)) {
                        if ($row_worker['shop_id'] == $shop_id) {

                            ?>

                            <div class="worker-card p-4 bg-white shadow-lg mb-4 rounded-lg">
                                <p><strong>Name:</strong>
                                    <?php echo $row_worker['worker_name'] . "  " . $row_worker['shop_id']; ?></p>
                                <p><strong>Email:</strong> <?php echo $row_worker['email']; ?></p>
                                <p><strong>Phone:</strong> <?php echo $row_worker['mobile_number']; ?></p>
                                <p><strong>Experience:</strong> <?php echo $row_worker['experience']; ?></p>
                                <p><strong>Expertise:</strong> <?php echo $row_worker['expertise']; ?></p>
                                <form action="" method="POST">
                                    <input type="hidden" name="id_to_delete" value="<?php echo $row_worker['worker_id']; ?>">

                                    <input class="deleteWorker bg-red-500 text-white px-4 py-2 rounded mt-2" type="submit"
                                        name="delete" value="Delete">

                                </form>

                            </div>


                        <?php }
                    } ?>
                </div>
            </div>
        </main>
        <!-- Popup Modal -->
        <!-- Popup Modal -->
        <div id="addWorkerModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg w-full max-w-md h-auto">
                <h3 class="text-lg font-bold mb-4">Add Worker</h3>
                <form id="workerForm" action="update_worker.php" method="POST">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" class="mb-2 border p-2 w-full"
                        placeholder="Enter worker's name" required>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="mb-2 border p-2 w-full"
                        placeholder="Enter worker's email" required>
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" class="mb-2 border p-2 w-full"
                        placeholder="Enter worker's phone" required>
                    <label for="experience">Experience:</label>
                    <input type="text" name="experience" id="experience" class="mb-4 border p-2 w-full"
                        placeholder="Enter worker's experience (e.g., 5 years)" required>
                    <label for="expertise">Expertise:</label>
                    <label for="expertise">Expertise:</label>
                    <select id="expertise" name="expertise[]" class="mb-4 border p-2 w-full" multiple required>
                        <?php while ($row_worker = mysqli_fetch_assoc($re)) { ?>
                            <option value="<?php echo htmlspecialchars($row_worker['item_name']); ?>">
                                <?php echo htmlspecialchars($row_worker['item_name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <input type="hidden" name="shop_id" value="<?php echo $shop_id; ?>">
                    <div class="flex justify-between">
                        <input type="submit" name="addWorker" class="bg-green-500 text-white px-4 py-2 rounded">
                        <button type="button" id="closeModal"
                            class="bg-gray-500 text-white px-4 py-2 rounded">Close</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Workers List -->

        <script>
            // Show the modal
            document.getElementById('addWorkerBtn').addEventListener('click', function () {
                document.getElementById('addWorkerModal').classList.remove('hidden');
            });

            // Hide the modal
            document.getElementById('closeModal').addEventListener('click', function () {
                document.getElementById('addWorkerModal').classList.add('hidden');
            });
        </script>

</body>

</html>