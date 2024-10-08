<?php

include 'db_connection.php'; // Include your database connection
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1.
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Content-Type: application/json");
// Your existing logic to fetch and return dat
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch all sensor data from the sensor table
$stmt = $pdoConnect->prepare("SELECT * FROM sensors");
if ($stmt->execute()) {
    $sensors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($sensors)) {
        echo json_encode(["error" => "No sensor data found."]);
    } else {
        header('Content-Type: application/json');
        echo json_encode($sensors);
    }
} else {
    echo json_encode(["error" => "Failed to fetch sensor data."]);
}
?>
