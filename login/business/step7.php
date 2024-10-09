<?php
session_start();
require 'mysql_connection.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $members = $_POST['member'] ?? [];
    $_SESSION['members'] = $members;
    header("Location: step8.php");
    exit();
}
$items_list = [];
if (isset($_SESSION['items'])) {
    foreach ($_SESSION['items'] as $serviceIndex => $items) {
        // echo "<h2>Service $serviceIndex</h2>";
        foreach ($items['name'] as $itemIndex => $itemName) {
            $itemPrice = $items['price'][$itemIndex];
            if (!empty($itemName)) {
                // echo "<p>Item: $itemName, Price: $itemPrice</p>";
                $items_list[] = $itemName;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Setup - Members</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .multiselect-container {
            height: 100px;
            overflow-y: auto;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
            <a href="step6.php" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">
                &larr;
            </a>
            <h2 class="text-xl font-bold text-center text-gray-700 mb-8">Enter Member Details</h2>
            <form action="" method="POST" id="memberDetailsForm" enctype="multipart/form-data">
                <table class="w-full mb-4">
                    <thead>
                        <tr>
                            <th class="border p-2">Index</th>
                            <th class="border p-2">Name</th>
                            <th class="border p-2">E-mail</th>
                            <th class="border p-2">Contact</th>
                            <th class="border p-2">Expertise</th>
                            <th class="border p-2">Experience</th>
                            <!-- <th class="border p-2">Picture</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rows = isset($_GET['rows']) ? $_GET['rows'] : 1; // default to 4 rows if not set
                        for ($i = 0; $i < $rows; $i++) {
                            echo "<tr>";
                            echo "<td class='border p-2'>$i</td>";
                            echo "<td class='border p-2'><input type='text' name='member[$i][name]' class='w-full p-1 border rounded' required></td>";
                            echo "<td class='border p-2'><input type='email' name='member[$i][email]' class='w-full p-1 border rounded' required></td>";
                            echo "<td class='border p-2'><input type='text' name='member[$i][contact]' class='w-full p-1 border rounded' required></td>";
                            echo "<td class='border p-2'>
                                    <select name='member[$i][expertise][]' class='w-full p-1 border rounded multiselect-container' multiple required>";
                            foreach ($items_list as $item) {
                                echo "<option value='$item'>$item</option>";
                            }
                            echo "</select>
                                  </td>";
                            echo "<td class='border p-2'><input type='text' name='member[$i][experience]' class='w-full p-1 border rounded' required></td>";
                            //echo "<td class='border p-2'><input type='file' name='member[$i][picture]' class='w-full p-1 border rounded' required></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <button type="submit"
                    class="bg-pink-500 text-white p-2 w-full rounded-lg hover:bg-pink-700 transition duration-300">Submit</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('memberDetailsForm').addEventListener('submit', function (event) {
            const contactInputs = document.querySelectorAll('input[name$="[contact]"]');
            const experienceInputs = document.querySelectorAll('input[name$="[experience]"]');
            let valid = true;

            contactInputs.forEach(input => {
                const value = input.value.trim();
                const bangladeshiPhonePattern = /^\+8801[3-9]\d{8}$/;
                if (!bangladeshiPhonePattern.test(value)) {
                    valid = false;
                    alert('Please enter a valid Bangladeshi contact number starting with +880 followed by 10 digits.');
                }
            });

            experienceInputs.forEach(input => {
                const value = input.value.trim();
                if (isNaN(value) || value === '') {
                    valid = false;
                    alert('Please enter a valid number for experience.');
                }
            });

            if (!valid) {
                event.preventDefault();
            }
        });
    </script>
</body>

</html>