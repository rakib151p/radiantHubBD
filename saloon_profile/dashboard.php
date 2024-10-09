<?php
require '../mysql_connection.php';
session_start();
// Fetch the shop_id from the URL
$shop_id = $_GET['shop_id'];


// Fetch salon details
$sql = "SELECT * FROM `barber_shop` WHERE shop_id=$shop_id";
$result = mysqli_query($conn, $sql);
$salon = mysqli_fetch_assoc($result);

// Fetch salon services
$sql_services = "SELECT * FROM `shop_service_table` as `x` join `item_table` as `y` on x.item_id=y.item_id WHERE shop_id=$shop_id";
$result_services = mysqli_query($conn, $sql_services);

// Fetch customer reviews
// $sql_reviews = "SELECT * FROM `reviews` WHERE shop_id=$shop_id ORDER BY review_date DESC";
// $result_reviews = mysqli_query($conn, $sql_reviews);

//log at recent_log
if (isset($_SESSION['customer_id'])) {
    $customer_id = $_SESSION['customer_id'];
    // SQL query to check if customer and shop have the same gender
    $sql = "SELECT gender
            FROM barber_shop
            WHERE shop_id = $shop_id
            AND gender = (SELECT gender FROM customer WHERE customer_id = $customer_id);
            ";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $sql = "INSERT INTO `recent_log` (`customer_id`, `shop_id`, `logged_time`) 
        VALUES ('$customer_id', '$shop_id', NOW())";
        // Execute the query
        if (mysqli_query($conn, $sql)) {
            // echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        // If genders don't match, redirect to the previous page
        header("Location: " . $_SERVER['HTTP_REFERER']); // Redirect to the previous page
        exit(); // Always exit after header redirection
    }
}


