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
$search = "";
if ($_SERVER['REQUEST_METHOD'] = "POST") {
    $search = $_POST['search'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search results</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .bg-black {
            opacity: 0.8;
            background-image: url("professional.jpg");
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

        #background {
            background-image: url(pexels-delbeautybox-211032-705255.jpg);
            background-repeat: no-repeat;
            background-size: cover;

        }

        .sha {
            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;

        }

        #backgroundinside {
            background-color: rgba(0, 0, 0, 0.4);
        }

        body {
            /* background-image: url(pawel-czerwinski-OG44d93iNJk-unsplash.jpg); */
            background-color: white;
            background-repeat: no-repeat;
            background-size: cover;

        }

        #bg {
            background-color: rgb(0, 0, 0, 0.1);
        }

        #reviewsContainer {
            width: 400px;
        }

        .cards {
            border: 2px solid #ccc;

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
    <section class="py-6 bg-gray-100">
        <div>&nbsp;</div>
        <!-- <div>&nbsp;</div> -->
    </section>
    <!-- Featured Salons and Parlours -->
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Search results of <?php echo $search; ?></h2>

    <section class="py-12 ">
        <div class="container mx-auto px-4">
            <!-- <h2 class="text-2xl font-bold mb-4">Recommended</h2>-->
            <h2 class="text-3xl font-bold text-left text-gray-800 mb-8">Salons and Parlours</h2>
            <div class="relative">
                <div id="carousel-1" class="carousel flex space-x-4 overflow-x-auto scroll-smooth">
                    <?php
                    // Prepare the SQL query to search for the term in shop_name, shop_title, or shop_info
                    $sql = "SELECT * FROM `barber_shop` as `x` 
                            WHERE (x.shop_name LIKE '%$search%' 
                                  OR x.shop_title LIKE '%$search%' 
                                  OR x.shop_info LIKE '%$search%') AND x.status=1 
                            ORDER BY x.shop_customer_count DESC";
                    $result = $conn->query($sql);
                    $recommendations = [];
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
                                'link' => 'http://localhost/rakib/final1/saloon_profile/dashboard.php?shop_id=' . $row["shop_id"]
                            ];
                        }
                    } else {
                        echo "0 results";
                    }

                    foreach ($recommendations as $recommendation) {
                        ?>
                        <a href="<?php echo $recommendation['link']; ?>"
                            class="block bg-white rounded-lg shadow-lg overflow-hidden min-w-[300px] max-w-[300px] flex-none cards">
                            <img id="imagesize" src="<?php echo $recommendation['image']; ?>"
                                alt="<?php echo $recommendation['title']; ?>" class="w-full h-48 object-cover">
                            <div class="p-4">
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
                <div class="absolute inset-y-0 left-0 flex items-center" id="leftBtn-1">
                    <div class="scroll-arrow left-arrow bg-white p-2 rounded-full shadow-md cursor-pointer"
                        onclick="scrollCarousel('carousel-1', -300)"><img src="image/icon/left-arrow1.png" alt=""
                            class="w-8 h-8 opacity-100"></div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center" id="rightBtn-1">
                    <div class="scroll-arrow right-arrow bg-white p-2 rounded-full shadow-md cursor-pointer"
                        onclick="scrollCarousel('carousel-1', 300)"><img src="image/icon/right-arrow.png" alt=""
                            class="w-8 h-8 opacity-100"></div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-12">
        <div class="container mx-auto px-4">
            <!-- Sorting Dropdown -->
            <div class="flex items-center mb-8">
                <label for="sort" class="mr-4 text-gray-800 font-semibold">Sort by:</label>
                <select id="sort" class="form-select" onchange="sortRecommendations()">
                    <option value="rating-asc">Rating: Low to High</option>
                    <option value="rating-desc">Rating: High to Low</option>
                    <!-- <option value="reviews-asc">Reviews: Low to High</option>
                    <option value="reviews-desc">Reviews: High to Low</option> -->
                </select>
            </div>

            <h2 class="text-3xl font-bold text-left text-gray-800 mb-8">Based on location</h2>
            <div class="relative">
                <div id="carousel-2" class="carousel flex space-x-4 overflow-x-auto scroll-smooth">
                    <?php
                    $search = mysqli_real_escape_string($conn, $search); // Securing the search input
                    $sql = "SELECT * FROM `barber_shop` AS `x`
                        WHERE(shop_state LIKE '%$search%' 
                        OR shop_city LIKE '%$search%'
                        OR shop_area LIKE '%$search%' 
                        OR shop_landmark_1 LIKE '%$search%' 
                        OR shop_landmark_2 LIKE '%$search%'
                        OR shop_landmark_3 LIKE '%$search%'
                        OR shop_landmark_4 LIKE '%$search%' 
                        OR shop_landmark_5 LIKE '%$search%') AND x.status=1
                        ORDER BY shop_customer_count DESC";

                    $result = mysqli_query($conn, $sql);
                    $recommendations = []; // Initialize array to avoid undefined error
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Add to recommendations array
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
                        echo "<p>No results found</p>";
                    }

                    if (!empty($recommendations)) {
                        foreach ($recommendations as $recommendation) {
                            // Fallback image if the image file doesn't exist
                            $image_path = file_exists($recommendation['image']) ? $recommendation['image'] : 'image/shop/default.jpeg';
                            ?>
                            <a href="<?php echo htmlspecialchars($recommendation['link']); ?>"
                                class="recommendation-item block bg-white rounded-lg shadow-lg overflow-hidden min-w-[300px] max-w-[300px] flex-none">
                                <img id="imagesize" src="<?php echo htmlspecialchars($image_path); ?>"
                                    alt="<?php echo htmlspecialchars($recommendation['title']); ?>"
                                    class="w-full h-48 object-cover">
                                <div class="p-4 cards">
                                    <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($recommendation['title']); ?>
                                    </h3>
                                    <p class="mt-2 text-sm text-gray-600">
                                        <span
                                            class="font-bold text-yellow-500"><?php echo htmlspecialchars($recommendation['rating']); ?>
                                            &#9733;</span>
                                        (<?php echo htmlspecialchars($recommendation['reviews']); ?> reviews)
                                    </p>
                                    <p class="mt-2 text-sm text-gray-600">
                                        <?php echo htmlspecialchars($recommendation['location']); ?>
                                    </p>
                                    <p class="mt-2 text-sm text-gray-600">
                                        <?php echo htmlspecialchars($recommendation['category']); ?>
                                    </p>
                                </div>
                            </a>
                            <?php
                        }
                    }
                    ?>
                </div>
                <div class="absolute inset-y-0 left-0 flex items-center" id="leftBtn-2">
                    <div class="scroll-arrow left-arrow bg-white p-2 rounded-full shadow-md cursor-pointer"
                        onclick="scrollCarousel('carousel-2', -300)"><img src="image/icon/left-arrow1.png" alt=""
                            class="w-8 h-8 opacity-100"></div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center" id="rightBtn-2">
                    <div class="scroll-arrow right-arrow bg-white p-2 rounded-full shadow-md cursor-pointer"
                        onclick="scrollCarousel('carousel-2', 300)"><img src="image/icon/right-arrow.png" alt=""
                            class="w-8 h-8 opacity-100"></div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function sortRecommendations() {
            const sortOrder = document.getElementById('sort').value;
            const container = document.getElementById('carousel-2');
            const items = Array.from(container.getElementsByClassName('recommendation-item'));

            items.sort((a, b) => {
                const aRating = parseFloat(a.querySelector('span').textContent);
                const bRating = parseFloat(b.querySelector('span').textContent);
                const aReviews = parseInt(a.querySelector('p:nth-child(3)').textContent.replace(/\D/g, ''), 10);
                const bReviews = parseInt(b.querySelector('p:nth-child(3)').textContent.replace(/\D/g, ''), 10);

                if (sortOrder === 'rating-asc') {
                    return aRating - bRating;
                } else if (sortOrder === 'rating-desc') {
                    return bRating - aRating;
                } else if (sortOrder === 'reviews-asc') {
                    return aReviews - bReviews;
                } else if (sortOrder === 'reviews-desc') {
                    return bReviews - aReviews;
                }
                return 0;
            });

            container.innerHTML = '';
            items.forEach(item => container.appendChild(item));
        }
    </script>


    <section class="py-12 ">
        <div class="container mx-auto px-4">
            <!-- <h2 class="text-2xl font-bold mb-4">Recommended</h2>-->
            <h2 class="text-3xl font-bold text-left text-gray-800 mb-8">Based on provided services</h2>
            <div class="relative">
                <div id="carousel-3" class="carousel flex space-x-4 overflow-x-auto scroll-smooth">
                    <?php
                    // Prepare the SQL query to search for the term in shop_name, shop_title, or shop_info
                    
                    $sql = "SELECT a.*
                            FROM `barber_shop` AS `a`
                            LEFT JOIN `shop_service_table` AS `b` ON a.shop_id = b.shop_id
                            LEFT JOIN `item_table` AS `c` ON b.item_id = c.item_id
                            LEFT JOIN `service_table` AS `d` ON c.service_id = d.service_id
                            WHERE (d.service_name LIKE '%$search%' 
                                OR c.item_name LIKE '%$search%') 
                                AND a.status = 1
                            ORDER BY a.shop_customer_count DESC";

                    $result = $conn->query($sql);
                    $recommendations = [];
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
                                'link' => 'http://localhost/rakib/final1/saloon_profile/dashboard.php?shop_id=' . $row["shop_id"]
                            ];
                        }
                    } else {
                        echo "0 results";
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
                <div class="absolute inset-y-0 left-0 flex items-center" id="leftBtn-3">
                    <div class="scroll-arrow left-arrow bg-white p-2 rounded-full shadow-md cursor-pointer"
                        onclick="scrollCarousel('carousel-3', -300)"><img src="image/icon/left-arrow1.png" alt=""
                            class="w-8 h-8 opacity-100"></div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center" id="rightBtn-3">
                    <div class="scroll-arrow right-arrow bg-white p-2 rounded-full shadow-md cursor-pointer"
                        onclick="scrollCarousel('carousel-3', 300)"><img src="image/icon/right-arrow.png" alt=""
                            class="w-8 h-8 opacity-100"></div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-12 ">
        <div class="container mx-auto px-4">
            <!-- <h2 class="text-2xl font-bold mb-4">Recommended</h2>-->
            <h2 class="text-3xl font-bold text-left text-gray-800 mb-8">Based on services</h2>
            <div class="relative">
                <div id="carousel-4" class="carousel flex space-x-4 overflow-x-auto scroll-smooth">
                    <?php
                    $sql = $sql = "SELECT * FROM `item_table` as `x` 
                                    JOIN `service_table` as `y` ON x.service_id = y.service_id 
                                    WHERE(y.service_name LIKE '%$search%' 
                                    OR x.item_name LIKE '%$search%' )
                                    ORDER BY x.item_name DESC";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="text-center bg-white p-6 rounded-lg shadow-lg flex-none w-60 cards">';
                            echo '<a href="explore_by_items.php?item_id=' . urlencode($row['item_id']) . '&item_name=' . urlencode($row['item_name']) . '">';
                            echo '<div class="text-pink-600 text-5xl mb-4"><img style="width: 100px; height: 100px;" src="image/item/' . $row['item_name'] . '.png" alt="✂️"></div>';
                            echo "<h3 class=\"text-xl font-semibold\">{$row['item_name']}</h3>";
                            //     <a href="https://www.youtube.com/watch?v=4U_AAGHzTok">Stylish haircuts by professional stylists.</a>
                            echo "</a></div>";
                        }
                    } else {
                        echo "0 results";
                    }
                    ?>
                </div>
                <div class="absolute inset-y-0 left-0 flex items-center" id="leftBtn-4">
                    <div class="scroll-arrow left-arrow bg-white p-2 rounded-full shadow-md cursor-pointer"
                        onclick="scrollCarousel('carousel-4', -300)"><img src="image/icon/left-arrow1.png" alt=""
                            class="w-8 h-8 opacity-100"></div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center" id="rightBtn-4">
                    <div class="scroll-arrow right-arrow bg-white p-2 rounded-full shadow-md cursor-pointer"
                        onclick="scrollCarousel('carousel-4', 300)"><img src="image/icon/right-arrow.png" alt=""
                            class="w-8 h-8 opacity-100"></div>
                </div>
            </div>
        </div>
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
                        <li><a href="#" class="hover:text-gray-300">Services</a></li>
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
                        <li><a href="https://www.facebook.com/" class="hover:text-gray-300">Facebook</a></li>
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
    <script>
        // Function to scroll carousel
        function scrollCarousel(carouselId, scrollOffset) {
            const carousel = document.getElementById(carouselId);
            carousel.scrollBy({
                left: scrollOffset,
                behavior: 'smooth'
            });
        }

        // Function to show/hide navigation buttons based on scroll position
        function updateNavigationButtons(carouselId, leftBtnId, rightBtnId) {
            const carousel = document.getElementById(carouselId);
            const leftBtn = document.getElementById(leftBtnId);
            const rightBtn = document.getElementById(rightBtnId);

            // Check if carousel can scroll left or right
            leftBtn.style.display = carousel.scrollLeft > 0 ? 'flex' : 'none';
            rightBtn.style.display = carousel.scrollLeft < (carousel.scrollWidth - carousel.clientWidth) ? 'flex' : 'none';
        }

        // Attach scroll event listeners to both carousels
        document.getElementById('carousel-1').addEventListener('scroll', function () {
            updateNavigationButtons('carousel-1', 'leftBtn-1', 'rightBtn-1');
        });
        document.getElementById('carousel-2').addEventListener('scroll', function () {
            updateNavigationButtons('carousel-2', 'leftBtn-2', 'rightBtn-2');
        });
        document.getElementById('carousel-3').addEventListener('scroll', function () {
            updateNavigationButtons('carousel-3', 'leftBtn-3', 'rightBtn-3');
        });
        document.getElementById('carousel-4').addEventListener('scroll', function () {
            updateNavigationButtons('carousel-4', 'leftBtn-4', 'rightBtn-4');
        });

        // Initialize navigation button visibility on page load
        updateNavigationButtons('carousel-1', 'leftBtn-1', 'rightBtn-1');
        updateNavigationButtons('carousel-2', 'leftBtn-2', 'rightBtn-2');
        updateNavigationButtons('carousel-3', 'leftBtn-3', 'rightBtn-3');
        updateNavigationButtons('carousel-4', 'leftBtn-4', 'rightBtn-4');
    </script>
</body>

</html>