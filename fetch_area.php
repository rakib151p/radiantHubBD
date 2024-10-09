<?php
require 'mysql_connection.php';
$district = $_GET['district'];
$upazila = isset($_GET['upazila']) ? $_GET['upazila'] : '';

$sql = "SELECT distinct shop_area FROM barber_shop WHERE shop_city = '$upazila' AND shop_state='$district'";
$result = mysqli_query($conn, $sql);
$areas = [];
$cnt=1;
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $areas[] = [
            'area_id'=> $cnt,
            'area_name' => $row['shop_area']
        ];
    }
}
echo json_encode($areas);
?>
