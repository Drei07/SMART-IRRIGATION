<?php
require_once 'authentication/admin-class.php';
include_once '../../config/settings-configuration.php';
include_once '../../config/header.php';
include_once '../../config/footer.php';
require_once 'sidebar.php';

$currentPage = basename($_SERVER['PHP_SELF'], ".php"); // Gets the current page name without the extension
$sidebar = new SideBar($config, $currentPage);

$config = new SystemConfig();
$header_dashboard = new HeaderDashboard($config);
$footer_dashboard = new FooterDashboard();
$user = new ADMIN();

if(!$user->isUserLoggedIn())
{
 $user->redirect('../../');
}

// retrieve user data
$stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid");
$stmt->execute(array(":uid"=>$_SESSION['adminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// retrieve profile user and full name
$user_id                = $user_data['id'];
$user_profile           = $user_data['profile'];
$user_fname             = $user_data['first_name'];
$user_mname             = $user_data['middle_name'];
$user_lname             = $user_data['last_name'];
$user_fullname          = $user_data['last_name'] . ", " . $user_data['first_name'];
$user_sex               = $user_data['sex'];
$user_birth_date        = $user_data['date_of_birth'];
$user_age               = $user_data['age'];
$user_civil_status      = $user_data['civil_status'];
$user_phone_number      = $user_data['phone_number'];
$user_email             = $user_data['email'];
$user_last_update       = $user_data['updated_at'];

// Retrieve sensor data
$stmt = $user->runQuery("SELECT * FROM sensors");
$stmt->execute();
$sensorData = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows

// Loop through the result set to assign data for sensors
foreach ($sensorData as $sensor) {
    if($sensor['sensor_id'] == 1) {
        $sensor1Mode = $sensor['mode'];
        $sensor1PlantName = $sensor['plant_name'];
        $sensor1Dry = $sensor['dry_threshold'];
        $sensor1Watered = $sensor['watered_threshold'];
        $sensor1StartDate = $sensor['start_date'];
        $sensor1EndDate = $sensor['end_date'];
    } else if ($sensor['sensor_id'] == 2) {
        $sensor2Mode = $sensor['mode'];
        $sensor2PlantName = $sensor['plant_name'];
        $sensor2Dry = $sensor['dry_threshold'];
        $sensor2Watered = $sensor['watered_threshold'];
        $sensor2StartDate = $sensor['start_date'];
        $sensor2EndDate = $sensor['end_date'];
    }
}
