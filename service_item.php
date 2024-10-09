<?php
require 'mysql_connection.php';
session_start();
$username = $type = '';
if (isset($_SESSION['type'])) {
    $type = $_SESSION['type'];
    $email = $_SESSION['email'];
    if ($type == 'admin') {

    } else if ($type == 'customer') {
        //fetch customer details
        //user_id first_name are important
        $sql = "SELECT customer_id,first_name,last_name FROM customer WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $_SESSION['customer_id'] = $customer_id = $row['customer_id'];
        $_SESSION['first_name'] = $first_name = $row['first_name'];
        $_SESSION['last_name'] = $last_name = $row['last_name'];
    } else {
        //fetch shop details
        //shop_id business_name
        $sql = "SELECT shop_id, shop_name FROM barber_shop WHERE shop_email='$email'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $_SESSION['shop_id'] = $shop_id = $row['shop_id'];
        $_SESSION['shop_name'] = $business_name = $row['shop_name'];
    }
}
$service='';
if (isset($_GET['id'])) {
    $service = urldecode($_GET['id']);
    // echo $service;  // Outputs 'Spa Services'
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service items</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="style_home.css">
    <style>
        .bg-black {
            opacity: 0.8;
           
        }

        .bg-gray-100 {
            background-color: #F8F6FF;
        }

        .text-gray-700 {
            font-weight: bolder;
        }

        .t {

            font-size: 50px;
        }

        * {
            font-family: cursive;

        }

        section div div h1 {
            font-weight: 900;

        }

        h1 {
            font-weight: 900;
        }

        #navbar {
            position: fixed;
            z-index: 1;
            width: 100%;
            height:69px;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
        }

        #weight {
            font-weight: 800px;
            color: pink;
            font-style: italic;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);


        }

        .s {
            width: 200px;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
            border-radius: 10px;
        }

        #imagesize:hover {
            overflow: hidden;
            border-radius: 8px;
            transition: transform 0.3s ease;
            transform: scale(1.1);

        }

        #writeReviewBtn {
            margin: 0 0 0 45%;
        }

      

        .sha {
            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;

        }

        #backgroundinside {
            background-color: rgba(0, 0, 0, 0.4);
        }

      

        #bg {
            background-color: rgb(0, 0, 0, 0.1);
        }

        #reviewsContainer {
            width: 400px;
        }
    </style>
</head>

<body class="bg-gray-100">

    <!-- Header -->
    <header id="navbar" class="bg-white shadow">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="home.php" class="t text-2xl font-bold text-pink-700">RadiantHub BD</a>
            <nav class="space-x-6">
                <a href="home.php" class="text-gray-700 hover:text-pink-600">Home</a>
                <a href="service.php" class="text-gray-700 hover:text-pink-600">Services</a>
                <a href="book_now.php" class="text-gray-700 hover:text-pink-600">Book Now</a>
                <a href="location.php" class="text-gray-700 hover:text-pink-600">Locations</a>
                <a href="contact_us.php" class="text-gray-700 hover:text-pink-600">Contact Us</a>
                <a href="
                <?php
                if (!isset($_SESSION['type'])) {
                    echo 'login/login.php';
                } else {
                    if ($_SESSION['type'] === 'customer') {
                        echo 'customer_profile/My_profile.php';
                    } else if ($_SESSION['type'] === 'admin') {
                        echo 'admin/index.php';
                    } else {
                        echo 'business_dashboard/saloon_dashboard.php';
                    }
                }
                ?>
                " class="text-gray-700 hover:text-pink-600">
                    <?php
                    if (!isset($_SESSION['type'])) {
                        echo 'Login';
                    } else {
                        if ($_SESSION['type'] === 'customer') {
                            echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
                        } else if ($_SESSION['type'] === 'admin') {
                            echo 'admin';
                        } else {
                            echo $_SESSION['shop_name'];
                        }
                    }
                    ?>
                </a>
                <?php
                if (isset($_SESSION['type'])) {
                    echo '<a href="logout.php" class="text-gray-700 hover:text-pink-600">Logout</a>';
                }
                ?>
            </nav>
        </div>
    </header>
    <!-- empty space creation -->
    <!-- <section class="py-6 bg-gray-100"> -->
        <div>&nbsp;</div>
        <div>&nbsp;</div>
        <!-- <div>&nbsp;</div> -->
    <!-- </section> -->

    <section id="wat">
        
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Available Services</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <?php
                $sql = "SELECT * 
                        FROM `item_table` 
                        WHERE service_id in (SELECT service_id from `service_table` WHERE service_name='$service') 
                        ORDER BY `item_name` ASC";
                $result_services = mysqli_query($conn, $sql);
                while ($service = mysqli_fetch_assoc($result_services)) {
                    echo '<div class="bg-white p-6 rounded-lg shadow-lg text-center">';
                    echo '<div class="text-pink-600 text-5xl mb-4"><img style="width: 100px; height: 100px;" src="image/item/'.$service['item_name'].'.png"></div>';
                    echo "<h3 class=\"text-xl font-semibold\">{$service['item_name']}</h3>";
                    // Add a form to handle the POST request
                    echo '<form action="explore_by_items.php" method="POST">';
                    echo "<input type=\"hidden\" name=\"item_id\" value=\"{$service['item_id']}\">";
                    echo "<input type=\"hidden\" name=\"item_name\" value=\"{$service['item_name']}\">";
                    echo '<button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-lg mt-4">Explore sallons</button>';
                    echo '</form>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </section>


        <!-- </section> -->
        <!-- don't do anything here -->
        <div class="wave water"></div>
        <div class="wave water"></div>
        <div class="wave water"></div>
        <div class="wave water"></div>
        <!-- don't do anything here -->

    </section>









    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="home.php" class="hover:text-gray-300">Home</a></li>
                        <li><a href="service.php" class="hover:text-gray-300">Services</a></li>
                        <li><a href="book_now.php" class="hover:text-gray-300">Book Now</a></li>
                        <li><a href="location.php" class="hover:text-gray-300">Locations</a></li>
                        <li><a href="contact_us.php" class="hover:text-gray-300">Contact Us</a></li>
                    </ul>
                </div>
                <!-- Contact Information -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
                    <p class="text-gray-400">Madani avenue</p>
                    <p class="text-gray-400">radienthub@admin.com</p>
                    <p class="text-gray-400">+8801794706582</p>
                </div>
                <!-- Social Media Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
                    <ul class="space-y-2">
                        <li><a href="https://www.facebook.com" class="hover:text-gray-300">Facebook</a></li>
                        <li><a href="https://www.instagram.com" class="hover:text-gray-300">Instagram</a></li>
                        <li><a href="https://www.twitter.com" class="hover:text-gray-300">Twitter</a></li>
                    </ul>
                </div>
                <!-- Newsletter Signup -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Subscribe to Our Newsletter</h3>
                    <form action="#" class="flex">
                        <input type="email" placeholder="Enter your email"
                            class="p-2 w-full rounded-l-lg text-gray-800 bg-gray-700">
                        <button type="submit"
                            class="p-2 bg-pink-600 text-white rounded-r-lg hover:bg-pink-700">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>