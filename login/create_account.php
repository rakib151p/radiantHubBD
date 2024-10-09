<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up / Log In</title>
    <link rel="stylesheet" href="create_account.css">
</head>

<body>
    <div class="b">
        <div class="container">
            <div class="left">

                <div class="box">
                    <div class="back-arrow">
                        <a href="#">&larr;</a>
                    </div>
                    <h1>Sign Up</h1>
                    <div class="buttons">
                        <a href="step2.php" target=_blank class="button">Customer<br><span>Book your preferable salon</span></a>
                        <a href="http://localhost/rakib/final1/login/business/step1.php" class="button">Business purposes<br><span>Open your own salon business</span></a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html> -->
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
                <a href="http://localhost/rakib/final1/login/login.php" class="text-gray-700"><img src="../image/icon/left-arrow.png" alt="" style="width: 30px; height: 30px;"></a>
                <h1 class="text-2xl font-bold text-gray-700">Sign Up&nbsp;&nbsp;&nbsp;</h1>
                <div></div> <!-- Empty div to balance the flex spacing -->
            </div>
            <div class="mb-8">
                <p class="text-gray-600 text-center">Choose your account type</p>
            </div>
            <div class="space-y-4">
                <a href="http://localhost/rakib/final1/login/customer/step1.php" class="block bg-pink-500 text-white p-4 rounded-lg text-center hover:bg-pink-600 transition duration-300">
                    Customer<br><span class="text-sm text-pink-200">Book your preferable salon</span>
                </a>
                <!-- <a href="http://localhost/rakib/final1/login/customer/step1.php" class="block bg-pink-500 text-white p-4 rounded-lg text-center hover:bg-pink-600 transition duration-300">
                    Business purposes<br><span class="text-sm text-pink-200">Open your own salon business</span> -->
                </a>
                <a href="http://localhost/rakib/final1/login/business/step1.php" class="block bg-pink-500 text-white p-4 rounded-lg text-center hover:bg-pink-600 transition duration-300">
                    Business purposes<br><span class="text-sm text-pink-200">Open your own salon business</span>
                </a>
            </div>
        </div>
    </div>
</body>

</html>
