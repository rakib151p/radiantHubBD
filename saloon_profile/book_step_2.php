<?php
session_start();

include '../mysql_connection.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $worker_id = $_POST['worker_id'];
    $shop_id = $_POST['shop_id'];
    $item_id = $_POST['item_id'];
    $customer_id = $_SESSION['customer_id'];
    $item_description = $_POST['item_description'];
    $item_price = $_POST['item_price'];
    $_SESSION['check']=1;
    // echo 'worker: ' . $_POST['worker_id'].'<br>';
    // echo 'shop:' . $_POST['shop_id'].'<br>';
    // echo 'item:' . $_POST['item_id'].'<br>';
    // echo 'item-description:'.$_POST['item_description'].'<br>';
    // echo 'item-price:'.$_POST['item_price'].'<br>';
    // echo 'customer ID: ' . $_SESSION['customer_id'].'<br>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Date & Time</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .professional-card {
            background-color: white;
            width: 100%;
            cursor: pointer;
            transition: box-shadow 0.3s ease;
        }

        .professional-card.selected {
            border-color: #f43f5e;
            box-shadow: 0 0 10px 10px #f43f5e;
        }

        .professional-card:hover {
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        @media (min-width: 1080px) {
            .professional-card {
                width: calc(33.33% - 1rem);
            }
        }

        @media (min-width: 1900px) {
            .professional-card {
                width: calc(25% - 1rem);
            }
        }

        .selected-date {
            background-color: #FF1493;
            color: white;
        }

        .selected {
            background-color: #FF1493;
            color: white;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="max-w-screen-xl mx-auto p-6 lg:p-8">
        <div class="flex flex-col lg:flex-row lg:space-x-6">
            <div class="lg:w-5/6">
                <div class="flex items-center space-x-4 mb-8">
                    <a href="javascript:window.history.back()">
                        <img src="../image/icon/left-arrow.png" alt="Back" class="w-8 h-8">
                    </a>
                    <h2 class="text-4xl font-bold text-gray-800">Select Your Desired Time</h2>
                </div>

                <!-- Time Selection Section -->
                <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-lg mx-auto">
                    <!-- Month and Year -->
                    <div class="flex justify-between items-center mb-4">
                        <span id="month-year" class="text-lg font-semibold">October 2024</span>
                        <div class="space-x-4">
                            <button id="prev-month" class="text-gray-500">&lt;</button>
                            <button id="next-month" class="text-gray-500">&gt;</button>
                        </div>
                    </div>

                    <!-- Date Selection -->
                    <div id="date-buttons" class="grid grid-cols-7 gap-2 mb-6"></div>
                    <div>
                        <label type="hideden" name="selected_date" id="fetch_selected_date"></label>
                        <input type="hidden" name="selected_date" id="fetch_selected_date">
                        <label name="selected_time" id="fetch_selected_time"></label>
                         <input type="hidden" id="fetch_selected_time" name="selected_time">
                    </div>
                    <!-- Time Slot dynamic -->
                    <div id="time-slots">

                    </div>
                    
                    <input type="hidden" id="selected-time" name="selected-time">
                </div>
            </div>

            <!-- Shop and Service Details -->
            <div class="lg:w-2/6 bg-white p-6 shadow-md rounded-lg mt-6 lg:mt-0">
                <?php
                $sql_shop = "SELECT * FROM barber_shop WHERE shop_id = $shop_id";
                $result_shop = mysqli_query($conn, $sql_shop);

                if ($result_shop && mysqli_num_rows($result_shop) > 0) {
                    $shop = mysqli_fetch_assoc($result_shop);
                    echo '<h1 class="text-4xl font-bold mb-4">' . htmlspecialchars($shop['shop_name']) . "</h1>";
                    ?>
                    <p class="text-gray-700 ">
                        <?php echo $shop['shop_rating'] . '⭐ (' . $shop['shop_customer_count'] . ' reviews)'; ?>
                    </p>
                    <p class="text-gray-500 text-2xl"><?php echo $shop['shop_city']; ?></p>
                    <p class="text-gray-500">
                        <?php echo $shop['shop_area'] . ',' . $shop['shop_landmark_1'] . ',' . $shop['shop_landmark_2']; ?>
                    </p>
                    <hr class="my-4">

                    <p class="text-gray-700 font-semibold">
                        <?php
                        $sql_item = "SELECT item_name FROM item_table WHERE item_id = $item_id";
                        $result_item = mysqli_query($conn, $sql_item);

                        if ($result_item && mysqli_num_rows($result_item) > 0) {
                            $item = mysqli_fetch_assoc($result_item);
                            echo '<h3 class="text-lg text-2xl font-bold mb-4">' . htmlspecialchars($item['item_name']) . '</h3>';
                        }
                        ?>
                    </p>
                    <p class="text-gray-500">Your desired Expert:
                        <?php
                        $sql_worker = "SELECT worker_name FROM shop_worker WHERE worker_id = $worker_id";
                        $result_worker = mysqli_query($conn, $sql_worker);
                        if ($result_worker && mysqli_num_rows($result_worker) > 0) {
                            $worker_name = mysqli_fetch_assoc($result_worker);
                            echo $worker_name['worker_name'];
                        }
                        ?>
                    </p>
                    <p class="text-gray-500">Time: 1 hour(aprx) &bull; <?php echo $item_description; ?></p>
                    <p class="text-gray-700 font-bold mt-4">BDT: <?php echo $item_price; ?>TK</p>
                    <hr class="my-4">
                    <p class="text-lg font-bold">Total: <span class="text-green-600">BDT:<?php echo $item_price; ?>TK</span>
                    </p>
                <?php }
                ?>
                <form id="worker-selection-form" action="success.php" method="POST">
                    <input id="shop_id" type="hidden" name="shop_id" value="<?php echo $shop_id; ?>">
                    <input id="worker_id" type="hidden" name="worker_id" value="<?php echo $worker_id; ?>">
                    <input id="item_id" type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                    <input id="selected_time_id" type="hidden" name="selected_time_id" value="">
                    <input id="selected_date_id" type="hidden" name="selected_date_id" value="">
                    <input id="check" type="hidden" name="check" value="1">
                    <button id="confirmButton"
                        class="w-full bg-pink-400 text-white py-2 mt-4 rounded-lg transition duration-150 hover:bg-pink-700">Confirm</button>
                </form>
                <!-- <button id="continue-button" disabled
                    class="w-full bg-pink-400 text-white py-2 mt-4 rounded-lg transition duration-150 hover:bg-pink-700">Continue</button> -->
            </div>
        </div>
    </div>

    <script>
        function checkInputs() {
            document.getElementById('confirmButton').disabled = true;
        }
        window.onload = checkInputs;
        function addTimeSlots(selectedDate) {
            let shop_id = document.getElementById('shop_id').value;
            let worker_id = document.getElementById('worker_id').value;
            let item_id = document.getElementById('item_id').value;

            
            // alert(typeof(selectedDate));
            // alert(selectedDate + ' ' + shop_id + ' ' + worker_id + ' ' + item_id);

            // Define the time slots to add
            let timeSlots = [];

            fetch(`available_slots.php?shop_id=${shop_id}&worker_id=${worker_id}&item_id=${item_id}&date=${selectedDate}`)
                .then(response => response.json())
                .then(resultSlots => {
                    timeSlots.push(...resultSlots);
                    // alert(resultSlots); // Log the result to see what you get
                    // Get the container where the time slots will be added
                    const timeSlotsContainer = document.getElementById('time-slots');
                    timeSlotsContainer.innerHTML = ''; // Clear previous slots
                    // Create and append each time slot
                    timeSlots.forEach(function (time) {
                        // Create a new div element for the time slot
                        const timeSlotDiv = document.createElement('div');
                        timeSlotDiv.className = 'time-slot border border-pink-500 text-black-500 font-semibold text-center p-4 rounded-md';
                        timeSlotDiv.setAttribute('data-value', time);
                        timeSlotDiv.textContent = time;

                        // Append the new time slot to the container
                        timeSlotsContainer.appendChild(timeSlotDiv);

                        // Add event listener to handle time selection
                        timeSlotDiv.addEventListener('click', function () {
                            // Remove the 'selected' class from all time slots
                            document.querySelectorAll('#time-slots .time-slot').forEach(function (slot) {
                                slot.classList.remove('selected');
                            });
                            // Add the 'selected' class to the clicked time slot
                            timeSlotDiv.classList.add('selected');
                            // Update the hidden input with the selected time value
                            const selectedTime = timeSlotDiv.getAttribute('data-value');
                            document.getElementById('selected-time').value = selectedTime;
                            document.getElementById('fetch_selected_time').innerText = selectedTime;
                            document.getElementById('selected_date_id').value = selectedDate;
                            document.getElementById('selected_time_id').value = selectedTime;
                            if (selectedTime) {
                                document.getElementById('confirmButton').disabled = false;
                            }
                            // Log the selected time to the console
                            console.log('Selected time:', selectedTime);
                        });
                    });
                    if (timeSlots.length == 0) Swal.fire({
                        icon: 'info',
                        title: 'Not Available',
                        text: 'This Day is currently unavailable!',
                        confirmButtonText: 'Okay',
                        background: '#fff',
                        color: '#333',
                        iconColor: '#ffcc00',
                        confirmButtonColor: '#3085d6',
                        backdrop: `rgba(0,0,123,0.4)`,
                    });
                    // alert(timeSlots); // Moved here to reflect the updated timeSlots
                })
                .catch(error => {
                    console.error('Error fetching slots:', error);
                });
        }

        function updateMonthYear(date) {
            const monthNames = [
                "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
            ];
            const monthYearElement = document.getElementById('month-year');
            const month = monthNames[date.getMonth()];
            const year = date.getFullYear();
            monthYearElement.textContent = `${month} ${year}`;
        }
        // Call the function to add time slots when the page loads
        // document.addEventListener('DOMContentLoaded', addTimeSlots);

        let today = new Date();
        // alert('today'+today);
        let currentStartDate = new Date(today); // Keep a reference to the current start date
        let selectedButton = null; // Track the selected button

        // Function to generate the next 7 days
        function generateDays(startDate) {
            // alert(startDate);
            const dateContainer = document.getElementById('date-buttons');
            const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            // Clear previous dates
            dateContainer.innerHTML = '';
            // Update month and year based on the start date
            updateMonthYear(startDate);
            // Generate the 7-day range starting from startDate
            for (let i = 0; i < 7; i++) {
                let date = new Date(startDate);
                date.setDate(startDate.getDate() + i);  // Move the date forward by i days

                // Reset the time to midnight to avoid timezone issues
                date.setHours(date.getHours() + 6);

                let day = date.getDate();
                let dayName = dayNames[date.getDay()];
                let formattedDate = date.toISOString().split('T')[0];  // Format YYYY-MM-DD
                // Create the button
                let button = document.createElement('button');
                button.className = 'flex flex-col items-center text-center px-2 py-1 border rounded-md';
                button.setAttribute('data-date', formattedDate);
                // Create the date element
                let dateSpan = document.createElement('span');
                dateSpan.className = 'h-10 w-10 flex items-center justify-center';
                dateSpan.textContent = day;
                // Create the day name element
                let dayNameSpan = document.createElement('span');
                dayNameSpan.className = 'text-sm text-gray-700 mt-1';
                dayNameSpan.textContent = dayName;
                // Append date and day to the button
                button.appendChild(dateSpan);
                button.appendChild(dayNameSpan);
                // Append the button to the container
                dateContainer.appendChild(button);
                // Event listener to handle date selection
                button.addEventListener('click', function () {
                    const selectedDate = this.getAttribute('data-date');
                    // alert(selectedDate);
                    // Remove pink background from the previously selected button
                    if (selectedButton) {
                        selectedButton.classList.remove('selected-date');
                    }
                    // Add pink background to the newly selected button
                    this.classList.add('selected-date');
                    selectedButton = this; // Update the selected button

                    document.getElementById('fetch_selected_date').innerText = selectedDate;
                    // selectedDate.setDate(selectedDate.getDate() + 1);
                    // Clear the previous time slots before adding new ones
                    document.getElementById('time-slots').innerHTML = '';
                    // alert(selectedDate);
                    addTimeSlots(selectedDate);
                    // Call the function to add time slots for the selected date
                    console.log("Selected Date:", selectedDate);


                    // Log selected date
                });
            }
        }

        // Initial render of dates (next 7 days from today)
        generateDays(currentStartDate);

        // Handle previous and next buttons
        document.getElementById('prev-month').addEventListener('click', function () {
            currentStartDate.setDate(currentStartDate.getDate() - 7);  // Move 7 days back
            generateDays(currentStartDate);
        });
        document.getElementById('next-month').addEventListener('click', function () {
            currentStartDate.setDate(currentStartDate.getDate() + 7);  // Move 7 days forward
            generateDays(currentStartDate);
        });

        // Initial update of month and year
        updateMonthYear(today);

        document.querySelectorAll('#time-slots .time-slot').forEach(function (timeSlot) {
            timeSlot.addEventListener('click', function () {
                // Remove the 'selected' class from all time slots
                document.querySelectorAll('#time-slots .time-slot').forEach(function (slot) {
                    slot.classList.remove('selected');
                });
                // Add the 'selected' class to the clicked time slot
                timeSlot.classList.add('selected');
                // Update the hidden input with the selected time value
                const selectedTime = timeSlot.getAttribute('data-value');
                document.getElementById('selected-time').value = selectedTime;
                document.getElementById('fetch_selected_time').innerText = selectedTime;
                // Log the selected time to the console
                console.log('Selected time:', selectedTime);
            });
        });
    </script>
</body>

</html>