<?php
require 'mysql_connection.php';

$sql = "SELECT DISTINCT shop_state FROM barber_shop";
$result = mysqli_query($conn, $sql);

$districts = [];
$cnt=1;
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $districts[] = [
            'district_id'=> $cnt,
            'district_name' => $row['shop_state']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($districts);
?>
