<?php
require 'mysql_connection.php';

// Set default values for limit and offset
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 12;
$offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;
$search = isset($_GET['district']) ? $_GET['district'] : ''; // Search input

// Construct the SQL query in a single statement
$sql = "SELECT shop_id, 
                shop_name, 
                shop_rating, 
                shop_customer_count, 
                shop_city, 
                shop_state, 
                shop_area, 
                shop_title 
        FROM barber_shop 
        WHERE status = 1 " .
    (!empty($search) ? "AND (shop_state LIKE ? OR shop_city LIKE ? OR shop_area LIKE ?) " : "") .
    "ORDER BY shop_customer_count DESC 
        LIMIT ? OFFSET ?";

// Prepare and execute the SQL statement
$stmt = $conn->prepare($sql);

// Bind parameters for search, limit, and offset
if (!empty($search)) {
    $searchTerm = '%' . $search . '%'; // Use LIKE for partial match
    $stmt->bind_param('sssii', $searchTerm, $searchTerm, $searchTerm, $limit, $offset); // 3 strings for search + 2 integers for limit and offset
} else {
    $stmt->bind_param('ii', $limit, $offset); // Only limit and offset if no search
}

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