<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['memberCount']=$rows = $_POST['members'];
    header("Location: step7.php?rows=$rows");
    exit();
}
?>