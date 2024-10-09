<?php
require '../mysql_connection.php';
session_start();
// if (isset($_POST['change'])) {
//     echo 'change';
//     echo $_POST['booking_id'];
// }
if (isset($_POST['cancel'])) {
    $review_id = $_POST['review_id'];
    // echo $review_id;
    // Delete review from the database
    $sql_review = "DELETE FROM review_shop WHERE review_id='$review_id'";
    $result_review = mysqli_query($conn, $sql_review);
    if ($result_review) {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>' . "<script>
        Swal.fire({
            icon: 'success',
            title: 'Deleted successfully.'
        });
        </script>";
    } else {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>' . "<script>
        Swal.fire({
            icon: 'warning',
            title: 'Error deleting review.',
            text: 'Something went wrong.',
            confirmButtonText: 'OK'
        });
        </script>";
    }
}
// Fetching customer ID from the session
$customer_id = $_SESSION['customer_id'];

// Fetching the bookings of the logged-in customer
$query = "SELECT * FROM review_shop WHERE customer_id = ? ORDER BY date_and_time";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        body {
            background-color: #F4F4F4;
            font-family: 'Inter', sans-serif;
        }

        .text-gray-700 {
            font-weight: bolder;
        }

        * {
            font-family: cursive;
        }

        .t {
            /* font-family: 'Times New Roman', Times, serif; */
            font-size: 50px;
            color: #C71585;
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
            margin-right: 40px;
        }



        #undernavbar {
            display: flex;
            border-radius: 20px;
            margin-bottom: 80px;
            margin-top: 50px;
            /* Increased margin-bottom for more space */
        }



        img {
            height: 100px;
            width: 140px;
            margin: 10px 0 0 60px;
        }

        #profile_edit {
            margin-left: 20px;
            margin-top: 2px;
            padding: 10px 0 0 8px;
            height: 300px;
            width: 300px;
            border-radius: 10px;
        }

        #tittlemnm {
            font-size: x-large;
            color: #C71585;
            margin: 50px 0 0 340px;
        }

        footer {
            background-color: #2D3748;
            color: #F7FAFC;
            padding: 20px;
            width: 100vw;
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

        /* button {
            height: 15px;
            text-align: center;
        } */

        #B {
            font-size: larger;
            font-weight: bolder;
        }

        #name {
            margin: 0 0 0 670px;
        }



        #box1 {
            /* background-color: #FFFFFF; */
            width: 61%;
            height: 500px;
            border-radius: 10px;
            margin-left: 180px;
            /* box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px; */

        }

        #box1 h1 {
            font-size: 2rem;
            color: #C71585;
            font-weight: 900;
            position: relative;
            bottom: 20px;
        }

        .tables {
            height: 400px;
            width: 1300px;
            position: relative;
            top: 30px;
            right: 100px;
            border-radius: 20px;
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
            padding-top: 3px;
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
    <!-- <h2 id="tittlemnm">My Booking</h2> -->
    <section id="undernavbar">
        <div id="sidebar">
            <a href="" id="mma">Manage My Account</a>
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
        <div id="box1">
            <h2 class="text-3xl font-bold text-pink-700 mb-6" style="color:#C71585;margin:10px 0 0 0;">My Reviews</h2>
            <?php
            if ($result->num_rows > 0) {
                echo '<div class="overflow-x-auto tables">';
                echo '<table class="min-w-full bg-white border border-gray-200 rounded-lg">';
                echo '<thead class="bg-pink-500 text-white">';
                echo '<tr>';
                echo '<th class="px-4 py-2 text-left">Serial</th>';
                echo '<th class="px-4 py-2 text-left">Shop Name</th>';
                echo '<th class="px-4 py-2 text-left">Shop Address</th>';
                echo '<th class="px-4 py-2 text-left">Review</th>';
                echo '<th class="px-4 py-2 text-left">Rating</th>';
                echo '<th class="px-4 py-2 text-left">Date & Time</th>';
                echo '<th class="px-4 py-2 text-center" colspan="2">Action</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody class="text-gray-700">';
                $cnt = 1;
                while ($row = $result->fetch_assoc()) {
                    echo '<tr class="border-t">';
                    echo '<td class="px-4 py-2">' . $cnt++ . '</td>';
                    // Fetching shop details
                    $shop_id = $row['shop_id'];
                    $sql_shop = "SELECT shop_name, shop_state, shop_city, shop_area FROM barber_shop WHERE shop_id='$shop_id'";
                    $result_shop = mysqli_query($conn, $sql_shop);
                    while ($row_shop = $result_shop->fetch_assoc()) {
                        echo '<td class="px-4 py-2">' . htmlspecialchars($row_shop['shop_name']) . '</td>';
                        echo '<td class="px-4 py-2">' . htmlspecialchars($row_shop['shop_state']) . ', ' . htmlspecialchars($row_shop['shop_city']) . ', ' . htmlspecialchars($row_shop['shop_area']) . '</td>';
                    }
                    echo '<td class="px-4 py-2">' . htmlspecialchars($row['review']) . '</td>';
                    echo '<td class="px-4 py-2">' . htmlspecialchars($row['star']) . '</td>';
                    echo '<td class="px-4 py-2">' . htmlspecialchars($row['date_and_time']) . '</td>';
                    // Action buttons
                    // Change button in a separate 
                    // echo '<td class="px-2 py-1">';
                    // echo '<button type="submit" name="change" class="text-blue-500">Change</button>';
                    // echo '</td>';
                    // Delete button in a separate form
                    echo '<td class="px-2 py-1">';
                    echo '<form onsubmit="confirmDelete(this)" action="myreviews.php" method="POST">';
                    echo '<input type="hidden" name="review_id" value="' . $row['review_id'] . '">';
                    echo '<button type="submit" name="cancel" class="text-red-500">Delete</button>';
                    echo '</form>';
                    echo '</td>';

                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            } else {
                echo '<p class="text-gray-500">No reviews found.</p>';
            }
            ?>
        </div>

    </section>

    <script>
        function confirmDelete(form) {
            event.preventDefault(); // Prevent the form from submitting immediately

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const hiddenInput = document.createElement("input");
                    hiddenInput.setAttribute("type", "hidden");
                    hiddenInput.setAttribute("name", "cancel");
                    hiddenInput.setAttribute("value", "Delete");
                    form.appendChild(hiddenInput);

                    form.submit(); // Submit the form if confirmed
                }
            });
        }
    </script>

</body>

</html>