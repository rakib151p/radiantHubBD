<?php
require '../mysql_connection.php';
$error = array('email' => '', 'password' => '');
$email = $password = '';
// $type = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $type = $_GET['profile-type'];
    // echo $type;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // echo 'rakib';
    $email = $_POST['email'];
    $password = $_POST['password'];
    $type = $_POST['profile-type'];
    // echo 'password= '.$password.'<br>'.'email ='.$email;
    if ($type == 'Customer') {
        $sql = "SELECT * FROM customer WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) === 0) {
            $error['email'] = "The email doesn't exist";
        } else {
            $row = mysqli_fetch_assoc($result);
            if ($password === $row['password'] && $row['status'] == 1) {
                session_start();
                $_SESSION['email'] = $email;
                $_SESSION['type'] = 'customer';
                header('Location: ../home.php');
                exit();
            } else if ($password === $row['password']) {
                $error['password'] = "You are banned";
                // echo "
                // <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'>
                //     Swal.fire({
                //         title: 'Sorry!',
                //         text: 'You are banned.',
                //         icon: 'error',
                //         confirmButtonText: 'OK'
                //     });
                // </script>
                // ";
            } else {
                $error['password'] = "Password doesn't match";
            }
        }
    } else {
        $sql = "SELECT * FROM barber_shop WHERE shop_email = '$email'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) === 0) {
            $error['email'] = "The email doesn't exist";
        } else {
            $row = mysqli_fetch_assoc($result);
            if ($password === $row['shop_password']&& $row['status'] == 1) {
                session_start();
                $_SESSION['email'] = $email;
                $_SESSION['type'] = 'shop';
                header('Location: ../home.php');
                exit();
            } else if ($password === $row['password']) {
                $error['password'] = "You are banned";
                // echo "
                // <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'>
                //     Swal.fire({
                //         title: 'Sorry!',
                //         text: 'You are banned.',
                //         icon: 'error',
                //         confirmButtonText: 'OK'
                //     });
                // </script>
                // ";
            }  else {
                $error['password'] = "Password don't match";
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
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <a href="login.php" class="text-gray-700"><img src="../image/icon/left-arrow1.png" alt=""
                    style="width: 30px; height: 30px;"></a>

            <h1 class="text-2xl font-bold mb-8 text-center text-gray-700"><?php echo $type . ' '; ?>Login</h1>
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
                <div class="mb-4">
                    <!-- <label for="profile-type" class="block text-gray-700 font-bold mb-2">Profile Type*</label> -->
                    <!-- <select id="profile-type" name="profile-type"
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600"
                        required>
                        <option value="">Select Profile Type</option>
                        <option value="customer">Customer</option>
                        <option value="business">Business</option>
                    </select> -->
                    <input type="hidden" id="profile-type" name="profile-type" value="<?php echo $type; ?>">
                </div>
                <button type="submit"
                    class="bg-pink-600 text-white p-2 w-full rounded-lg hover:bg-pink-700 transition duration-300">Login</button>
            </form>
            <p class="text-center text-gray-600 mt-4">Don't have an account? <a href="create_account.php"
                    class="text-pink-600 hover:underline">Create Account</a></p>
            <p class="text-pink-600 hover:underline" align="center" style="cursor:pointer;"><a
                    href="forget_password.php"> forgot password?</a></p>
        </div>
    </div>
</body>