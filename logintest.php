<?php
// Database connection
define('DB_SERVER', 'database-finals.c1wqummm2qfr.ap-southeast-1.rds.amazonaws.com');
define('DB_USERNAME', 'admin');
define('DB_PASSWORD', 'CloudCompFinals!');
define('DB_DATABASE', 'FINALSDB');
$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE, 3306);

session_start();

// Function to get client's IP address
function getClientIp() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// Get client IP and location data
$ip = getClientIp();
$locationInfo = file_get_contents("http://ipinfo.io/{$ip}/geo");
$locationData = json_decode($locationInfo);

// Get form data (username) and client info, check if they exist
$username = isset($_POST['email']) ? mysqli_real_escape_string($db, $_POST['email']) : ''; // username from email field
$os = isset($_POST['os']) ? mysqli_real_escape_string($db, $_POST['os']) : '';
$location = isset($locationData->city) ? $locationData->city : 'Unknown'; // capture city from IP
$time = date('Y-m-d H:i:s'); // current time

// Save login info to the database
$stmt = $db->prepare("INSERT INTO LOGINS (USERNAME, IP, LOCATION, TIME, OS) VALUES (?,?,?,?,?)");
$stmt->bind_param("sssss", $username, $ip, $location, $time, $os);

if ($stmt->execute()) {
    echo "Login info saved successfully!";
} else {
    echo "Error saving login info.";
}

$stmt->close();
$db->close();
?>
