<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <form action="register.php" method="post">
        <label>Username:</label>
        <input type="text" name="username" required>
        <br>
        <label>Email:</label>
        <input type="email" name="email" required>
        <br>
        <label>Password:</label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" name="register" value="Register">
    </form>
</body>
</html>