<?php

include_once __DIR__ . '/../../../database/dbconfig.php';

// Sensor Class to fetch data from the sensor table
class Sensor
{
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($db)
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    // Method to fetch all sensor data
    public function getAllSensors()
    {
        $query = 'SELECT * FROM sensors';
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $sensors = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($sensors)) {
                return ["error" => "No sensor data found."];
            }
            return $sensors;
        } else {
            return ["error" => "Failed to fetch sensor data."];
        }
    }
}

// Instantiate the Database and Sensor classes
try {
    $sensor = new Sensor($db); // Pass the connection to the Sensor class

    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    header("Content-Type: application/json");

    // Fetch all sensors and return as JSON
    $sensorData = $sensor->getAllSensors();
    echo json_encode($sensorData);
} catch (Exception $e) {
    // Handle any unexpected errors
    echo json_encode(["error" => $e->getMessage()]);
}
?>
