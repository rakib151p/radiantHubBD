<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Store the services and items_list in session
    $_SESSION['items'] = $_POST['items'];
    // $_SESSION['other'] = $_POST['others'];
    // $_SESSION['items_list'] = $_POST['items_list'];
    // header('Location:step6.php');
    // exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .selected {
            background-color: #be185d;
            /* Orange background when selected */
            color: white;
            /* White text when selected */
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <div>
                <a href="step5.php" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-1 px-3 rounded">
                    &larr;
                </a>
            </div>
            <div>
                <h2 class="text-xl font-bold text-center text-gray-700">How Many Members do You Have?</h2>
            </div>

            <p class="text-center text-gray-600 mb-8">Enter the number of members</p>
            <form action="step7process.php" method="POST" id="memberForm">
                <div class="mb-4">
                    <input type="number" name="members" id="members" class="p-4 border rounded w-full text-center"
                        min="0" placeholder="Enter the exact number of members" required>
                </div>
                <button type="submit"
                    class="bg-pink-500 text-white p-2 w-full rounded-lg hover:bg-pink-700 transition duration-300">Continue</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('memberForm').addEventListener('submit', function (event) {
            const members = document.getElementById('membersInput');
            if (members.value === '') {
                event.preventDefault();
                alert('Please enter the number of members.');
            }
        });
    </script>

</body>

</html>