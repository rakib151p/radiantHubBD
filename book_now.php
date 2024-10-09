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
    <title>Book Now</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="style_book_now.css">
    <style>
        .bg-black {
            opacity: 0.8;
            background-image: url("professional.jpg");
        }

        .bg-gray-100 {
            background-color: #EDD6FF;
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
            background-repeat: no-repeat;
            background-size: cover;

        }

        #bg {
            background: linear-gradient(-45deg, #FFD8FF, #D6D4FF);
            background-size: 400% 400%;
            /* Ensures the gradient is large enough for smooth animation */
            animation: gradient-bg 15s ease infinite;
        }

        @keyframes gradient-bg {
            0% {
                background-position: 0% 50%;
                /* Start position */
            }

            50% {
                background-position: 100% 50%;
                /* Middle position (gradient shifts to the right) */
            }

            100% {
                background-position: 0% 50%;
                /* End position (returns to start) */
            }
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
    <section class="py-6 bg-gray-100">
        <div>&nbsp;</div>
        <!-- <div>&nbsp;</div> -->
    </section>

    <section id="" class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-left text-gray-800 mb-8">Salons and Parlours</h2>
            <!-- Dropdown Filters -->
            <div class="flex mb-8">
                <select id="districtDropdown" class="p-2 border rounded mr-4" onchange="fetchUpazilla(this.value)">
                    <option value="">Select District</option>
                    <!-- <option value="Dhaka">Dhaka</option> -->
                    <!-- Add district options dynamically -->
                </select>
                <select id="upazilaDropdown" class="p-2 border rounded mr-4" onchange="fetchArea(this.value)">
                    <option value="">Select Upazila</option>
                </select>
                <select id="areaDropdown" class="p-2 border rounded" onchange="filterNow()">
                    <option value="">Select Area</option>
                </select>
            </div>

            <!-- Salons Listing -->
            <div id="salonsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

            <!-- "See More" Button -->
            <div class="text-center mt-8">
                <button id="seeMoreBtn" class="px-4 py-2 bg-pink-600 text-white rounded hover:bg-pink-700"
                    onclick="loadMoreSalons()">See More</button>
            </div>
        </div>
        <!-- don't do anything here -->
        <div class="wave water"></div>
        <div class="wave water"></div>
        <div class="wave water"></div>
        <div class="wave water"></div>
        <!-- don't do anything here -->

    </section>
    </section>

    <script>
        let limit = 12;
        let offset = 0;

        function loadMoreSalons() {
            const district = document.getElementById('districtDropdown').value;
            const upazila = document.getElementById('upazilaDropdown').value;
            const area = document.getElementById('areaDropdown').value;
            const timestamp = new Date().getTime(); // Add a timestamp to prevent caching

            fetch(`fetch_saloons.php?limit=${limit}&offset=${offset}&district=${district}&upazila=${upazila}&area=${area}&t=${timestamp}`)
                .then(response => response.json())
                .then(salons => {
                    if (salons.length > 0) {
                        offset += limit;
                        const container = document.getElementById('salonsContainer');
                        salons.forEach(salon => {
                            const salonElement = `
                                    <a href="saloon_profile/dashboard.php?shop_id=${salon.shop_id}">
                                        <div class="bg-white rounded-lg shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-2xl">
                                            <img src="${salon.image}" alt="${salon.shop_name}" class="w-full h-48 object-cover">
                                            <div class="p-4">
                                                <h3 class="text-lg font-semibold">${salon.shop_name}</h3>
                                                <p class="text-yellow-500">${salon.shop_rating} &#9733; (${salon.shop_customer_count} reviews)</p>
                                                <p>${salon.shop_city}</p>
                                                <p>${salon.shop_title}</p>
                                            </div>
                                        </div>
                                    </a>`;
                            container.insertAdjacentHTML('beforeend', salonElement);
                        });
                    } else {
                        alert('no saloon found');
                    }
                });
        }
        function filterNow() {
            offset = 0;
            document.getElementById('salonsContainer').innerHTML = '';
            loadMoreSalons();
        }
        // Load initial 10 salons on page load
        document.addEventListener('DOMContentLoaded', loadMoreSalons);
        function fetchUpazilla(district) {
            console.log("Selected district: " + district); // Debugging line to ensure district is being captured
            if (district === "") {
                document.getElementById('upazilaDropdown').innerHTML = "<option value=''>Select Upazila</option>";
                document.getElementById('areaDropdown').innerHTML = "<option value=''>Select Area</option>";
                return;
            }

            // Fetch Upazilas based on selected district
            fetch(`fetch_upazilas.php?district=${district}`)
                .then(response => response.json())
                .then(upazilas => {
                    console.log(upazilas); // Debugging line to check fetched upazilas
                    let options = "<option value=''>Select Upazila</option>";
                    upazilas.forEach(upazila => {
                        options += `<option value="${upazila.upazilla_name}">${upazila.upazilla_name}</option>`;
                    });
                    document.getElementById('upazilaDropdown').innerHTML = options;

                    // Reset area dropdown
                    document.getElementById('areaDropdown').innerHTML = "<option value=''>Select Area</option>";
                })
                .catch(error => {
                    alert("error");
                    console.error("Error fetching upazilas:", error);
                });
        }


        function fetchArea(upazila) {
            if (upazila === "") {
                document.getElementById('areaDropdown').innerHTML = "<option value=''>Select Area</option>";
                return;
            }
            let district = document.getElementById('districtDropdown').value;
            // console.log(district);
            fetch(`fetch_area.php?upazila=${upazila}&district=${district}`)
                .then(response => response.json())
                .then(areas => {
                    let options = "<option value=''>Select Area</option>";
                    areas.forEach(area => {
                        options += `<option value="${area.area_name}">${area.area_name}</option>`;
                    });
                    document.getElementById('areaDropdown').innerHTML = options;
                })
                .catch(error => {
                    console.error("Error fetching areas:", error);
                });
        }

        function fetchDistrict() {
            fetch('fetch_district.php')
                .then(response => response.json())
                .then(districts => {
                    let options = "<option value=''>Select District</option>";
                    districts.forEach(district => {
                        options += `<option value="${district.district_name}">${district.district_name}</option>`;
                    });
                    document.getElementById('districtDropdown').innerHTML = options;
                })
                .catch(error => {
                    alert("Error fetching districts:", error);
                    console.log("Error fetching districts:", error);
                });
        }

        document.addEventListener('DOMContentLoaded', fetchDistrict);
        // document.addEventListener('DOMContentLoaded', fetchUpazilla);
    </script>

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
</body>

</html>