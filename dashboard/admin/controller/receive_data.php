<?php
include_once __DIR__ . '/../../../database/dbconfig.php';

$database = new Database();
$pdo = $database->dbConnection();
$proxyServerUrl = 'https://servify.cloud/dashboard/admin/controller/data.php';

// Fetch data from the proxy server
$response = file_get_contents($proxyServerUrl);
if ($response !== false) {
    $data = json_decode($response, true);
    
    // Ensure the data was parsed correctly
    if ($data) {
        // Check for changes and log them
        logStatusChange($pdo, 'valve1', $data['valve1Status'] ?? 'CLOSED', 'CLOSED');
        logStatusChange($pdo, 'valve2', $data['valve2Status'] ?? 'CLOSED', 'CLOSED');
        logStatusChange($pdo, 'pump', $data['pumpStatus'] ?? 'OFF', 'OFF');
        logStatusChange($pdo, 'water', $data['waterStatus'] ?? 'NO WATER', 'NO WATER');

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
            'waterStatus' => 'NO WATER' // Default water status
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
        'waterStatus' => 'NO WATER' // Default water status
    ]);

    error_log("Failed to fetch data from proxy server.");
}

function logStatusChange($pdo, $sensor, $currentStatus, $previousStatus) {
    // Check if the current status is different from the previous state
    if ($currentStatus !== $previousStatus) {
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
    }
}
?>
