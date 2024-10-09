<?php

    header("Content-Type: application/json");

// File to store the latest data
$dataFile = 'latest_data.json';
$timeoutDuration = 10; // 1 minute timeout duration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive data from ESP32 and save it to the file
    $data = file_get_contents('php://input');
    $dataArray = json_decode($data, true);
    $dataArray['timestamp'] = time(); // Add a timestamp
    file_put_contents($dataFile, json_encode($dataArray, JSON_PRETTY_PRINT)); // Save formatted JSON
    echo 'Data received';
} else {
    // Serve the latest data
    if (file_exists($dataFile)) {
        $data = json_decode(file_get_contents($dataFile), true);
        $currentTime = time();
        $dataAge = $currentTime - $data['timestamp'];
        
        if ($dataAge > $timeoutDuration) {
            echo json_encode([
                'wifi_status' => 'NO DEVICE FOUND',
                'pumpStatus' => 'NOT CONNECTED',
                'valve1Status' => 'NOT CONNECTED',
                'valve2Status' => 'NOT CONNECTED',
                'soilMoisture1' => 0,
                'soilMoisture2' => 0,
                'waterStatus' => 'NOT CONNECTED' // Water status when timeout occurs
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    } else {
        echo json_encode([
            'wifi_status' => 'NO DEVICE FOUND',
            'pumpStatus' => 'NOT CONNECTED',
            'valve1Status' => 'NOT CONNECTED',
            'valve2Status' => 'NOT CONNECTED',
            'soilMoisture1' => 0,
            'soilMoisture2' => 0,
            'waterStatus' => 'NOT CONNECTED' // Default water status
        ]);
    }
}
?>
