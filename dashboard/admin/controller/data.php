<?php
// File to store the latest data
$dataFile = 'latest_data.json';
$timeoutDuration = 10; // 1 minute timeout duration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive data from ESP32 and save it to the file
    $data = file_get_contents('php://input');
    $dataArray = json_decode($data, true);
    file_put_contents($dataFile, json_encode($dataArray, JSON_PRETTY_PRINT)); // Save formatted JSON
    echo 'Data received';
} else {
    // Serve the latest data
    if (file_exists($dataFile)) {
        $data = json_decode(file_get_contents($dataFile), true);
        
        if ($dataAge > $timeoutDuration) {
            echo json_encode([
                'wifi_status' => 'NO DEVICE FOUND',
                'pumpStatus' => 'OFF',
                'valve1Status' => 'CLOSED',
                'valve2Status' => 'CLOSED',
                'soilMoisture1' => 0,
                'soilMoisture2' => 0,
                'waterStatus' => 'WATER LEVEL IS LOW' // Water status when timeout occurs
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    } else {
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
}
?>
