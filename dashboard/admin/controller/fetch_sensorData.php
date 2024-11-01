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
        $this->conn = $database->dbConnection(); // Set the connection correctly
    }

    // Method to fetch all sensor data with modified attributes
    public function getAllSensors()
    {
        // Fetch all sensors
        $query = 'SELECT * FROM sensors';
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $sensors = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($sensors)) {
                return ["error" => "No sensor data found."];
            }

            $modifiedSensors = [];
            foreach ($sensors as $sensor) {
                // Fetch plant details
                $plantQuery = 'SELECT plant_name, dry_threshold, watered_threshold FROM plants WHERE id = :plant_id';
                $stmtPlant = $this->conn->prepare($plantQuery);
                $stmtPlant->execute([':plant_id' => $sensor['plant_id']]);
                $plant = $stmtPlant->fetch(PDO::FETCH_ASSOC);

                $plantName = $plant ? $plant['plant_name'] : 'Unknown';
                $dryThreshold = $plant['dry_threshold'] ?? 'Unknown';
                $wateredThreshold = $plant['watered_threshold'] ?? 'Unknown';

                // Fetch day names
                $selectedDays = explode(',', $sensor['selected_days']);
                $dayNames = [];
                if (!empty($selectedDays)) {
                    $placeholders = implode(',', array_fill(0, count($selectedDays), '?'));
                    $dayQuery = "SELECT day FROM day WHERE id IN ($placeholders)";
                    $stmtDay = $this->conn->prepare($dayQuery);
                    $stmtDay->execute($selectedDays);
                    $dayResults = $stmtDay->fetchAll(PDO::FETCH_ASSOC);
                    $dayNames = array_column($dayResults, 'day');
                }

                // Fetch irrigation status for each sensor
                $statusQuery = 'SELECT * FROM sensorIrrigatedStatus WHERE id = :id';
                $sensorIrrigationStatus = [];

                for ($i = 1; $i <= 4; $i++) {
                    $stmtStatus = $this->conn->prepare($statusQuery);
                    $stmtStatus->execute([':id' => $i]);
                    $statusRow = $stmtStatus->fetch(PDO::FETCH_ASSOC);
                    $sensorIrrigationStatus[] = $statusRow['status'] ; // Default to false if no row is found
                }

                // Prepare the modified sensor data
                $modifiedSensor = [
                    "sensor_id" => $sensor['sensor_id'],
                    "mode" => $sensor['mode'],
                    "plantName" => $plantName,
                    "dry_threshold" => $dryThreshold,
                    "watered_threshold" => $wateredThreshold,
                    "waterAmountAM" => $sensor['water_amount_am'],
                    "waterAmountPM" => $sensor['water_amount_pm'],
                    "startTimeAM" => $sensor['start_time_am'],
                    "startTimePM" => $sensor['start_time_pm'],
                    "selectedDays" => $dayNames,
                    "current_day" => date("l"),    // Current day
                    "current_time" => date("H:i:s"), // Current time
                    "sensor1IrrigatedAM" => $sensorIrrigationStatus[0], // sensor 1 AM
                    "sensor1IrrigatedPM" => $sensorIrrigationStatus[1], // sensor 1 PM
                    "sensor2IrrigatedAM" => $sensorIrrigationStatus[2], // sensor 2 AM
                    "sensor2IrrigatedPM" => $sensorIrrigationStatus[3], // sensor 2 PM
                ];

                // Add to the result array
                $modifiedSensors[] = $modifiedSensor;
            }

            return $modifiedSensors;
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
