<?php
require '../mysql_connection.php';
session_start();
$error = array('email' => '', 'password' => '');
$email = $password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // echo 'rakib';
    $email = $_POST['email'];
    $password = $_POST['password'];
    if($email=='admin@gmail.com'){
        if($password=='admin'){
            $_SESSION['type']='admin';
            $_SESSION['email']='admin@gmail.com';
            header('Location: ../admin/index.php');
        }else{
            $error['password']='The password is wrong.';
        }
    }else{
        $error['email']='The email is not admin email.';
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <a href="login.php" class="text-gray-700"><img src="../image/icon/left-arrow.png" alt=""
                    style="width: 30px; height: 30px;"></a>

            <h1 class="text-2xl font-bold mb-8 text-center text-gray-700">Admin Login</h1>
            <form method="post" action="">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email*</label>
                    <input type="email" id="email" name="email" placeholder="........@gmail.com"
                        value="<?php echo $email; ?>"
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600"
                        required>
                    <div class="red-text" style="color:red"><?php echo $error['email']; ?></div>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-bold mb-2">Password*</label>
                    <input type="password" id="password" name="password" placeholder="••••••••"
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600"
                        required>
                    <div class="red-text" style="color:red"><?php echo $error['password']; ?></div>
                </div>
                <button type="submit"
                    class="bg-pink-600 text-white p-2 w-full rounded-lg hover:bg-pink-700 transition duration-300">Login</button>
            </form>
            <!-- <p class="text-center text-gray-600 mt-4">Don't have an account? <a href="create_account.php"
                    class="text-pink-600 hover:underline">Create Account</a></p>
            <p class="text-pink-600 hover:underline" align="center" style="cursor:pointer;"><a
                    href="forget_password.php"> forgot password?</a></p> -->
                    <div>&nbsp;</div>
        </div>
    </div>
</body>