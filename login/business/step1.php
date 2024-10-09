<?php
require 'mysql_connection.php';
session_start();
$errors = array('email' => '', 'password' => '');
$first_name = $last_name = $email = $mobile = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_SESSION['first-name'] = $_POST['first-name'];
    $last_name = $_SESSION['last-name'] = $_POST['last-name'];
    $email = $_SESSION['email'] = $_POST['email'];
    $password = $_SESSION['password'] = $_POST['password'];
    $mobile = $_SESSION['mobile-number'] = $_POST['mobile-number'];
    $_SESSION['country-code'] = $_POST['country-code'];
    $_SESSION['country'] = $_POST['country'];
    // $sql = "select* from `barber_shop` where `shop_email`= $email ";
    $sql = "SELECT * FROM `barber_shop` WHERE shop_email='$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) === 0) {
        header('Location: step2.php');
        exit();
    } else {
        $errors['email'] = 'E-mail already exist.';
    }
    // Redirect to step2.php
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Business Account</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function validateForm() {
            var password = document.getElementById('password').value;
            var passwordError = document.getElementById('passwordError');
            if (password.length < 6) {
                passwordError.textContent = "Password must be at least 6 characters long.";
                return false; // Prevent form submission
            } else {
                passwordError.textContent = ""; // Clear the error message
            }

            return true; // Allow form submission
        }
    </script>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <a href="http://localhost/rakib/final1/login/create_account.php">
                    <img src="../../image/icon/left-arrow.png" alt="" style="width: 30px; height: 30px;">
                </a>
                <h2 class="text-xl font-bold text-gray-700">Create Your Business Account</h2>
                <div></div> <!-- Empty div to balance the flex spacing -->
            </div>
            <div>
                <h3>
                    <center><b>Owner Details only*</b></center>
                </h3>
            </div>
            <form action="" method="POST" onsubmit="return validateForm()">
                <div class="mb-4">
                    <label for="first-name" class="block text-gray-700 font-bold mb-2">First name *</label>
                    <input type="text" id="first-name" name="first-name" value="<?php echo $first_name; ?>"
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600"
                        required>
                </div>
                <div class="mb-4">
                    <label for="last-name" class="block text-gray-700 font-bold mb-2">Last name *</label>
                    <input type="text" id="last-name" name="last-name" value="<?php echo $last_name; ?>"
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600"
                        required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email *</label>
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>"
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600"
                        required>
                    <div class="red-text" style="color:red"><?php echo $errors['email']; ?></div>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-bold mb-2">Password *</label>
                    <input type="password" id="password" name="password" value="<?php echo $password; ?>"
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600"
                        required>
                    <div id="passwordError" class="red-text" style="color:red"></div>
                </div>
                <div class="mb-4">
                    <label for="mobile-number" class="block text-gray-700 font-bold mb-2">Mobile number *</label>
                    <div class="flex">
                        <select id="country-code" name="country-code"
                            class="border border-gray-300 p-2 rounded-l-lg focus:outline-none focus:border-pink-600">
                            <option value="+880">+880</option>
                        </select>
                        <input type="tel" id="mobile-number" name="mobile-number" value="<?php echo $mobile; ?>"
                            class="border border-gray-300 p-2 rounded-r-lg w-full focus:outline-none focus:border-pink-600"
                            required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="country" class="block text-gray-700 font-bold mb-2">Country *</label>
                    <input type="text" id="country" name="country" value="Bangladesh" readonly
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600"
                        required>
                </div>
                <div class="flex items-center mb-4">
                    <input type="checkbox" id="terms" name="terms" class="text-pink-600 focus:ring-pink-500 h-4 w-4"
                        required>
                    <label for="terms" class="ml-2 text-gray-700">I agree to the Privacy Policy, Terms of
                        Business.</label>
                </div>
                <button type="submit"
                    class="bg-pink-500 text-white p-2 w-full rounded-lg hover:bg-pink-700 transition duration-300">Continue</button>
            </form>
        </div>
    </div>
</body>

</html>