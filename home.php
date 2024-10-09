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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RadientHUB home</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="style_home.css">
    <style>
        .bg-black {
            opacity: 0.8;

        }

        .bg-gray-100 {
            background-color: #FFFFFF;
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
            height: 69px;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
        }

        #weight {
            font-weight: 800px;
            color: #B20E66;
            font-style: italic;



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

        .cards {
            border: 2px solid #ccc;

        }



        #bg {
            background-color: #FFFFFF;
        }

        #reviewsContainer {
            width: 400px;
        }

        .grid {
            margin-left: 100px;
        }

        .no-scrollbar {
            -ms-text-overflow: none;
            scrollbar-width: none;
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
    <section id="wat">
        <!-- <section class="bg-cover bg-center h-screen" id="background">Hero Section -->
        <div class="bg-black-400 bg-opacity-70 h-full flex items-center justify-center" id="backgroundinside">
            <div class="text-center text-white px-4">
                <h1 id="weight" class="text-4xl md:text-6xl font-bold">Find and Book the Best Salons and Parlours Near
                    You</h1>
                <p id="weight" class="mt-4 text-lg md:text-xl">Your beauty journey starts here</p>
                <form action="search.php" method="POST">
                    <div class="mt-6">
                        <input type="text" required placeholder="Enter your location or salon name"
                            class="p-2 w-full md:w-1/2 rounded-lg text-gray-800 sha" name="search">
                        <button type="submit"
                            class="mt-2 md:mt-0 md:ml-2 p-2 bg-pink-500 text-white rounded-lg hover:bg-pink-700 sha">Search
                            Now</button>
                    </div>
                </form>
                <div class="todayappointment"
                    style="color:#B20E66;position:absolute;bottom:300px;left:720px;font-size:30px;">
                    <?php
                    $sql = "SELECT count(id) AS ta  FROM bookings WHERE date(when_booked)=CURDATE()";
                    $result = mysqli_query($conn, $sql);
                    $appointment = mysqli_fetch_assoc($result);
                    ?>

                    <p> Today Total Appointmented: <?php echo $appointment['ta']; ?></p>
                </div>
            </div>
        </div>
        <!-- </section> -->
        <!-- don't do anything here -->
        <div class="wave water"></div>
        <div class="wave water"></div>
        <div class="wave water"></div>
        <div class="wave water"></div>
        <!-- don't do anything here -->

    </section>
    <section id="bg"><!-- How It Works Section -->
        <div class="container mx-auto px-4 mid">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">How It Works</h2>
            <div class="grid md:grid-cols-4 gap-8">
                <div class=" s text-center">
                    <div class="text-pink-600 text-5xl mb-4">🔍</div>
                    <h3 class="text-xl font-semibold">Search</h3>
                    <p>Find salons and parlours near you.</p>
                </div>
                <div class="s text-center">
                    <div class="text-pink-600 text-5xl mb-4">📋</div>
                    <h3 class="text-xl font-semibold">Choose</h3>
                    <p>Compare services, prices, and reviews.</p>
                </div>
                <div class="s text-center">
                    <div class="text-pink-600 text-5xl mb-4">📅</div>
                    <h3 class="text-xl font-semibold">Book</h3>
                    <p>Schedule your appointment easily.</p>
                </div>
                <div class="s text-center">
                    <div class="text-pink-600 text-5xl mb-4">💆‍♀️</div>
                    <h3 class="text-xl font-semibold">Relax</h3>
                    <p>Enjoy your beauty treatment.</p>
                </div>
            </div>
        </div>
        <?php
        if (isset($_SESSION['customer_id'])) {
            $customer_id = $_SESSION['customer_id']; // Fetch customer ID from session
            echo '<section class="py-12">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Recent visits</h2>
                <div class="relative">
                    <div id="carousel-5" class="carousel flex space-x-4 overflow-x-auto no-scrollbar">';
            // SQL query to fetch customer's log data
            $sql = "SELECT * 
                    FROM `barber_shop` AS `x`
                    WHERE x.shop_id IN (
                        SELECT `shop_id`
                        FROM (
                            SELECT `shop_id`, MAX(`logged_time`) as earliest_log_time
                            FROM `recent_log`
                            WHERE `customer_id` = '$customer_id'
                            GROUP BY `shop_id`
                            ORDER BY `earliest_log_time`desc
                        ) as recent_shops
                    ) AND x.status=1
                    ORDER BY (SELECT MAX(`logged_time`) 
                              FROM `recent_log` 
                              WHERE `customer_id` = '$customer_id' AND `shop_id` = x.shop_id) DESC";

            $result = mysqli_query($conn, $sql);
            $recommendations_recent = []; // Initialize array 
            // If there are results, loop through them
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $recommendations_recent[] = [
                        'title' => $row["shop_name"],
                        'rating' => $row["shop_rating"],
                        'reviews' => $row["shop_customer_count"],
                        'location' => $row["shop_city"],
                        'category' => $row["shop_title"],
                        'image' => 'image/shop/' . $row["shop_id"] . '.jpeg',
                        'link' => 'saloon_profile/dashboard.php?shop_id=' . $row["shop_id"]
                    ];
                }
            } else {
                echo "<p class='text-center'>No recent visits found.</p>";
            }
            foreach ($recommendations_recent as $recommendation) {
                echo '<a href="' . $recommendation['link'] . '" class="block bg-white rounded-lg shadow-lg overflow-hidden min-w-[300px] max-w-[300px] flex-none">
                <img src="' . $recommendation['image'] . '" alt="' . $recommendation['title'] . '" class="w-full h-48 object-cover">
                <div class="p-4 cards">
                    <h3 class="text-lg font-semibold">' . $recommendation['title'] . '</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        <span class="font-bold text-yellow-500">' . $recommendation['rating'] . ' &#9733;</span>
                        (' . $recommendation['reviews'] . ' reviews)
                    </p>
                    <p class="mt-2 text-sm text-gray-600">' . $recommendation['location'] . '</p>
                    <p class="mt-2 text-sm text-gray-600">' . $recommendation['category'] . '</p>
                </div>
            </a>';
            }
            echo '</div>
                <!-- Carousel navigation buttons -->
                <div class="absolute inset-y-0 left-0 flex items-center" id="leftBtn-5">
                    <div class="scroll-arrow left-arrow bg-white p-2 rounded-full shadow-md cursor-pointer" onclick="scrollCarousel(\'carousel-5\', -300)"><img src="image/icon/left-arrow1.png" alt="Right Arrow"
                                class="w-8 h-8 opacity-75 hover:opacity-100"></div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center" id="rightBtn-5">
                    <div class="scroll-arrow right-arrow bg-white p-2 rounded-full shadow-md cursor-pointer" onclick="scrollCarousel(\'carousel-5\', 300)"><img src="image/icon/right-arrow.png" alt="Right Arrow"
                                class="w-8 h-8 opacity-75 hover:opacity-100"></div>
                </div>
            </div>
        </div>
    </section>';
        }
        ?>

        <section class="py-12 "><!-- Featured Salons and Parlours -->
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Featured Salons and Parlours</h2>
                <div class="relative">
                    <div id="carousel-2" class="carousel flex space-x-4 overflow-x-auto no-scrollbar">
                        <?php

                        $sql = "SELECT * 
                                FROM `barber_shop` AS `x`
                                WHERE x.status=1
                                ORDER BY x.shop_customer_count DESC";

                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Creating recommendation array
                                $recommendations[] = [
                                    'title' => $row["shop_name"],
                                    'rating' => $row["shop_rating"],
                                    'reviews' => $row["shop_customer_count"],
                                    'location' => $row["shop_city"],
                                    'category' => $row["shop_title"],
                                    'image' => 'image/shop/' . $row["shop_id"] . '.jpeg',
                                    'link' => 'saloon_profile/dashboard.php?shop_id=' . $row["shop_id"]
                                ];
                            }
                        } else {
                            echo "No shop_found";
                        }
                        foreach ($recommendations as $recommendation) {
                            ?>
                            <a href="<?php echo $recommendation['link']; ?>"
                                class="block bg-white rounded-lg shadow-lg overflow-hidden min-w-[300px] max-w-[300px] flex-none">
                                <img id="imagesize" src="<?php echo $recommendation['image']; ?>"
                                    alt="<?php echo $recommendation['title']; ?>" class="w-full h-48 object-cover">
                                <div class="p-4 cards">
                                    <h3 class="text-lg font-semibold"><?php echo $recommendation['title']; ?></h3>
                                    <p class="mt-2 text-sm text-gray-600">
                                        <span class="font-bold text-yellow-500"><?php echo $recommendation['rating']; ?>
                                            &#9733;</span>
                                        (<?php echo $recommendation['reviews']; ?> reviews)
                                    </p>
                                    <p class="mt-2 text-sm text-gray-600"><?php echo $recommendation['location']; ?></p>
                                    <p class="mt-2 text-sm text-gray-600"><?php echo $recommendation['category']; ?></p>
                                </div>
                            </a>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="absolute inset-y-0 left-0 flex items-center" id="leftBtn-2">
                        <div class="scroll-arrow left-arrow bg-white p-2 rounded-full shadow-md cursor-pointer"
                            onclick="scrollCarousel('carousel-2', -300)"><img src="image/icon/left-arrow1.png"
                                alt="Right Arrow" class="w-8 h-8 opacity-75 hover:opacity-100"></div>
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center" id="rightBtn-2">
                        <div class="scroll-arrow right-arrow bg-white p-2 rounded-full shadow-md cursor-pointer"
                            onclick="scrollCarousel('carousel-2', 300)"><img src="image/icon/right-arrow.png"
                                alt="Right Arrow" class="w-8 h-8 opacity-75 hover:opacity-100"></div>
                    </div>
                </div>
            </div>
        </section>
        <div class="container mx-auto px-6 mb-12"><!-- Popular Services -->
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Popular Services</h2>
            <div class="mb-12">
                <h4 class="text-3xl font-bold text-left text-gray-800 mb-6">Male Services</h4>
                <div class="relative">
                    <div id="carousel-1" class="carousel flex space-x-6 overflow-x-auto no-scrollbar">
                        <?php
                        $sql = "SELECT * FROM `item_table` WHERE item_gender='0' OR item_gender='2'";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <div class="text-center bg-white p-6 rounded-lg shadow-lg flex-none w-60 cards">
                                    <a href="explore_by_items.php?item_id=<?php echo urlencode($row['item_id']); ?>&item_name=<?php echo urlencode($row['item_name']); ?>"
                                        class="block">
                                        <div class="flex justify-center items-center mb-4">
                                            <img src="image/item/<?php echo htmlspecialchars($row['item_name']); ?>.png"
                                                alt="<?php echo htmlspecialchars($row['item_name']); ?>"
                                                class="w-24 h-24 object-cover">
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-800">
                                            <?php echo htmlspecialchars($row['item_name']); ?>
                                        </h3>
                                    </a>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <!-- Navigation buttons -->
                    <div class="absolute inset-y-0 left-0 flex items-center pl-2">
                        <button id="leftBtn-1"
                            class="scroll-arrow left-arrow bg-white p-2 rounded-full shadow-md cursor-pointer hover:bg-gray-200 focus:outline-none"
                            onclick="scrollCarousel('carousel-1', -300)">
                            <img src="image/icon/left-arrow1.png" alt="Left Arrow"
                                class="w-8 h-8 opacity-75 hover:opacity-100">
                        </button>
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                        <button id="rightBtn-1"
                            class="scroll-arrow right-arrow bg-white p-2 rounded-full shadow-md cursor-pointer hover:bg-gray-200 focus:outline-none"
                            onclick="scrollCarousel('carousel-1', 300)">
                            <img src="image/icon/right-arrow.png" alt="Right Arrow"
                                class="w-8 h-8 opacity-75 hover:opacity-100">
                        </button>
                    </div>
                </div>
            </div>
            <div>
                <h4 class="text-3xl font-bold text-left text-gray-800 mb-8">Female Services</h4>
                <div class="relative">
                    <div id="carousel-3" class="carousel flex space-x-6 overflow-x-auto no-scrollbar">
                        <?php
                        $sql = "SELECT * FROM `item_table` WHERE item_gender='1' OR item_gender='2'";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <div class="text-center bg-white p-6 rounded-lg shadow-lg flex-none w-60 cards">
                                    <a href="explore_by_items.php?item_id=<?php echo urlencode($row['item_id']); ?>&item_name=<?php echo urlencode($row['item_name']); ?>"
                                        class="block">
                                        <div class="flex justify-center items-center mb-4">
                                            <img src="image/item/<?php echo htmlspecialchars($row['item_name']); ?>.png"
                                                alt="<?php echo htmlspecialchars($row['item_name']); ?>"
                                                class="w-24 h-24 object-cover">
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-800">
                                            <?php echo htmlspecialchars($row['item_name']); ?>

                                        </h3>
                                    </a>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <!-- Navigation buttons -->
                    <div class="absolute inset-y-0 left-0 flex items-center pl-2">
                        <button id="leftBtn-3"
                            class="scroll-arrow left-arrow bg-white p-2 rounded-full shadow-md cursor-pointer hover:bg-gray-200 focus:outline-none"
                            onclick="scrollCarousel('carousel-3', -300)">
                            <img src="image/icon/left-arrow1.png" alt="Left Arrow"
                                class="w-8 h-8 opacity-75 hover:opacity-100">
                        </button>
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                        <button id="rightBtn-3"
                            class="scroll-arrow right-arrow bg-white p-2 rounded-full shadow-md cursor-pointer hover:bg-gray-200 focus:outline-none"
                            onclick="scrollCarousel('carousel-3', 300)">
                            <img src="image/icon/right-arrow.png" alt="Right Arrow"
                                class="w-8 h-8 opacity-75 hover:opacity-100">
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!--Female-->
        <script>
            function scrollCarousel(carouselId, scrollOffset) {
                const carousel = document.getElementById(carouselId);
                carousel.scrollBy({
                    left: scrollOffset,
                    behavior: 'smooth'
                });
            }
            function updateNavigationButtons(carouselId, leftBtnId, rightBtnId) {
                const carousel = document.getElementById(carouselId);
                const leftBtn = document.getElementById(leftBtnId);
                const rightBtn = document.getElementById(rightBtnId);
                leftBtn.style.display = carousel.scrollLeft > 0 ? 'flex' : 'none';
                rightBtn.style.display = carousel.scrollLeft < (carousel.scrollWidth - carousel.clientWidth) ? 'flex' : 'none';
            }
            document.getElementById('carousel-1').addEventListener('scroll', function () {
                updateNavigationButtons('carousel-1', 'leftBtn-1', 'rightBtn-1');
            });
            document.getElementById('carousel-2').addEventListener('scroll', function () {
                updateNavigationButtons('carousel-2', 'leftBtn-2', 'rightBtn-2');
            });
            document.getElementById('carousel-3').addEventListener('scroll', function () {
                updateNavigationButtons('carousel-3', 'leftBtn-3', 'rightBtn-3');
            });
            updateNavigationButtons('carousel-1', 'leftBtn-1', 'rightBtn-1');
            updateNavigationButtons('carousel-2', 'leftBtn-2', 'rightBtn-2');
            updateNavigationButtons('carousel-3', 'leftBtn-3', 'rightBtn-3');
        </script>

        <!----Male-->
        <script>
            function scrollCarousel(carouselId, scrollOffset) {
                const carousel = document.getElementById(carouselId);
                carousel.scrollBy({
                    left: scrollOffset,
                    behavior: 'smooth'
                });
            }
            function updateNavigationButtons(carouselId, leftBtnId, rightBtnId) {
                const carousel = document.getElementById(carouselId);
                const leftBtn = document.getElementById(leftBtnId);
                const rightBtn = document.getElementById(rightBtnId);
                leftBtn.style.display = carousel.scrollLeft > 0 ? 'flex' : 'none';
                rightBtn.style.display = carousel.scrollLeft < (carousel.scrollWidth - carousel.clientWidth) ? 'flex' : 'none';
            }

            document.getElementById('carousel-1').addEventListener('scroll', function () {
                updateNavigationButtons('carousel-1', 'leftBtn-1', 'rightBtn-1');
            });
            document.getElementById('carousel-2').addEventListener('scroll', function () {
                updateNavigationButtons('carousel-2', 'leftBtn-2', 'rightBtn-2');
            });

            updateNavigationButtons('carousel-1', 'leftBtn-1', 'rightBtn-1');
            updateNavigationButtons('carousel-2', 'leftBtn-2', 'rightBtn-2');
        </script>

        <section class="py-12"><!-- Customer Testimonials -->
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Reviews</h2>
                <div class="relative">
                    <div id="carousel-7" class="carousel flex space-x-4 overflow-x-auto no-scrollbar">
                        <?php
                        $sql = "SELECT r.review, r.star,r.review_time, c.first_name, c.last_name, c.image, c.district 
                        FROM review_platform r 
                        JOIN customer c ON r.customer_id = c.customer_id"; // Fetch reviews
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Review Card
                                echo '
                        <div class="bg-gray-100 p-6 rounded-lg shadow-lg min-w-[300px] max-w-[300px] flex-none   cards">
                            <div class="flex items-center mb-4">
                                <div class="text-yellow-400">';
                                // Output star ratings
                                for ($i = 0; $i < $row['star']; $i++) {
                                    echo '<span>&#9733;</span>'; // Filled star
                                }
                                for ($i = 0; $i < 5 - $row['star']; $i++) {
                                    echo '<span>&#9734;</span>'; // Empty star
                                }
                                echo '<br><span style="color: red;">' . htmlspecialchars($row['review_time']) . '</span>';
                                echo '</div>
                            </div>
                            <p class="mt-2 text-gray-600">' . $row['review'] . '</p>
                            <div class="flex items-center mt-4">
                                <img src="' . $row['image'] . '" alt="" class="w-10 h-10 rounded-full mr-4">
                                <div>
                                    <p class="text-gray-800 font-semibold">' . $row['first_name'] . ' ' . $row['last_name'] . '</p>
                                    <p class="text-gray-600 text-sm">' . $row['district'] . '</p>
                                </div>
                            </div>
                        </div>';
                            }
                        } else {
                            echo '<p>No reviews available.</p>';
                        }
                        ?>
                    </div>

                    <!-- Left Arrow -->
                    <div class="absolute inset-y-0 left-0 flex items-center">
                        <div class="scroll-arrow left-arrow bg-white p-2 rounded-full shadow-md cursor-pointer"
                            onclick="scrollCarousel('carousel-7', -300)"><img src="image/icon/left-arrow1.png"
                                alt="Right Arrow" class="w-8 h-8 opacity-75 hover:opacity-100"></div>
                    </div>

                    <!-- Right Arrow -->
                    <div class="absolute inset-y-0 right-0 flex items-center">
                        <div class="scroll-arrow right-arrow bg-white p-2 rounded-full shadow-md cursor-pointer"
                            onclick="scrollCarousel('carousel-7', 300)"><img src="image/icon/right-arrow.png"
                                alt="Right Arrow" class="w-8 h-8 opacity-75 hover:opacity-100"></div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Blog Section -->
        <!-- <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Recent Blog Posts</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-4 rounded-lg shadow">
                    <img src="https://source.unsplash.com/160x90/?beauty,blog" alt="Blog" class="w-full rounded-t-lg">
                    <h3 class="text-xl font-semibold mt-4">Top Beauty Tips for Summer</h3>
                    <p class="mt-2 text-gray-600">Stay fresh and beautiful with these top tips.</p>
                    <button class="mt-4 p-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">Read More</button>
                </div>
            </div>
        </div> -->

        <!-- review about our platform-->
        <div class="container mx-auto px-4 py-7">
            <!-- Write a Review Button -->
            <?php if (isset($_SESSION['customer_id'])): ?>
                <h2 class="text-2xl font-bold text-center mb-8">Review About Our Platform</h2>
                <button id="writeReviewBtn" class="bg-pink-500 text-white px-4 py-2 rounded-lg">Write a Review</button>
            <?php endif; ?>
            <div id="modalOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden"></div>
            <!-- Review Modal -->
            <div id="reviewModal" class="fixed inset-0 flex items-center justify-center hidden">
                <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
                    <h3 class="text-xl font-semibold mb-4">Rate and Review</h3>
                    <!-- Review Form -->
                    <form id="reviewForm" action="submit_review.php" method="POST">
                        <!-- Name Input -->
                        <input type="text" id="reviewerName" name="reviewer_name"
                            class="w-full border p-2 rounded-lg mb-4" readonly value="<?php if (isset($_SESSION['first_name']))
                                echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?>" />

                        <!-- Star Rating -->
                        <div class="flex mb-4">
                            <span class="star w-6 h-6 text-gray-400 cursor-pointer" data-value="1">&#9733;</span>
                            <span class="star w-6 h-6 text-gray-400 cursor-pointer" data-value="2">&#9733;</span>
                            <span class="star w-6 h-6 text-gray-400 cursor-pointer" data-value="3">&#9733;</span>
                            <span class="star w-6 h-6 text-gray-400 cursor-pointer" data-value="4">&#9733;</span>
                            <span class="star w-6 h-6 text-gray-400 cursor-pointer" data-value="5">&#9733;</span>
                        </div>
                        <!-- Rating Input (hidden) -->
                        <input type="hidden" id="rating" name="rating" value="" required>

                        <!-- Review Textarea -->
                        <textarea id="reviewText" name="review" class="w-full border p-2 rounded-lg mb-4"
                            placeholder="Describe your experience (optional)" rows="4"></textarea>

                        <!-- Character Count -->
                        <p id="charCount" class="text-sm text-gray-500 mb-4">0 / 500</p>

                        <!-- Submit and Cancel Buttons -->
                        <button type="submit" id="submitReview"
                            class="bg-green-500 text-white px-4 py-2 rounded-lg">Submit
                            Review</button>
                        <button type="button" id="cancelReview" class="ml-4 text-gray-600">Cancel</button>
                    </form>
                </div>
            </div>

            <!-- Reviews Container -->
            <div id="reviewsContainer" class="mt-8 space-y-4">
                <!-- Reviews will be inserted here -->
            </div>
        </div>
        <!-- <div>
            <hr style="border: 0.5px solid #000; margin: 20px 0;">
        </div> -->
        <div class="py-16 text-center">
            <h2 class="text-3xl font-bold text-black"> Wellcome to RadientHUB :The Future of Beauty & Wellness</h2>
            <p class="text-lg text-gray-500 mt-2">Your One-Stop Hub for Radiance and Relaxation.</p>

            <div class="mt-8">
                <span class="text-6xl font-bold text-blue-600"><?php $sql = "SELECT count(id) AS ta  FROM bookings";
                $result = mysqli_query($conn, $sql);
                $appointment = mysqli_fetch_assoc($result); 
                echo $appointment['ta'];
                ?></span>
                <p class="text-lg text-gray-500">Appointments booked on RadientHUB</p>
            </div>

            <div class="mt-12 flex justify-center space-x-12">
                <div class="text-center">
                    <p class="text-2xl font-bold text-black">
                        <?php
                        $sql_saloon = "SELECT COUNT(*) AS salon_count FROM barber_shop";
                        $result_saloon = mysqli_query($conn, $sql_saloon);
                        if ($result_saloon) {
                            $row = mysqli_fetch_assoc($result_saloon);
                            $salon_count = $row['salon_count'];
                            echo $salon_count;
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                        ?>+
                    </p>
                    <p class="text-lg text-gray-500">Registered Shops</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-black">
                        <?php
                        $sql_area = "SELECT COUNT(DISTINCT shop_area) AS unique_area_count FROM barber_shop";
                        $result_area = mysqli_query($conn, $sql_area);

                        if ($result_area) {
                            $row = mysqli_fetch_assoc($result_area);
                            $unique_area_count = $row['unique_area_count'];
                            echo $unique_area_count;
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                        ?>+ upazilla
                    </p>
                    <p class="text-lg text-gray-500">using RadientHUB</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-black">
                        <?php
                        $sql_worker = "SELECT COUNT(*) AS worker_count FROM shop_worker";
                        $result_worker = mysqli_query($conn, $sql_worker);

                        if ($result_worker) {
                            $row = mysqli_fetch_assoc($result_worker);
                            $worker_count = $row['worker_count'];
                            echo $worker_count;
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                        ?>
                        +
                    </p>
                    <p class="text-lg text-gray-500">Professional barber</p>
                </div>
            </div>
        </div>
        <script>
            const writeReviewBtn = document.getElementById('writeReviewBtn');
            const modalOverlay = document.getElementById('modalOverlay');
            const reviewModal = document.getElementById('reviewModal');
            const cancelReview = document.getElementById('cancelReview');
            const reviewText = document.getElementById('reviewText');
            const reviewerName = document.getElementById('reviewerName');
            const charCount = document.getElementById('charCount');
            const stars = document.querySelectorAll('.star');
            const ratingInput = document.getElementById('rating');
            const reviewForm = document.getElementById('reviewForm');
            let selectedRating = 0;
            writeReviewBtn.onclick = () => {
                reviewModal.classList.remove('hidden');
                modalOverlay.classList.remove('hidden');
            };
            cancelReview.onclick = () => {
                reviewModal.classList.add('hidden');
                modalOverlay.classList.add('hidden');
                resetStars();
            };
            stars.forEach(star => {
                star.addEventListener('click', () => {
                    selectedRating = parseInt(star.getAttribute('data-value'));
                    ratingInput.value = selectedRating; // Set hidden rating input value
                    stars.forEach(s => s.classList.remove('text-yellow-500'));
                    for (let i = 0; i < selectedRating; i++) {
                        stars[i].classList.add('text-yellow-500');
                    }
                });
            });
            reviewText.addEventListener('input', () => {
                const textLength = reviewText.value.length;
                charCount.textContent = `${textLength} / 500`;
            });
            function resetStars() {
                selectedRating = 0;
                stars.forEach(s => s.classList.remove('text-yellow-500'));
                ratingInput.value = ''; // Clear hidden rating input
                reviewText.value = '';
                charCount.textContent = '0 / 500';
            }
            reviewForm.addEventListener('submit', (e) => {
                if (selectedRating === 0) {
                    alert("Please select a star rating.");
                    e.preventDefault();
                }
            });
        </script>

        <!-- Subscription Call-to-Action -->
        <!-- <section class="py-12">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Stay Updated with the Latest Beauty Trends and
                    Offers
                </h2>
                <input type="email" placeholder="Enter your email" class="p-2 w-full md:w-1/3 rounded-lg text-gray-800">
                <button
                    class="mt-2 md:mt-0 md:ml-2 p-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">Subscribe</button>
            </div>
        </section> -->
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
                    <form action="" method="POST" class="flex">
                        <input type="email" placeholder="Enter your email"
                            class="p-2 w-full rounded-l-lg text-gray-800 bg-gray-700">
                        <button type="email_submit"
                            class="p-2 bg-pink-600 text-white rounded-r-lg hover:bg-pink-700">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>