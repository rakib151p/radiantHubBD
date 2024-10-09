<?php
require 'mysql_connection.php';
$district = $_GET['district'];
$sql = "SELECT DISTINCT shop_city FROM barber_shop where shop_state='$district'";
$result = mysqli_query($conn, $sql);

$upazilla = [];
$cnt=1;
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $upazilla[] = [
            'upazilla_id'=> $cnt,
            'upazilla_name' => $row['shop_city']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($upazilla);
?>
