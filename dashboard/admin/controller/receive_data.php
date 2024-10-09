<?php
include_once __DIR__ . '/../../../database/dbconfig.php';

$database = new Database();
$pdo = $database->dbConnection();
$proxyServerUrl = 'https://servify.cloud/dashboard/admin/controller/data.php';

// Static array to store the last known statuses
static $lastKnownStatus = [
    'valve1' => 'CLOSED',
    'valve2' => 'CLOSED',
    'pump' => 'OFF',
    'water' => 'WATER LEVEL IS LOW', // Set default status
];

// Fetch data from the proxy server
$response = file_get_contents($proxyServerUrl);
if ($response !== false) {
    $data = json_decode($response, true);
    
    // Ensure the data was parsed correctly
    if ($data) {
        // Get the current status for each sensor
        $currentValve1Status = $data['valve1Status'] ?? 'CLOSED';
        $currentValve2Status = $data['valve2Status'] ?? 'CLOSED';
        $currentPumpStatus = $data['pumpStatus'] ?? 'OFF';
        $currentWaterStatus = $data['waterStatus'] ?? 'WATER LEVEL IS LOW';

        // Check for changes and log them
        logStatusChange($pdo, 'valve1', $currentValve1Status);
        logStatusChange($pdo, 'valve2', $currentValve2Status);
        logStatusChange($pdo, 'pump', $currentPumpStatus);
        logStatusChange($pdo, 'water', $currentWaterStatus);

        // Return the fetched data as JSON response
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        // Handle invalid response
        header('Content-Type: application/json');
        echo json_encode([
            'wifi_status' => 'NO DEVICE FOUND',
            'pumpStatus' => 'OFF',
            'valve1Status' => 'CLOSED',
            'valve2Status' => 'CLOSED',
            'soilMoisture1' => 0,
            'soilMoisture2' => 0,
            'waterStatus' => 'WATER LEVEL IS LOW' // Default water status
        ]);
    }
} else {
    // Handle fetch failure
    header('Content-Type: application/json');
    echo json_encode([
        'wifi_status' => 'NO DEVICE FOUND',
        'pumpStatus' => 'OFF',
        'valve1Status' => 'CLOSED',
        'valve2Status' => 'CLOSED',
        'soilMoisture1' => 0,
        'soilMoisture2' => 0,
        'waterStatus' => 'WATER LEVEL IS LOW' // Default water status
    ]);

    error_log("Failed to fetch data from proxy server.");
}

/**
 * Log the status change for a given sensor.
 *
 * @param PDO $pdo The PDO instance for database access.
 * @param string $sensor The sensor name.
 * @param string $currentStatus The current status of the sensor.
 */
function logStatusChange($pdo, $sensor, $currentStatus) {
    global $lastKnownStatus; // Access the static array
    
    // Check if the current status is different from the last known status
    if ($currentStatus !== $lastKnownStatus[$sensor]) {
        // Prepare SQL insert for logs
        $logStmt = $pdo->prepare("
            INSERT INTO sensor_logs (sensor, status, created_at)
            VALUES (:sensor, :status, NOW())
        ");

        // Bind parameters and execute the log query
        $logStmt->execute([
            ':sensor' => ucfirst($sensor), // Capitalize the sensor name
            ':status' => $currentStatus,
        ]);

        // Update the last known status
        $lastKnownStatus[$sensor] = $currentStatus; // Update the status
    }
}
?>
