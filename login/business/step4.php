<?php
require 'C:/xampp/htdocs/rakib/final1/mysql_connection.php';
session_start();
$_SESSION['$services']=[];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $_SESSION['district'] = $_POST['district'];
  $_SESSION['upazilla'] = $_POST['upazilla'];
  $_SESSION['area'] = $_POST['area'];
  $_SESSION['landmarks'] = $_POST['landmarks'];
  $_SESSION['latitude'] = $_POST['latitude'];
  $_SESSION['longitude'] = $_POST['longitude'];
  //Redirect to the next step or handle form submission
//   header('Location:submit.php');
//   exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Selection</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 16px;
        }

        .grid-item {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .grid-item.selected {
            background-color: #f9fafb;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transform: scale(1.05);
        }

        .grid-item img {
            max-width: 50px;
            margin-bottom: 8px;
        }

        .ji {
            margin-top: 16px;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-3xl">
            <form class="form" action="step5.php" method="POST" id="serviceForm">
            <a href="" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">
          &larr;
        </a>
                <h1 class="text-2xl font-bold mb-4 text-center text-gray-700">What types of services do you offer?</h1>
                <p class="text-center text-gray-600 mb-8">Choose your primary and up to 4 related service types</p>
                <div class="grid-container">
                    <?php
                    $sql = "SELECT * FROM `service_table`";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="grid-item" onclick="selectService(this)">';
                            echo '<input type="checkbox" id="' . $row['service_id'] . '" name="services[]" value="' . $row['service_name'] . '" class="hidden">';
                            echo '<label for="' . $row['service_id'] . '">';
                            echo '<img src="icon/' . $row['service_id'] . '.png" alt="' . $row['service_name'] . '">';
                            echo '<span>' . $row['service_name'] . '</span>';
                            echo '</label>';
                            echo '</div>';
                        }
                    }
                    ?>
                    <div class="ji">
                        <!-- <label for="other" class="block text-gray-700 font-bold mb-2">Other:</label> -->
                        <!-- <input type="text" id="other" name="other_service"
                            class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600"> -->
                    </div>
                </div>
                <button type="submit"
                    class="bg-pink-500 text-white p-2 w-full rounded-lg hover:bg-pink-700 transition duration-300 mt-6">Continue</button>
            </form>
        </div>
    </div>
    <script>
        let selectedServices = [];

        function selectService(element) {
            const checkbox = element.querySelector('input[type="checkbox"]');
            const index = selectedServices.indexOf(element);
            if (index > -1) {
                selectedServices.splice(index, 1);
                element.classList.remove('selected');
                checkbox.checked = false;
            } else {
                if (selectedServices.length < 4) {
                    selectedServices.push(element);
                    element.classList.add('selected');
                    checkbox.checked = true;
                }
            }

            selectedServices.forEach((el, i) => {
                el.setAttribute('data-order', i + 1);
            });
        }

        document.getElementById('serviceForm').addEventListener('submit', function (event) {
            if (selectedServices.length === 0) {
                event.preventDefault();
                alert('Please select at least one service.');
                return;
            }
            const selectedServiceNames = selectedServices.map(item => item.querySelector('input[type="checkbox"]').value);
            console.log("Selected services:", selectedServiceNames);

            // Optionally, you can submit the form if you want to proceed after logging the data
            this.submit();
        });
    </script>
</body>

</html>