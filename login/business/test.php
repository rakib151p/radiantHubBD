<?php
session_start();
// $_SESSION['first-name'] = $_POST['first-name'];
// $_SESSION['last-name'] = $_POST['last-name'];
// $_SESSION['email'] = $_POST['email'];
// $_SESSION['password'] = $_POST['password'];
// $_SESSION['mobile-number'] = $_POST['mobile-number'];
// $_SESSION['country-code'] = $_POST['country-code'];
// $_SESSION['country'] = $_POST['country'];
// $_SESSION['business-name'] = $_POST['business-name'];
// $_SESSION['website'] = $_POST['website'];
// $_SESSION['district'] = $_POST['district'];
// $_SESSION['upazilla'] = $_POST['upazilla'];
// $_SESSION['area'] = $_POST['area'];
// $_SESSION['landmarks'] = $_POST['landmarks'];
// $_SESSION['latitude'] = $_POST['latitude'];
// $_SESSION['longitude'] = $_POST['longitude'];
// $_SESSION['services'] = $services;
// $_SESSION['other_service'] = $other_service;
// $_SESSION['items_list'] = $items_list;
echo "First Name   : ".$_SESSION['first-name']."<br>";
echo "Last Name    : ".$_SESSION['last-name']."<br>";
echo "E-mail       : ".$_SESSION['email']."<br>";
echo "password     : ".$_SESSION['password']."<br>";
echo "mobile-number: ".$_SESSION['mobile-number']."<br>";
echo "country      : ".$_SESSION['country']."<br>";
echo "business-name: ".$_SESSION['business-name']."<br>";
echo "Website      : ".$_SESSION['website']."<br>";
echo "district     : ".$_SESSION['district']."<br>";
echo "upazilla     : ".$_SESSION['upazilla']."<br>";
echo "area         : ".$_SESSION['area']."<br>";
foreach ($_SESSION["services"] as $key => $value) {
    echo " " . $key . " " . $value . "</br>";
}

if (isset($_SESSION['items'])) {
    foreach ($_SESSION['items'] as $serviceIndex => $items) {
        echo "<h2>Service $serviceIndex</h2>";
        foreach ($items['name'] as $itemIndex => $itemName) {
            $itemPrice = $items['price'][$itemIndex];
            if (!empty($itemName)) {
                echo "<p>Item: $itemName, Price: $itemPrice</p>";
            }
        }
    }
}

// Print all items for the other service
if (isset($_SESSION['other'])) {
    foreach ($_SESSION['other'] as $otherIndex => $items) {
        echo "<h2>Other Service $otherIndex</h2>";
        foreach ($items['name'] as $itemIndex => $itemName) {
            $itemPrice = $items['price'][$itemIndex];
            if (!empty($itemName)) {
                echo "<p>Item: $itemName, Price: $itemPrice</p>";
            }
        }
    }
}
echo '<table border="1">';
$members=$_SESSION['members'];
foreach ($members as $member) {
    echo '<tr><td>'. $member['name'] .'</td>';
    echo '<td>'. $member['email'] .'</td>';
    echo '<td>'. $member['contact'] .'</td>';
    $expertise = $member['expertise'];
    echo '<td>';
    foreach ($member['expertise'] as $expertiseIndex => $expertise) {
        echo $expertiseIndex. " ".$expertise.'<br>';
    }
    echo '</td>';
    echo '<td>'. $member['experience'] .'</td></tr>';
    // if (isset($_FILES['member']['name'][$index]['picture'])) {
    //     $file_tmp = $_FILES['member']['tmp_name'][$index]['picture'];
    //     $file_name = $_FILES['member']['name'][$index]['picture'];
    //     $upload_dir = 'uploads/';
    //     $upload_file = $upload_dir . basename($file_name);

    //     if (move_uploaded_file($file_tmp, $upload_file)) {
    //         $_SESSION['members'][$index]['picture'] = $upload_file;
    //     }
    // }
}
echo "</table>";
// header("Location: next_step.php");
// exit();
?>