<?php
// File to store the latest data
$dataFile = 'latest_data.json';
$timeoutDuration = 60; // 1 minute timeout duration (in seconds)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive data from ESP32 and save it to the file
    $data = file_get_contents('php://input');
    $dataArray = json_decode($data, true);
    
    // Add a timestamp to the data to track the time of the last update
    $dataArray['timestamp'] = time();
    
    // Save formatted JSON with the timestamp
    file_put_contents($dataFile, json_encode($dataArray, JSON_PRETTY_PRINT));
    
    echo 'Data received';
} else {
    // Serve the latest data
    if (file_exists($dataFile)) {
        $data = json_decode(file_get_contents($dataFile), true);
        
        // Calculate the age of the data in seconds
        $currentTime = time();
        $dataAge = $currentTime - $data['timestamp'];
        
        if ($dataAge > $timeoutDuration) {
            // Data is too old (timeout occurred), show disconnected status
            echo json_encode([
                'wifi_status' => 'NO DEVICE FOUND',
                'pumpStatus' => 'OFF',
                'valve1Status' => 'CLOSED',
                'valve2Status' => 'CLOSED',
                'soilMoisture1' => 0,
                'soilMoisture2' => 0,
                'currentWaterAmount1' => 0,
                'currentWaterAmount2' => 0,
                'humidity' => 0,
                'temperature' => 0,
                'waterStatus' => 'WATER LEVEL IS LOW', // Water status when timeout occurs
                'alertMessage1' => '',
                'alertMessage2' => '',
                'alertMessageWater' => '',
            ]);
        } else {
            // Data is recent, serve the latest data
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    } else {
        // No data has been received yet, return default values
        echo json_encode([
            'wifi_status' => 'NO DEVICE FOUND',
            'pumpStatus' => 'OFF',
            'valve1Status' => 'CLOSED',
            'valve2Status' => 'CLOSED',
            'soilMoisture1' => 0,
            'soilMoisture2' => 0,
            'currentWaterAmount1' => 0,
            'currentWaterAmount2' => 0,
            'humidity' => 0,
            'temperature' => 0,
            'waterStatus' => 'WATER LEVEL IS LOW', // Default water status
            'alertMessage1' => '',
            'alertMessage2' => '',
            'alertMessageWater' => '',
        ]);
    }
}

?>
