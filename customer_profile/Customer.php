<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        body {
            background-color: #F4F4F4;
            font-family: 'Inter', sans-serif;
        }

        #navbar {
            z-index: 1;
            width: 100%;
            margin-bottom: 20px;
            background-color: #FFFFFF;
        }

        .text-gray-700 {
            font-weight: bolder;
        }

        .t {
            font-family: 'Times New Roman', Times, serif;
            font-size: 50px;
            color: #C71585;
        }

        #sidebar {
            flex-direction: column;
            justify-content: center;
            margin: 5px 0 0 30px;
            width: 234px;
        }

        #sidebar ul li a {
            text-decoration: none;
            line-height: 30px;
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
            font-size: 20px;
            text-decoration: none;
            color: #C71585;
            margin-right: 50px;
        }

        #box2 {
            background-color: white;
            height: 340px;
            width: 400px;
            padding: 10px 0 0 15px;
            border-radius: 20px;
            margin: 30px 0 0 110px;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
        }

        #undernavbar {
            display: flex;
            border-radius: 20px;
            margin-bottom: 80px;
            /* Increased margin-bottom for more space */
        }

        #box1 {
            background-color: #FFFFFF;
            margin: 30px 0 0 110px;
            height: 340px;
            width: 327px;
            border-radius: 20px;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
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

        button {
            height: 35px;
            text-align: center;
        }

        #B {
            font-size: larger;
            font-weight: bolder;
        }
    </style>
</head>

<body>
    <header id="navbar">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="#" class="t text-2xl font-bold">RadiantHub BD</a>
            <nav class="space-x-6">
                <a href="http://localhost/rakib/final1/home.php" class="text-gray-700 hover:text-pink-600">Home</a>
                <a href="#" class="text-gray-700 hover:text-pink-600">About Us</a>
                <a href="#" class="text-gray-700 hover:text-pink-600">Services</a>
                <a href="#" class="text-gray-700 hover:text-pink-600">Book Now</a>
                <a href="#" class="text-gray-700 hover:text-pink-600">Locations</a>
                <a href="contact_us.php" class="text-gray-700 hover:text-pink-600">Contact Us</a>
                <a href="http://localhost/rakib/final1/login/login.php" class="text-gray-700 hover:text-pink-600">Login</a>
                <a href="http://localhost/rakib/final1/login/create_account.php" class="text-gray-700 hover:text-pink-600">Create account</a>
            </nav>
        </div>
    </header>
    <h2 id="tittlemnm">Manage My Account</h2>
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
        </div>
        <div id="box1">
            <div id="profile_edit">
                <a href="#" id="B">My Profile</a><br>
                <a href="My_profile.php"> <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Edit</button></a>
                <img src="istockphoto-1364917563-612x612.jpg" alt="Profile Picture" class="rounded-full">
                <p>Name: Md.Rahadul Islam Jishan</p>
                <p>Email: rahdulsilasmajishan@gmail.com</p>
                <p>Phone: 01508146356</p>
                <p>Registration Date: 01/05/24</p>
                <p>Gender: Male</p>

            </div>
        </div>
        <div id="box2">
            <a href="#" id="B">Address of Booking</a><br>
            <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded"><a>Show</a></button>
            <p>
              
            </p>

            <p>

            </p>

        </div>
    </section>
    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-gray-300">Home</a></li>
                        <li><a href="#" class="hover:text-gray-300">About Us</a></li>
                        <li><a href="#" class="hover:text-gray-300">Services</a></li>
                        <li><a href="#" class="hover:text-gray-300">Book Now</a></li>
                        <li><a href="#" class="hover:text-gray-300">Locations</a></li>
                        <li><a href="#" class="hover:text-gray-300">Contact Us</a></li>
                    </ul>
                </div>
                <!-- Contact Information -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
                    <p class="text-gray-400">Madani Avenue</p>
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
                        <input type="email" placeholder="Enter your email" class="p-2 w-full rounded-l-lg text-gray-800 bg-gray-700">
                        <button type="submit" class="p-2 bg-pink-600 text-white rounded-r-lg hover:bg-pink-700">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>