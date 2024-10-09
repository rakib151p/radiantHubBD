
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up / Log In</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <a href="../home.php" class="text-gray-700"><img src="../image/icon/left-arrow1.png" alt="" style="width: 30px; height: 30px;"></a>
                <h1 class="text-2xl font-bold text-gray-700">Log In &nbsp;&nbsp;&nbsp;</h1>
                <div></div> <!-- Empty div to balance the flex spacing -->
            </div>
            <div class="mb-8">
                <p class="text-gray-600 text-center">Select user type</p>
            </div>
            <div class="space-y-4">
                <a href="select_login.php?profile-type=Customer" class="block bg-pink-500 text-white p-4 rounded-lg text-center hover:bg-pink-600 transition duration-300">
                    Customer profile<br><span class="text-sm text-pink-200">For customer only</span>
                </a>
                <a href="select_login.php?profile-type=Business" class="block bg-pink-500 text-white p-4 rounded-lg text-center hover:bg-pink-600 transition duration-300">
                    Business profile<br><span class="text-sm text-pink-200">For business user only</span>
                </a>
                <a href="admin_login.php?profile-type=admin" class="block bg-pink-500 text-white p-4 rounded-lg text-center hover:bg-pink-600 transition duration-300">
                    Admin<br><span class="text-sm text-pink-200">For admin only</span>
                </a>
            </div>
        </div>
    </div>
</body>

</html>
