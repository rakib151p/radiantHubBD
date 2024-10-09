<?php
    $servername="localhost";
    $username="root";
    $password="";
    $database="radienthub";
    $conn=mysqli_connect($servername,$username,$password,$database);
    if($conn){
        echo "connection is successfull.";
    }else{
         die("connection was not successfull".mysqli_connect_error());
    }


?>