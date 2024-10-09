<?php
require 'mysql_connection.php';

// Set default values for limit and offset
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 12;
$offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;
$district = isset($_GET['district']) ? $_GET['district'] : '';
$upazila = isset($_GET['upazila']) ? $_GET['upazila'] : '';
$area = isset($_GET['area']) ? $_GET['area'] : '';

// SQL query
$sql = "SELECT shop_id, shop_name, shop_rating, shop_customer_count, shop_city, shop_title 
        FROM barber_shop 
        WHERE status = 1";

// Append WHERE conditions dynamically based on filters
$whereClauses = [];
$params = [];

if (!empty($district)) {
    $whereClauses[] = "shop_state = ?";
    $params[] = $district;
}

if (!empty($upazila)) {
    $whereClauses[] = "shop_city = ?";
    $params[] = $upazila;
}

if (!empty($area)) {
    $whereClauses[] = "shop_area = ?";
    $params[] = $area;
}

if (!empty($whereClauses)) {
    $sql .= " AND " . implode(' AND ', $whereClauses);
}

// Add ordering and limit
$sql .= " ORDER BY shop_customer_count DESC LIMIT ? OFFSET ?";

// Add limit and offset to the params
$params[] = $limit;
$params[] = $offset;

// Prepare and execute the SQL statement
$stmt = $conn->prepare($sql);
$types = str_repeat('s', count($params) - 2) . 'ii'; // 's' for strings, 'i' for integers
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Fetch saloons
$saloons = [];
while ($row = $result->fetch_assoc()) {
    $saloons[] = [
        'shop_id' => $row['shop_id'],
        'shop_name' => $row['shop_name'],
        'shop_rating' => $row['shop_rating'],
        'shop_customer_count' => $row['shop_customer_count'],
        'shop_city' => $row['shop_city'],
        'shop_title' => $row['shop_title'],
        'image' => 'image/shop/' . $row['shop_id'] . '.jpeg'
    ];
}
// Set JSON header and output saloons
header('Content-Type: application/json');
echo json_encode($saloons);
?>