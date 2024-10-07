<?php
// Database configuration
$host = 'localhost';       // Server where the MySQL database is hosted
$dbname = 'smart_irrigation'; // Database name
$username = 'root';        // Database username
$password = '';            // Database password (leave blank if no password)

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // echo "Connected successfully"; // Uncomment this line if you want to confirm the connection
}
?>
