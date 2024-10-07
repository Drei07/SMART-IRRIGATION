<?php

include 'db_connection.php'; // Include your database connection

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
