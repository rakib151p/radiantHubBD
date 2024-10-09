<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upstats Dashboard</title>
    <!-- <link rel="stylesheet"href="index_style.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: cursive;
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
            /* color: #333; */
            font-weight: bold;
        }

        .main-content {
            flex: 1;
            /* padding: 20px; */
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
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .search-bar {
            position: relative;
            width: 300px;
            height: 40px;
            border-radius: 20px;
            background-color: white;
            padding: 10px 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .search-bar input {
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
            font-size: 16px;
            padding: 0 10px;
        }

        .search-bar img {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
        }

        .notification {
            display: flex;
            align-items: center;
        }

        .notification img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .notification span {
            font-size: 14px;
            color: #555;
            margin-right: 10px;
        }

        .notification h3 {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }

        .dashboard-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            width: calc(50% - 10px);
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .chart-container {
            width: 100%;
            height: 200px;
        }

        .chart-options {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }

        .chart-options button {
            background-color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            margin-left: 5px;
            cursor: pointer;
        }

        .chart-options button.active {
            background-color: #007bff;
            color: white;
        }

        .card-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card-content .number {
            font-size: 40px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .card-content span {
            font-size: 14px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .metrics {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            width: calc(33.33% - 10px);
            margin-bottom: 10px;
        }

        .metrics h3 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .metrics .rating {
            font-size: 24px;
            font-weight: bold;
        }

        .metrics .number {
            font-size: 20px;
            font-weight: bold;
        }

        /* Carousel Container */
        .carousel-container {
            position: relative;
            max-width: 300px;
            margin: auto;
            overflow: hidden;
        }

        /* Carousel Inner Wrapper */
        .carousel-inner {
            display: flex;
            transition: transform 0.5s ease-in-out;
            width: 300%;
        }

        /* Card Style */
        .cards {
            background-color: #71C383;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 10px;
            text-align: center;
            width: 300px;
            /* Each card takes up a third of the width */
            height: 200px;
            box-sizing: border-box;
        }

        /* Name, Rating, and Feedback */
        .cards h3 {
            font-size: 1.5em;
            color: #333;
        }

        .cards .rating {
            color: #FFD700;
            /* Gold star color */
            font-size: 1.2em;
            margin: 10px 0;
        }

        .cards p {
            color: #666;
            font-size: 1em;
            margin-bottom: 15px;
        }

        /* View More Button */
        .view-more {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .view-more:hover {
            background-color: #0056b3;
        }

        /* Navigation Buttons */
        .prev-btn,
        .next-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 50%;
            font-size: 10px;
        }

        .prev-btn {
            left: 10px;
        }

        .next-btn {
            right: 10px;
        }

        .prev-btn:hover,
        .next-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container flex">
        <div class="sidebar bg-white p-4 shadow-md">
            <div class="logo flex items-center mb-4">
                <!-- <img src="logo.png" alt="Upstats Logo" class="w-10 h-10 mr-2"> -->
                <h1 style="color:rgb(211, 106, 124);font-size:38px;"><a href="../home.php"> Radienthub</a></h1>
            </div>
            <ul class="list-none p-5">
                <div class="hover:bg-pink-500 p-3 transition-colors duration-300 rounded-lg"><a href="#"
                        style="color:white;font-size:20px;">Dashboard</a></div>
                <div class="hover:bg-pink-500 p-3 transition-colors duration-300 rounded-lg"><a href="manage_shops.php"
                        style="color:white;font-size:20px;">Manage shops</a></div>
                <div class="hover:bg-pink-500 p-3 transition-colors duration-300 rounded-lg"><a href="manage_customer.php"
                        style="color:white;font-size:20px;">Manage customer</a> </div>
                <div class="hover:bg-pink-500 p-3 transition-colors duration-300 rounded-lg"><a href="check_Report.php"
                        style="color:white;font-size:20px;">Check Reports</a></div>
                <div class="hover:bg-pink-500 p-3 transition-colors duration-300 rounded-lg"><a href="Managed_legal_notice.php"
                        style="color:white;font-size:20px;">Managed legal notice</a></div>
            </ul>
        </div>
        <div class="main-content p-4 flex-1">
            <div class="header flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold" style="margin:0 0 0 40px;">Welcome to Admin Panel</h2>
                <div class="notification flex items-center" style="margin-right:40px;">
                    <img src="1-change1.jpg" alt="Notification Icon" class="w-8 h-8 rounded-full mr-2">
                    <span class="text-gray-500 mr-2">RadientHub</span>
                    <h3 class="text-base font-bold">Admin</h3>
                </div>
            </div>
            <div class="dashboard-content flex flex-wrap justify-between">
                <div class="card bg-white rounded-lg p-4 w-1/2 mb-4 shadow-md">
                    <h3 class="text-xl font-bold mb-2">Response of last 7 days</h3>
                    <div class="chart-container">
                        <canvas id="responseChart"></canvas>
                    </div>
                    <div class="chart-options flex justify-end mt-2">
                        <button
                            class="bg-white border-none px-3 py-1 rounded-md mr-2 focus:outline-none active:bg-blue-500 active:text-white">Montly</button>
                        <button class="bg-white border-none px-3 py-1 rounded-md focus:outline-none">Yearly</button>
                    </div>
                </div>
                <div class="card bg-white rounded-lg p-4 w-1/2 mb-4 shadow-md">
                    <h3 class="text-xl font-bold mb-2">Feedbacks Recieveing</h3>
                    <div class="chart-container">
                        <canvas id="feedbackChart"></canvas>
                    </div>
                    <div class="chart-options flex justify-end mt-2">
                        <button class="bg-white border-none px-3 py-1 rounded-md mr-2 focus:outline-none active:bg-blue-500 active:text-white">Guests
                            Visiting</button>
                        <button class="bg-white border-none px-3 py-1 rounded-md focus:outline-none">Feedbacks</button>
                    </div>
                </div>

                <div class="card  rounded-lg p-4 w-1/2 mb-4 shadow-md" style="background-color:#963FF0;color:white;">
                    <h3 class="text-xl font-bold mb-2">Existing shops</h3>
                    <div class="card-content flex flex-col items-center">
                        <div class="number text-4xl font-bold mb-2">250</div>
                        <span class="text-gray-500" style="color:white;">+22 than last week</span>
                    </div>
                </div>
                <div class="card  rounded-lg p-4 w-1/2 mb-4 shadow-md" style="background-color:#48CEEE;color:white;">
                    <h3 class="text-xl font-bold mb-2">Customers</h3>
                    <div class="card-content flex flex-col items-center">
                        <div class="number text-4xl font-bold mb-2">250</div>
                        <span class="text-gray-500" style="color:white;">+22 than last week</span>
                    </div>
                </div>

                <div class="card bg-white rounded-lg p-4 w-1/2 mb-4 shadow-md">
                    <h3 class="text-xl font-bold mb-2">Members</h3>
                    <div class="chart-container">
                        <canvas id="memberChart"></canvas>
                    </div>
                    <div class="chart-options flex justify-end mt-2">
                        <button
                            class="bg-white border-none px-3 py-1 rounded-md mr-2 focus:outline-none active:bg-blue-500 active:text-white">Yearly</button>
                        <button class="bg-white border-none px-3 py-1 rounded-md focus:outline-none">Montly</button>
                    </div>
                </div>
                <div class="card bg-white rounded-lg p-4 w-1/2 mb-4 shadow-md">
                    <h3 class="text-xl font-bold mb-2">Workers</h3>
                    <div class="chart-container">
                        <canvas id="guestChart"></canvas>
                    </div>
                    <div class="chart-options flex justify-end mt-2">
                        <button
                            class="bg-white border-none px-3 py-1 rounded-md mr-2 focus:outline-none active:bg-blue-500 active:text-white">Yearly</button>
                        <button class="bg-white border-none px-3 py-1 rounded-md focus:outline-none">Montly</button>
                    </div>
                </div>


                <p style="font-size:20px;font-weight:700;">review:</p>
                <div class="carousel-container">

                    <div class="carousel-inner">
                        <!-- Card 1 -->
                        <div class="cards">
                            <h3 style="color:white">Helen Moor</h3>
                            <div class="rating">★★★★★</div>
                            <p style="color:white">Very pleasant atmosphere, especially during these challenging times.
                            </p>

                        </div>

                        <!-- Card 2 -->
                        <div class="cards">
                            <h3>Sandra Stockton</h3>
                            <div class="rating">★★★★★</div>
                            <p>The only salon I trust with my hair! Great service and expertise.</p>

                        </div>

                        <!-- Card 3 -->
                        <div class="cards">
                            <h3>John Doe</h3>
                            <div class="rating">★★★★☆</div>
                            <p>Good experience, though there was a slight delay with my appointment.</p>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <button class="prev-btn">❮</button>
                    <button class="next-btn">❯</button>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const carouselInner = document.querySelector(".carousel-inner");
                        let currentIndex = 0;

                        document.querySelector(".prev-btn").addEventListener("click", () => {
                            if (currentIndex > 0) {
                                currentIndex--;
                                updateCarousel();
                            }
                        });

                        document.querySelector(".next-btn").addEventListener("click", () => {
                            if (currentIndex < 2) { // Adjust this based on the number of cards
                                currentIndex++;
                                updateCarousel();
                            }
                        });

                        function updateCarousel() {
                            carouselInner.style.transform = `translateX(-${currentIndex * 33.33}%)`;
                        }
                    });
                </script>

                <div class="card bg-white rounded-lg p-4 w-full mb-4 shadow-md flex justify-between">
                    <div class="metrics flex flex-col items-center justify-center p-4 border border-gray-300 rounded-lg w-1/3 mr-2"
                        style="background-color:#F95B77;color:white;">
                        <h3 class="text-sm font-bold mb-2">Average Ratings</h3>
                        <div class="rating text-xl font-bold">4.5</div>
                    </div>
                    <div class="metrics flex flex-col items-center justify-center p-4 border border-gray-300 rounded-lg w-1/3 mr-2"
                        style="background-color:#48CEEE;color:white;">
                        <h3 class="text-sm font-bold mb-2">Happy Customers</h3>
                        <div class="number text-lg font-bold">500k</div>
                    </div>
                    <div class="metrics flex flex-col items-center justify-center p-4 border border-gray-300 rounded-lg w-1/3"
                        style="background-color:#963FF0;color:white;">
                        <h3 class="text-sm font-bold mb-2">Unhappy Customers</h3>
                        <div class="number text-lg font-bold">122k</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sample data for charts
        const responseChartData = {
            labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            datasets: [{
                label: 'Response by Location',
                data: [10, 20, 30, 40, 35, 45, 50],
                borderColor: '#007bff',
                borderWidth: 2,
                fill: false
            }]
        };

        const feedbackChartData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Guests Visiting',
                data: [20, 40, 30, 45, 50, 35, 40, 30, 25, 30, 40, 35],
                borderColor: '#007bff',
                borderWidth: 2,
                fill: false
            }, {
                label: 'Feedbacks',
                data: [30, 35, 40, 45, 50, 25, 30, 20, 30, 35, 45, 40],
                borderColor: '#ffc107',
                borderWidth: 2,
                fill: false
            }]
        };

        const memberChartData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Members',
                data: [20, 25, 30, 40, 45, 40, 35, 30, 35, 40, 45, 40],
                borderColor: '#007bff',
                borderWidth: 2,
                fill: false
            }, {
                label: 'Non - Members',
                data: [25, 30, 35, 40, 40, 45, 35, 30, 30, 40, 40, 35],
                borderColor: '#ffc107',
                borderWidth: 2,
                fill: false
            }]
        };

        const guestChartData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'New Guests',
                data: [20, 25, 30, 40, 45, 40, 35, 30, 35, 40, 45, 40],
                borderColor: '#007bff',
                borderWidth: 2,
                fill: false
            }, {
                label: 'Existing Guests',
                data: [25, 30, 35, 40, 40, 45, 35, 30, 30, 40, 40, 35],
                borderColor: '#ffc107',
                borderWidth: 2,
                fill: false
            }]
        };

        // Initialize charts
        const responseChartCanvas = document.getElementById('responseChart').getContext('2d');
        const responseChart = new Chart(responseChartCanvas, {
            type: 'line',
            data: responseChartData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const feedbackChartCanvas = document.getElementById('feedbackChart').getContext('2d');
        const feedbackChart = new Chart(feedbackChartCanvas, {
            type: 'bar',
            data: feedbackChartData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const memberChartCanvas = document.getElementById('memberChart').getContext('2d');
        const memberChart = new Chart(memberChartCanvas, {
            type: 'line',
            data: memberChartData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const guestChartCanvas = document.getElementById('guestChart').getContext('2d');
        const guestChart = new Chart(guestChartCanvas, {
            type: 'line',
            data: guestChartData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Handle chart options click
        const chartOptions = document.querySelectorAll('.chart-options button');
        chartOptions.forEach(button => {
            button.addEventListener('click', () => {
                chartOptions.forEach(b => b.classList.remove('active'));
                button.classList.add('active');
                // Update chart data based on selected option (not implemented in this sample)
            });
        });
    </script>
</body>

</html>