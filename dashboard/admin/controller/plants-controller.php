<?php
include_once '../../../config/settings-configuration.php';
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once '../authentication/admin-class.php';


class Plants
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

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

    public function addPlant($plantName, $Dry,  $Watered){
        $stmt = $this->admin->runQuery('INSERT INTO plants (plant_name, dry_threshold, watered_threshold) VALUES (:plant_name, :dry_threshold, :watered_threshold)');
        $exec = $stmt->execute(array(

            ":plant_name"           => $plantName,
            ":dry_threshold"        => $Dry,
            ":watered_threshold"    => $Watered,
        ));

        if ($exec) {

            $activity = "New Plant has been added ($plantName)";
            $user_id = $_SESSION['adminSession'];
            $this->admin->logs($activity, $user_id);

            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'New Plant has been added';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../plants');
        exit;
        
    }

    public function deletePlants($plant_id){
        $stmt = $this->runQuery('UPDATE plants SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":status"  => "disabled",
            ":id"        => $plant_id
        ));

        if ($exec) {

            $activity = "Plants () has been deleted";
            $user_id = $_SESSION['adminSession'];
            $this->admin->logs($activity, $user_id);

            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Plant has been deleted';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../plants');
        exit;
    }

    public function updatePlant($plant_id, $plantName, $Dry,  $Watered){
        $stmt = $this->admin->runQuery('SELECT * FROM plants WHERE id=:id');
        $stmt->execute(array(":id" => $plant_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(
            $row["plant_name"] == $plantName &&
            $row["dry_threshold"] == $Dry &&
            $row["watered_threshold"] == $Watered
        )
        {
            $_SESSION['status_title'] = 'Oopss!';
            $_SESSION['status'] = 'No changes have been made to your plant data.';
            $_SESSION['status_code'] = 'info';
            $_SESSION['status_timer'] = 40000;

            header('Location: ../plants');
            exit;
        }

        $stmt = $this->admin->runQuery('UPDATE plants SET plant_name=:plant_name, dry_threshold=:dry_threshold, watered_threshold=:watered_threshold WHERE id=:id');
        $exec = $stmt->execute(array(

            ":id"                   => $plant_id,
            ":plant_name"           => $plantName,
            ":dry_threshold"        => $Dry,
            ":watered_threshold"    => $Watered,
        ));
        
        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Plant has been updated';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        }
        else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }
        header('Location: ../plants');
        exit;

    }
}


if(isset($_POST['btn-add-plants'])){
    $plantName = trim($_POST['plant_name']);
    $Dry = trim($_POST['dry_threshold']);
    $Watered = trim($_POST['watered_threshold']);


    $addPlants = new Plants();
    $addPlants->addPlant($plantName, $Dry,  $Watered);
}

if(isset($_POST['btn-edit-plants'])){
    $plant_id = trim($_POST['plant_id']);
    $plantName = trim($_POST['plant_name']);
    $Dry = trim($_POST['dry_threshold']);
    $Watered = trim($_POST['watered_threshold']);


    $updatePlants = new Plants();
    $updatePlants->updatePlant($plant_id, $plantName, $Dry,  $Watered);
}


if (isset($_GET['delete_plants'])) {
    $plant_id = $_GET["id"];

    $deletePlants = new Plants();
    $deletePlants->deletePlants($plant_id);
}
?>