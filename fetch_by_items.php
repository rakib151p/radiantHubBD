<?php
require 'mysql_connection.php';
// session_start();
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 12;
$offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;
// $item_id = isset($_GET['item_id']) ? $_GET['item_id'] : '';
$item_id = $_GET['item_id'];

// SQL query to fetch the shop's that provides the desired service
$sql = "SELECT 
        x.shop_id, 
        x.shop_name, 
        x.shop_rating, 
        x.shop_customer_count, 
        x.shop_city, 
        x.shop_title,
        IFNULL(srv.total_services, 0) AS total_services,
        IFNULL(AVG(x.shop_rating), 0) AS avg_rating,
        CASE 
            WHEN x.shop_customer_count > 500 THEN 'Premium' 
            WHEN x.shop_customer_count BETWEEN 100 AND 500 THEN 'Popular'
            ELSE 'Regular'
        END AS shop_tier
    FROM 
        barber_shop AS x
    JOIN 
        shop_service_table AS z ON z.shop_id = x.shop_id
    LEFT JOIN 
        (SELECT shop_id, COUNT(item_id) AS total_services 
         FROM shop_service_table 
         GROUP BY shop_id) AS srv ON srv.shop_id = x.shop_id
    WHERE 
        z.item_id = ? AND x.status = 1
    GROUP BY 
        x.shop_id
    ORDER BY 
        x.shop_customer_count DESC, avg_rating DESC
    LIMIT ? OFFSET ?
";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind the parameters (item_id, limit, and offset) to the placeholders
$stmt->bind_param('iii', $item_id, $limit, $offset);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();
// $result = mysqli_query($conn, $sql);
$saloons = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $saloons[] = [
            'shop_id' => $row['shop_id'],
            'shop_name' => $row['shop_name'],
            'shop_rating' => $row['shop_rating'],
            'shop_customer_count' => $row['shop_customer_count'],
            'shop_city' => $row['shop_city'],
            'shop_title' => $row['shop_title'],
            'shop_tier' => $row['shop_tier'],
            'image' => 'image/shop/' . $row['shop_id'] . '.jpeg'
        ];
    }
}

echo json_encode($saloons);
?>