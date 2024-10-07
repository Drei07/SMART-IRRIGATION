
<?php

include 'db_connection.php'; // Include your database connection

    // Fetch all sensor data from the sensor table
    $stmt = $pdoConnect->prepare("SELECT * FROM sensors");
    $stmt->execute();
    $sensors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the sensor data as JSON
    header('Content-Type: application/json');
    echo json_encode($sensors);

?> 
