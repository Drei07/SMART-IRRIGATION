
<?php

// Database connection parameters
$host = 'localhost';
$db_name = 'smart_irrigation';
$username = 'root';
$password = '';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all sensor data from the sensor table
    $stmt = $pdo->prepare("SELECT * FROM sensors");
    $stmt->execute();
    $sensors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the sensor data as JSON
    header('Content-Type: application/json');
    echo json_encode($sensors);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?> 
