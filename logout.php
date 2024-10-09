<?php
session_start();  // Start the session
session_destroy();  // Destroy the session
header("Location: home.php");  // Redirect to the login page
exit();  // Ensure no further code is executed
?>
