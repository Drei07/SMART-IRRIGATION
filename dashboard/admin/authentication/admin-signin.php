<?php
require_once 'admin-class.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$user = new ADMIN();
$site_secret_key = $user->siteSecretKey();

// Redirect if the user is already logged in
if ($user->isUserLoggedIn() != "") {
    $user->redirect('');
}

if (isset($_POST['btn-signin'])) {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['status_title'] = "Error!";
        $_SESSION['status'] = "Invalid token";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_timer'] = 40000;
        header("Location: ../../../");
        exit;
    }
    unset($_SESSION['csrf_token']);

    // Validate Google reCAPTCHA
    $response = $_POST['g-token'];
    $remoteip = $_SERVER['REMOTE_ADDR'];
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$site_secret_key&response=$response&remoteip=$remoteip";
    $data = file_get_contents($url);
    $row = json_decode($data, true);

    if ($row['success'] == "true") {
        $email = trim($_POST['email']);
        $upass = trim($_POST['password']);

        if ($user->login($email, $upass)) {
            $_SESSION['status_title'] = "Hey!";
            $_SESSION['status'] = "Welcome to Vape";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_timer'] = 10000;
            header("Location: ../");
            exit;
        } else {
            // Handle invalid login attempt
            $_SESSION['status_title'] = "Error!";
            $_SESSION['status'] = "Invalid email or password!";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_timer'] = 40000;
            header("Location: ../../../");
            exit;
        }
    } else {
        // Handle invalid reCAPTCHA
        $_SESSION['status_title'] = "Error!";
        $_SESSION['status'] = "Invalid captcha, please try again!";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_timer'] = 40000;
        header("Location: ../../../");
        exit;
    }
}
?>
