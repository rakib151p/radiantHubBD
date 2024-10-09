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
    <title>Services</title>
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
            height: 69px;
            width: 100vw;
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




        /* Photo Slider Section */
        .photo-slider {
            position: relative;
            width: 100%;
            max-width: 1200px;
            height: 600px;
            margin: 50px auto;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            background-color: #fff;

        }

        .slider-container {
            display: flex;
            transition: transform 0.7s ease-in-out;

        }

        .slide {
            min-width: 100%;
            position: relative;

        }

        .slide img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 15px;
            transition: transform 0.3s ease;

        }

        .slide img:hover {
            opacity: 1;

        }

        .slide a {
            text-decoration: none;
            display: block;

        }

        .prev,
        .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            padding: 15px;
            cursor: pointer;
            border-radius: 50%;
            transition: background-color 0.3s ease;
        }

        .prev:hover,
        .next:hover {
            background-color: rgba(0, 0, 0, 0.9);
        }

        .prev {
            left: 15px;
        }

        .next {
            right: 15px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .photo-slider {
                height: 400px;
            }

            .prev,
            .next {
                padding: 10px;
            }
        }

        @media (max-width: 480px) {
            .photo-slider {
                height: 300px;
            }
        }

        .under_img {
            position: absolute;
            top: 250px;
            width: 100vw;
            padding-left: 480px;
            padding-top: 25px;
            font-size: 30px;
            background-color: rgba(0, 0, 0, 0.4);
            color: white;
            height: 100px;
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
                        echo '/admin/index.php';
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
    <!-- </section> -->
    <section id="wat">
        <section class="py-12">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Available Services</h2>
                <section class="photo-slider">
                    <div class="slider-container">
                        <div class="slide">
                            <a href="service_item.php?id=<?php echo 'Body Treatments'; ?>">
                                <div class="under_img">Body Treatments</div>
                                <img src="image\services photo\towfiqu-barbhuiya-EDBy1syonFg-unsplash.jpg"
                                    alt="Salon Image 1">
                            </a>
                        </div>
                        <div class="slide">
                            <a href="service_item.php?id=<?php echo 'Educational Services'; ?>">
                                <div class="under_img">Educational Services</div>
                                <img src="image\services photo\pexels-pixabay-159844.jpg" alt="Salon Image 2"
                                    style="background-size: cover;height:600px;">
                            </a>
                        </div>
                        <div class="slide">
                            <a href="service_item.php?id=<?php echo 'Facial Services'; ?>">
                                <div class="under_img">Facial Services</div>
                                <img src="image\services photo\pexels-gustavo-fring-7446671.jpg" alt="Salon Image 3">
                            </a>
                        </div>
                        <div class="slide">
                            <a href="service_item.php?id=<?php echo 'Grooming Services (For Men)'; ?>">
                                <div class="under_img">Grooming Services (For Men)</div>
                                <img src="image\services photo\premium_photo-1658506695819-15989df6d489.avif"
                                    alt="Salon Image 2" style="background-size: cover;height:600px;">
                            </a>
                        </div>
                        <div class="slide">
                            <a href="service_item.php?id=<?php echo 'Hair Services'; ?>">
                                <div class="under_img">Hair Services</div>
                                <img src="image\services photo\premium_photo-1661542995743-cfdbf376014b.avif"
                                    alt="Salon Image 2" style="background-size: cover;height:600px;">
                            </a>
                        </div>
                        <div class="slide">
                            <a href="service_item.php?id=<?php echo 'Miscellaneous Services'; ?>">
                                <div class="under_img">Miscellaneous Services</div>
                                <img src="image\services photo\pexels-delbeautybox-211032-853427.jpg"
                                    alt="Salon Image 2" style="background-size: cover;height:600px;">
                            </a>
                        </div>
                        <div class="slide">
                            <a href="service_item.php?id=<?php echo 'Nail Services'; ?>">
                                <div class="under_img">Nail Services</div>
                                <img src="image\services photo\pexels-rdne-7755558.jpg" alt="Salon Image 2"
                                    style="background-size: cover;height:600px;">
                            </a>
                        </div>
                        <div class="slide">
                            <a href="service_item.php?id=<?php echo 'Retail Products'; ?>">
                                <div class="under_img">Retail Products</div>
                                <img src="image\services photo\christelle-bourgeois-Aq7paIaerrY-unsplash.jpg"
                                    alt="Salon Image 2" style="background-size: cover;height:600px;">
                            </a>
                        </div>
                        <div class="slide">
                            <a href="service_item.php?id=<?php echo 'Spa Services'; ?>">
                                <div class="under_img">Spa Services</div>
                                <img src="image\services photo\christin-hume-0MoF-Fe0w0A-unsplash.jpg"
                                    alt="Salon Image 2" style="background-size: cover;height:600px;">
                            </a>
                        </div>
                        <div class="slide">
                            <a href="service_item.php?id=<?php echo 'Specialized Treatments'; ?>">
                                <div class="under_img">Specialized Treatments</div>
                                <img src="image\services photo\premium_photo-1664300409226-52250ffa7601.avif"
                                    alt="Salon Image 2" style="background-size: cover;height:600px;">
                            </a>
                        </div>
                        <div class="slide">
                            <a href="service_item.php?id=<?php echo 'Wellness Services'; ?>">
                                <div class="under_img">Wellness Services</div>
                                <img src="image\services photo\pexels-sarah-chai-7262688.jpg" alt="Salon Image 2"
                                    style="background-size: cover;height:600px;">
                            </a>
                        </div>


                    </div>
                    <button class="prev" onclick="changeSlide(-1)">&#10094;</button>
                    <button class="next" onclick="changeSlide(1)">&#10095;</button>


                </section>

                <!-- don't do anything here -->
                <div class="wave water"></div>
                <div class="wave water"></div>
                <div class="wave water"></div>
                <div class="wave water"></div>
                <!-- don't do anything here -->

        </section>
        <script>
            let currentSlide = 0;

            function showSlide(index) {
                const slides = document.querySelectorAll('.slide');
                const totalSlides = slides.length;

                // Reset the slider if index is out of bounds
                if (index >= totalSlides) {
                    currentSlide = 0;
                } else if (index < 0) {
                    currentSlide = totalSlides - 1;
                } else {
                    currentSlide = index;
                }

                // Move slider to show the current slide
                const sliderContainer = document.querySelector('.slider-container');
                sliderContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
            }

            function changeSlide(direction) {
                showSlide(currentSlide + direction);
            }

            // Auto-slide functionality
            setInterval(function () {
                changeSlide(1);
            }, 10000); // Auto-slide every 5 seconds

            // Show the first slide on load
            showSlide(currentSlide);
        </script>

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