<?php
include_once '../../../config/settings-configuration.php';
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once '../authentication/admin-class.php';

class Sensor
{
    private $conn;
    private $admin;

    public function __construct()
    {
        $this->admin = new ADMIN();


        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }


    public function sensorThresholds($sensorId, $sensorMode, $sensorPlantName, $sensorDry,  $sensorWatered, $sensorStartDate, $sensorEndDate){

        $stmt = $this->admin->runQuery('SELECT * FROM sensors WHERE sensor_id=:sensor_id');
        $stmt->execute(array(":sensor_id" => $sensorId));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(
            $row["plant_name"] == $sensorPlantName &&
            $row["mode"] == $sensorMode &&
            $row["dry_threshold"] == $sensorDry &&
            $row["watered_threshold"] == $sensorWatered &&
            $row["start_date"] == $sensorStartDate &&
            $row["end_date"] == $sensorEndDate
        )
        {
            $_SESSION['status_title'] = 'Oopss!';
            $_SESSION['status'] = 'No changes have been made to your thresholds.';
            $_SESSION['status_code'] = 'info';
            $_SESSION['status_timer'] = 40000;

            header('Location: ../thresholds');
            exit;
        }

        $stmt = $this->admin->runQuery('UPDATE sensors SET plant_name=:plant_name, mode=:mode, dry_threshold=:dry_threshold, watered_threshold=:watered_threshold, start_date=:start_date, end_date=:end_date WHERE sensor_id=:sensor_id');
        $exec = $stmt->execute(array(

            ":sensor_id"            => $sensorId,
            ":plant_name"           => $sensorPlantName,
            ":mode"                 => $sensorMode,
            ":dry_threshold"        => $sensorDry,
            ":watered_threshold"    => $sensorWatered,
            ":start_date"           => $sensorStartDate,
            ":end_date"            => $sensorEndDate,
        ));
        
        if ($exec) {
            if($sensorId == 1){
                $_SESSION['status_title'] = "Success!";
                $_SESSION['status'] = "Sensor 1 Thresholds successfully updated";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_timer'] = 40000;
            }
            else if ($sensorId == 2){
                $_SESSION['status_title'] = "Success!";
                $_SESSION['status'] = "Sensor 2 Thresholds successfully updated";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_timer'] = 40000;
            }
        }

        header('Location: ../thresholds');
        exit;
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
}

if(isset($_POST['btn-update-thresholds'])){
    $sensorId = trim($_POST['sensorId']);
    $sensorMode = trim($_POST['mode']);
    $sensorPlantName = trim($_POST['plant_name']);
    $sensorDry = trim($_POST['dry_threshold']);
    $sensorWatered = trim($_POST['watered_threshold']);
    $sensorStartDate = trim($_POST['start_date']);
    $sensorEndDate = trim($_POST['end_date']);



    $sensorData = new Sensor();
    $sensorData->sensorThresholds($sensorId, $sensorMode, $sensorPlantName, $sensorDry,  $sensorWatered, $sensorStartDate, $sensorEndDate);

}
?>