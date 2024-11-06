<?php
define('DB_SERVER', 'database-finals.c1wqummm2qfr.ap-southeast-1.rds.amazonaws.com');
define('DB_USERNAME', 'admin');
define('DB_PASSWORD', 'CloudCompFinals!');
define('DB_DATABASE', 'FINALSDB');
$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE, 3306);

session_start();

// Function to get user IP address
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// Function to get geolocation data
function getGeolocation($ip) {
    $url = "https://ipinfo.io/{$ip}/json";
    $response = file_get_contents($url);
    return json_decode($response, true);
}

// Get user IP and device info
$user_ip = getUserIP();
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$location_data = getGeolocation($user_ip);
$location = $location_data['city'] ?? 'Unknown';

// Get the current date and time
date_default_timezone_set('UTC'); // Set timezone (you can change it to your local timezone if needed)
$current_time = date('Y-m-d H:i:s'); // Format: Year-Month-Day Hour

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = mysqli_real_escape_string($db, $_POST['USERNAME']);
    $password = mysqli_real_escape_string($db, $_POST['PASSWORD']);

    $sql = "SELECT * FROM USERS WHERE USERNAME = '$user_name'";
    $result = mysqli_query($db, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['PASSWORD'])) {
        // Save user demographics to session
        $_SESSION['login_user'] = $user_name;
        $_SESSION['gender'] = $user['GENDER'];
        $_SESSION['birthdate'] = $user['BIRTHDAY'];
        $_SESSION['location'] = $location;
        $_SESSION['phone'] = $user['PHONENUMBER'];
        
        // Log login activity
        $stmt = $db->prepare("INSERT INTO LOGINS (Username, IP, LOCATION, TIME, OS) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $user_name, $user_ip, $location, $current_time, $user_agent);
        $stmt->execute();
        $stmt->close();

        // Redirect to the landing page
        header("location: landing.php");
        exit();
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <section class="container">
        <div class="login-container">
            <div class="form-container">
                <h1>LOGIN</h1>
                <form class="login" action="login.php" method="post">
                    <input name="USERNAME" id="user_name" type="text" placeholder="USERNAME / EMAIL" required>
                    <input name="PASSWORD" id="password" type="password" placeholder="PASSWORD" required>
                    <button type="submit">SUBMIT</button>
                    <div>
                        <?php if (!empty($error)) echo '<p style="color: red;">' . $error . '</p>'; ?>
                    </div>
                    <div>
                        <span>Don't have an account? <a href="signup.php">Sign Up</a></span>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>
</html>
