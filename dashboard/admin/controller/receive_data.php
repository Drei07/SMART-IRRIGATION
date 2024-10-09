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
        logStatusChange($pdo, 'Valve 1', $currentValve1Status);
        logStatusChange($pdo, 'Valve 2', $currentValve2Status);
        logStatusChange($pdo, 'Water', $currentPumpStatus);
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

?>
