<?php
// Database connection
define('DB_SERVER', 'database-finals.c1wqummm2qfr.ap-southeast-1.rds.amazonaws.com');
define('DB_USERNAME', 'admin');
define('DB_PASSWORD', 'CloudCompFinals!');
define('DB_DATABASE', 'FINALSDB');
$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE, 3306);

session_start();

// Check if user is logged in, otherwise redirect to login page
if(!isset($_SESSION['login_user'])){
    header("location: login.php");
    exit;
}

// Get the current date and time
date_default_timezone_set('UTC'); // Set timezone (you can change it to your local timezone if needed)
$current_time = date('Y-m-d H:i:s'); // Format: Year-Month-Day Hour

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_SESSION['login_user'];
    $name = mysqli_real_escape_string($db, $_POST['NAME']);
    $price = mysqli_real_escape_string($db, $_POST['PRICE']);

    $stmt = $db->prepare("INSERT INTO ITEMS (USERNAME, NAME, PRICE, TIME) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $user_name, $name, $price, $current_time);
    if ($stmt->execute()) {
        $_SESSION['login_user'] = $user_name;

        // Redirect to login page
        header("location: landing.php");
        exit();
    } else {
        $error = "Error during Buying";
    }
    $stmt->close(); 

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - Photography & Videography Equipment</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Header Section -->
    <header>
        <div class="logo">
            <h1>Syntax</h1>
        </div>
        <nav>
            <ul>
                <li><h2><?php echo $_SESSION['login_user']; ?></h2></li>
                <li><a href="landing.php">Home</a></li>
                <li><a href="landing.php#shop">Shop</a></li>
                <li><a href="landing.php#about">About Us</a></li>
                <li><a href="logoutlanding.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Product Details Section -->
    <section class="product-details">
        <h2 name="NAME" id="NAME">Photography & Videography Equipment</h2>
        <img src="assets/cam.png" alt="Photography & Videography Equipment">
        <p>Description of the product: Capture life's moments in stunning detail with our premium photography and videography equipment, designed for both enthusiasts and professionals alike.</p>
        <p>Price: $<span nam="PRICE" id="PRICE">400</span></p>
        <form action="product2.php" method="POST">
        <input type="hidden" name="NAME" value="Photography & Videography Equipment">
        <input type="hidden" name="PRICE" value="400">
        <button type="submit" class="cta-button">Buy Now</button>
        </form>
        <div>
                    <?php if (!empty($error)) echo '<p style="color: red;">' . $error . '</p>'; ?>
                </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>© 2024 Syntax. All rights reserved.</p>
        <ul>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Service</a></li>
            <li><a href="#">Contact Us</a></li>
        </ul>
    </footer>

</body>
</html>