$sql_reviews = [];
//message start


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $salon['shop_name']; ?> - Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

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
            font-family: 'Times New Roman', Times, serif;
            /* color: #9B1C31; */
            font-size: 50px;
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
            font-weight: bold;
            color: #F8F6FF;
            z-index: 1;
            margin: 0;
            padding: 0;
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

        * {
            font-family: cursive;

        }


        /* Photo Slider Section */
        .photo-slider {
            position: relative;
            width: 100%;
            max-width: 1500px;
            height: 800px;
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





        .chat-wrapper {
            position: sticky;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        .chat-circle {
            width: 60px;
            height: 60px;
            background-color: #f1c40f;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .chat-container {
            width: 300px;
            height: 500px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            padding: 15px;
            position: relative;
            margin-top: 10px;
        }

        .hidden {
            display: none;
        }

        .title {
            font-size: 16px;
            text-align: center;
            margin-bottom: 10px;
            color: #333;
        }

        .chat {
            flex-grow: 1;
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 10px;
            overflow-y: auto;
            margin-bottom: 10px;
        }

        .message {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }

        .user {
            justify-content: flex-end;
        }

        .bot {
            justify-content: flex-start;
        }

        .user .text {
            background-color: #f1c40f;
            color: white;
            border-radius: 10px 10px 0 10px;
            padding: 10px;
            max-width: 60%;
        }

        .bot .text {
            background-color: #e0e0e0;
            color: black;
            border-radius: 10px 10px 10px 0;
            padding: 10px;
            max-width: 60%;
        }

        .button {
            background-color: #F7366C;
            color: white;
            border: none;
            padding: 8px;
            border-radius: 50%;
            margin-left: 5px;

            cursor: pointer;
        }

        .button i {
            font-size: 14px;
            /* Decreased icon size */
        }
    </style>
</head>

<body class="bg-gray-100">

    <!-- Header -->
    <header id="navbar" class="bg-white shadow">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="../home.php" class="t text-2xl font-bold text-pink-700" style="font-family: cursive;">RadiantHub
                BD</a>
            <nav class="space-x-6">
                <a href="../home.php" class="text-gray-700 hover:text-pink-600">Home</a>
                <a href="../service.php" class="text-gray-700 hover:text-pink-600">Services</a>
                <a href="../book_now.php" class="text-gray-700 hover:text-pink-600">Book Now</a>
                <a href="../location.php" class="text-gray-700 hover:text-pink-600">Locations</a>
                <a href="../contact_us.php" class="text-gray-700 hover:text-pink-600">Contact Us</a>

                <a href="
                <?php
                if (!isset($_SESSION['type'])) {
                    echo '../login/login.php';
                } else {
                    if ($_SESSION['type'] === 'customer') {
                        echo '../customer_profile/My_profile.php';
                    } else {
                        echo '../business_dashboard/saloon_dashboard.php';
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
                        } else {
                            echo $_SESSION['shop_name'];
                        }
                    }
                    ?>
                </a>
                <?php
                if (isset($_SESSION['type'])) {
                    echo '<a href="../logout.php" class="text-gray-700 hover:text-pink-600">Logout</a>';
                }
                ?>
                <!-- <a href="http://localhost/rakib/final1/login/create_account.php"
                    class="text-gray-700 hover:text-pink-600">Create account</a> -->
            </nav>
        </div>
    </header>

    <!-- empty space creation -->
    <section class="py-6 bg-gray-100">
        <div>&nbsp;</div>
    </section>

    <!-- Salon Profile Section -->
    <section class="py-6 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex flex-col md:flex-row">
                    <img src="<?php echo '../image/shop/' . $shop_id . '.jpeg'; ?>"
                        alt="<?php echo $salon['shop_name']; ?>"
                        class="w-full md:w-1/3 rounded-lg shadow-lg object-cover">
                    <div class="md:ml-6 mt-6 md:mt-5">
                        <h1 class="text-3xl font-bold text-gray-800"><?php echo $salon['shop_name']; ?></h1>
                        <p class="text-2xl mt-3 text-sm text-gray-900"><?php echo $salon['shop_title']; ?></p>
                        <p class="mt-2 text-sm text-gray-600"><?php echo $salon['shop_city']; ?></p>
                        <p class="mt-2 text-sm text-gray-600"><?php echo $salon['shop_landmark_1']; ?></p>
                        <p class="mt-2 text-sm text-gray-600"><?php echo $salon['shop_landmark_2']; ?></p>
                        <p class="mt-2 text-sm text-gray-600"><?php echo $salon['shop_landmark_3']; ?></p>
                        <div class="mt-4">
                            <span class="font-bold text-yellow-500 text-lg"><?php echo $salon['shop_rating']; ?>
                                &#9733;</span>
                            <?php
                             $sql_review_count="SELECT count(*) as total FROM review_shop where shop_id='$shop_id'";
                             $result_review_count=mysqli_query($conn,$sql_review_count);
                             $total_review=0;
                             if($result_review_count){
                                $total_review_row=$result_review_count->fetch_assoc();
                                $total_review=$total_review_row['total'];
                             }
                            ?>
                            <span class="text-sm text-gray-600">(<?php echo $total_review; ?>
                                reviews)</span>
                            <span class="text-sm text-gray-600">For <?php echo $salon['gender']; ?>
                                Only</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Salon gallery Section -->
    <section class="photo-slider">
        <div class="slider-container">
            <?php
            // $shop_id = $_SESSION['shop_id'];
            $sql = "SELECT * FROM shop_gallery WHERE shop_id = '$shop_id'";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {

                    echo '<div class="slide">';
                    echo '<img src="' . $row['image'] . '" alt="Shop Image">';
                    echo '</div>';
                }
            } else {
                echo '<p>No images available for this shop.</p>';
            }

            ?>
        </div>
        <button class="prev" onclick="changeSlide(-1)"><img src="../image/icon/left-arrow1.png"
        alt="Right Arrow" class="w-8 h-8 opacity-75 hover:opacity-100"></button>
        <button class="next" onclick="changeSlide(1)"><img src="../image/icon/right-arrow.png"
        alt="Right Arrow" class="w-8 h-8 opacity-75 hover:opacity-100"></button>
    </section>
    <!-- chatwithsalonn -->
    <?php
    if (isset($_SESSION['customer_id'])) {
        echo '<div class="chat-wrapper" id="chat-wrapper">
        <div class="chat-circle" id="chat-circle">
            <i class="fas fa-comments"></i>
        </div>

        <div class="chat-container hidden" id="chat-container">
            <input type="hidden" id="chat_shop_id" name="chat_shop_id" value="' . $shop_id . '">
            <div class="title">Let\'s Chat</div>
            <div class="chat" id="chat"></div>
            <input type="text" class="input" id="input" placeholder="Type your message here...."
                style=" height: 40px;border-radius:20px;border:1px solid transparent;border:1px solid pink;" />

            <button class="button" id="button"><i class="fa-brands fa-telegram"></i></button>
        </div>
    </div>';
    }
    ?>
    <!-- chat_javascript -->
    <script>
        function displayUserMessage(message) {
            let chat = document.getElementById("chat");
            let userMessage = document.createElement("div");
            userMessage.classList.add("message");
            userMessage.classList.add("user");
            let userAvatar = document.createElement("div");
            userAvatar.classList.add("avatar");
            let userText = document.createElement("div");
            userText.classList.add("text");
            userText.innerHTML = message;
            userMessage.appendChild(userAvatar);
            userMessage.appendChild(userText);
            chat.appendChild(userMessage);
            chat.scrollTop = chat.scrollHeight;
        }

        // Display the bot message on the chat
        function displayBotMessage(message) {
            let chat = document.getElementById("chat");
            let botMessage = document.createElement("div");
            botMessage.classList.add("message");
            botMessage.classList.add("bot");
            let botAvatar = document.createElement("div");
            botAvatar.classList.add("avatar");
            let botText = document.createElement("div");
            botText.classList.add("text");
            botText.innerHTML = message;
            botMessage.appendChild(botAvatar);
            botMessage.appendChild(botText);
            chat.appendChild(botMessage);
            chat.scrollTop = chat.scrollHeight;
        }

        // Send the user message and get the bot response
        function sendMessage() {
            let input = document.getElementById("input").value;
            if (input) {
                let shop_id = document.getElementById("chat_shop_id").value; // Get shop_id
                // alert(shop_id); // For debugging
                // alert(input); // For debugging
                displayUserMessage(input); // Display user message

                // Create the JSON data to send
                let data = JSON.stringify({
                    message: input,
                    shop_id: shop_id
                });

                // Send the message to the server via AJAX
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "send_message.php", true);
                xhr.setRequestHeader("Content-Type", "application/json"); // Set content type to JSON

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        let response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Display bot message after successful message insertion
                            setTimeout(function () {
                                displayBotMessage("Thank you for your interest in our salon. We will reach you soon.");
                            }, 1000);
                        } else {
                            console.error("Error sending message:", response.error);
                        }
                    }
                };

                xhr.send(data); // Send the JSON data
                document.getElementById("input").value = ""; // Clear input field
            }
        }




        // Add a click event listener to the button
        document.getElementById("button").addEventListener("click", sendMessage);

        // Add a keypress event listener to the input
        document.getElementById("input").addEventListener("keypress", function (event) {
            if (event.keyCode == 13) {
                sendMessage();
            }
        });
    </script>
    <script>
        // Toggle chat container visibility
        document.getElementById('chat-circle').addEventListener('click', function () {
            const chatContainer = document.getElementById('chat-container');
            chatContainer.classList.toggle('hidden');
        });

        // Make chat-wrapper (circle and chat window) draggable
        let chatWrapper = document.getElementById('chat-wrapper');
        let isDragging = false;
        let offsetX, offsetY;

        chatWrapper.addEventListener('mousedown', function (e) {
            isDragging = true;
            offsetX = e.clientX - chatWrapper.getBoundingClientRect().left;
            offsetY = e.clientY - chatWrapper.getBoundingClientRect().top;
        });

        document.addEventListener('mousemove', function (e) {
            if (isDragging) {
                chatWrapper.style.left = e.clientX - offsetX + 'px';
                chatWrapper.style.top = e.clientY - offsetY + 'px';
                chatWrapper.style.position = 'fixed';
            }
        });

        document.addEventListener('mouseup', function () {
            isDragging = false;
        });
    </script>




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

    <!----- Photo Gallary -->

    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Our Services</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <?php
                while ($service = mysqli_fetch_assoc($result_services)) {
                    echo '<div class="bg-white p-6 rounded-lg shadow-lg text-center">';
                    echo '<div class="text-pink-600 text-5xl mb-4">💇‍♂️</div>';
                    echo "<h3 class=\"text-xl font-semibold\">{$service['item_name']}</h3>";
                    echo "<p class=\"mt-2 text-sm text-gray-600\">{$service['item_description']}</p>";
                    echo "<p class=\"mt-2 text-lg font-bold text-gray-800\">{$service['item_price']} BDT</p>";

                    // Add a form to handle the POST request
                    echo '<form action="book_step_1.php" method="POST">';
                    echo "<input type=\"hidden\" name=\"item_id\" value=\"{$service['item_id']}\">";
                    echo "<input type=\"hidden\" name=\"item_description\" value=\"{$service['item_description']}\">";
                    echo "<input type=\"hidden\" name=\"item_price\" value=\"{$service['item_price']}\">";
                    echo "<input type=\"hidden\" name=\"shop_id\" value=\"$shop_id\">";
                    echo '<button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-lg mt-4">Book Now</button>';
                    echo '</form>';

                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </section>
    <section>
        <?php
        $sql_worker = "SELECT worker_id,
                              worker_name,
                              rating,
                              worker_picture
                            FROM shop_worker
                            WHERE shop_id='$shop_id'
                            ORDER BY worker_id
    ";
        $result_worker = mysqli_query($conn, $sql_worker);
        ?>
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Our Experts</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <?php
                while ($row_worker = mysqli_fetch_assoc($result_worker)) {
                    echo '<div class="bg-white p-6 rounded-lg shadow-lg text-center">';
                    $image=$row_worker['worker_picture'];
                    echo '<div class="mb-4 flex justify-center items-center"><img src="../image/worker/'.$image.'" alt="Service Icon" width="100" height="100"></div>';

                    echo "<h3 class=\"text-xl font-semibold\">{$row_worker['worker_name']}</h3>";
                    echo "<p class=\"mt-2 text-sm text-gray-600\">Rating :{$row_worker['rating']}</p>";
                    echo "<p class=\"mt-2 text-lg text-gray-600\">Expert in :";
                    $worker_id = $row_worker['worker_id'];
                    $sql_expertise = "SELECT * FROM (SELECT * FROM worker_expertise WHERE worker_id='$worker_id') as a JOIN item_table ON a.item_id=item_table.item_id";
                    $result_item = mysqli_query($conn, $sql_expertise);
                    $tmp = 1;
                    while ($row_item = $result_item->fetch_assoc()) {
                        echo '<li>' . $tmp++ . ' :' . $row_item['item_name'] . '</li>';
                    }
                    echo '</p></div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!--Customer Reviews Section-->
    <!-- Reviews Container -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Reviews</h2>
            <div class="relative">
                <div id="carousel-7" class="carousel flex space-x-4 overflow-x-auto scroll-smooth">
                    <?php
                    $sql = "SELECT r.review, r.star, r.date_and_time, 
                                        (SELECT first_name FROM customer WHERE customer_id = r.customer_id) AS first_name, 
                                        (SELECT last_name FROM customer WHERE customer_id = r.customer_id) AS last_name, 
                                        (SELECT image FROM customer WHERE customer_id = r.customer_id) AS image, 
                                        (SELECT district FROM customer WHERE customer_id = r.customer_id) AS district 
                                    FROM review_shop r
                                    WHERE r.shop_id='$shop_id'";
                    // Fetch reviews
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Review Card
                            echo '
                        <div class="bg-gray-100 p-6 rounded-lg shadow-lg min-w-[300px] max-w-[300px] flex-none">
                            <div class="flex items-center mb-4">
                                <div class="text-yellow-400">';
                            // Output star ratings
                            for ($i = 0; $i < $row['star']; $i++) {
                                echo '<span>&#9733;</span>'; // Filled star
                            }
                            for ($i = 0; $i < 5 - $row['star']; $i++) {
                                echo '<span>&#9734;</span>'; // Empty star
                            }
                            echo '<br><span style="color: red;">' . htmlspecialchars($row['date_and_time']) . '</span>';
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
                        onclick="scrollCarousel('carousel-7', -300)"><img src="../image/icon/left-arrow1.png"
                        alt="Right Arrow" class="w-8 h-8 opacity-75 hover:opacity-100"></div>
                </div>

                <!-- Right Arrow -->
                <div class="absolute inset-y-0 right-0 flex items-center">
                    <div class="scroll-arrow right-arrow bg-white p-2 rounded-full shadow-md cursor-pointer"
                        onclick="scrollCarousel('carousel-7', 300)"><img src="../image/icon/right-arrow.png"
                        alt="Right Arrow" class="w-8 h-8 opacity-75 hover:opacity-100"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- review about our shop-->
    <section>

        <div class="container mx-auto px-4 py-7">
            <?php if (isset($_SESSION['customer_id'])): ?>
                <h2 class="text-2xl font-bold text-center mb-8">Review About Our services</h2>
                <!-- Write a Review Button -->
                <button id="writeReviewBtn" class="bg-pink-500 text-white px-4 py-2 rounded-lg">Write a Review</button>
            <?php endif; ?>

            <!-- Modal Overlay -->
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
                        <!-- <?php echo $shop_id; ?> -->
                        <input type="hidden" name="shop_id" value="<?php echo $shop_id; ?>">
                        <input type="hidden" name="customer_id" value="<?php echo $_session['customer_id']; ?>">
                        <!-- Submit and Cancel Buttons -->
                        <button type="submit" id="submitReview"
                            class="bg-green-500 text-white px-4 py-2 rounded-lg">Submit Review</button>
                        <button type="button" id="cancelReview" class="ml-4 text-gray-600">Cancel</button>
                    </form>
                </div>
            </div>


            <!-- JavaScript for Review Modal and Functionality -->
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

                // Show the review modal when "Write a Review" button is clicked
                writeReviewBtn.onclick = () => {
                    reviewModal.classList.remove('hidden');
                    modalOverlay.classList.remove('hidden');
                };

                // Hide the review modal when "Cancel" button is clicked
                cancelReview.onclick = () => {
                    reviewModal.classList.add('hidden');
                    modalOverlay.classList.add('hidden');
                    resetStars();
                };

                // Star rating selection
                stars.forEach(star => {
                    star.addEventListener('click', () => {
                        selectedRating = parseInt(star.getAttribute('data-value'));
                        ratingInput.value = selectedRating; // Set hidden rating input value

                        // Reset all stars before filling the selected ones
                        stars.forEach(s => s.classList.remove('text-yellow-500'));

                        // Highlight selected stars
                        for (let i = 0; i < selectedRating; i++) {
                            stars[i].classList.add('text-yellow-500');
                        }
                    });
                });

                // Character count update
                reviewText.addEventListener('input', () => {
                    const textLength = reviewText.value.length;
                    charCount.textContent = `${textLength} / 500`;
                });

                // Reset stars and form values when closing the modal
                function resetStars() {
                    selectedRating = 0;
                    stars.forEach(s => s.classList.remove('text-yellow-500'));
                    ratingInput.value = ''; // Clear hidden rating input
                    reviewText.value = '';
                    charCount.textContent = '0 / 500';
                }

                // Validate form submission to ensure rating is selected
                reviewForm.addEventListener('submit', (e) => {
                    if (selectedRating === 0) {
                        alert("Please select a star rating.");
                        e.preventDefault();
                    }
                });
            </script>

    </section>



    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="../home.php" class="hover:text-gray-300">Home</a></li>
                        <li><a href="../service.php" class="hover:text-gray-300">Services</a></li>
                        <li><a href="../book_now.php" class="hover:text-gray-300">Book Now</a></li>
                        <li><a href="../location.php" class="hover:text-gray-300">Locations</a></li>
                        <li><a href="../contact_us.php" class="hover:text-gray-300">Contact Us</a></li>
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
                        <li><a href="#" class="hover:text-gray-300">Facebook</a></li>
                        <li><a href="#" class="hover:text-gray-300">Instagram</a></li>
                        <li><a href="#" class="hover:text-gray-300">Twitter</a></li>
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

        // Initialize navigation button visibility on page load
        updateNavigationButtons('carousel-1', 'leftBtn-1', 'rightBtn-1');
        updateNavigationButtons('carousel-2', 'leftBtn-2', 'rightBtn-2');
        updateNavigationButtons('carousel-3', 'leftBtn-3', 'rightBtn-3');
    </script>
</body>

</html>