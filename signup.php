<?php
define('DB_SERVER', 'database-finals.c1wqummm2qfr.ap-southeast-1.rds.amazonaws.com');
define('DB_USERNAME', 'admin');
define('DB_PASSWORD', 'CloudCompFinals!');
define('DB_DATABASE', 'FINALSDB');
$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE, 3306);

session_start();
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = mysqli_real_escape_string($db, $_POST['USERNAME']);
    $password = mysqli_real_escape_string($db, $_POST['PASSWORD']);
    $confirm_password = mysqli_real_escape_string($db, $_POST['CONFIRM_PASSWORD']);
    $gender = mysqli_real_escape_string($db, $_POST['GENDER']);
    $birthdate = mysqli_real_escape_string($db, $_POST['BIRTHDAY']);
    $address = mysqli_real_escape_string($db, $_POST['ADDRESS']);
    $age = mysqli_real_escape_string($db, $_POST['AGE']);
    $phone = mysqli_real_escape_string($db, $_POST['PHONE']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if the username already exists
        $sql_check = "SELECT * FROM USERS WHERE USERNAME = '$user_name'";
        $result_check = mysqli_query($db, $sql_check);
        if (mysqli_num_rows($result_check) > 0) {
            $error = "Username already exists!";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $stmt = $db->prepare("INSERT INTO USERS (USERNAME, PASSWORD, GENDER, BIRTHDAY, AGE, PHONENUMBER, ADDRESS) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $user_name, $hashed_password, $gender, $birthdate, $age, $phone, $address);
            if ($stmt->execute()) {
                $_SESSION['login_user'] = $user_name;

                // Redirect to login page
                header("location: login.php");
                exit();
            } else {
                $error = "Error during registration!";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container">
    <div class="login-container">
        <div class="form-container">
            <h1>Register</h1>
            <form class="register" action="signup.php" method="POST">
                <input type="text" id="USERNAME" name="USERNAME" placeholder="Username" required>
                <input type="password" id="PASSWORD" name="PASSWORD" placeholder="Password" required>
                <input type="password" id="CONFIRM_PASSWORD" name="CONFIRM_PASSWORD" placeholder="Confirm Password" required>
                <select id="GENDER" name="GENDER" required>
                    <option value="" disabled selected>Gender</option>
                    <option value="MALE">Male</option>
                    <option value="FEMALE">Female</option>
                </select>
                <input type="date" id="BIRTHDAY" name="BIRTHDAY" required>
                <input type="text" id="ADDRESS" name="ADDRESS" placeholder="Address" required>
                <input type="number" id="AGE" name="AGE" placeholder="Age" min="1" required>
                <input type="tel" id="PHONE" name="PHONE" placeholder="Phone Number" pattern="[0-9]{10}" required>
                <button type="submit">Register</button>
                <div>
                    <?php if (!empty($error)) echo '<p style="color: red;">' . $error . '</p>'; ?>
                </div>
                <div>
                    <span>Already have an account? <a href="login.php">Log In</a></span>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
