<?php

$proxyServerUrl = 'https://servify.cloud/dashboard/admin/controller/data.php'; // Replace with your proxy server UR

$response = file_get_contents($proxyServerUrl);
if ($response !== false) {
    header('Content-Type: application/json');
    echo $response;
} else {
    header('Content-Type: applicataion/json');
    echo json_encode([
        'wifi_status' => 'NO DEVICE FOUND',
        'pumpStatus' => 'NOT CONNECTED',
        'valve1Status' => 'NOT CONNECTED',
        'valve2Status' => 'NOT CONNECTED',
        'soilMoisture1' => 0,
        'soilMoisture2' => 0,
        'waterStatus' => 'NOT CONNECTED' // Default water status
    ]);

    error_log("Failed to fetch data from proxy server.");
}


?>