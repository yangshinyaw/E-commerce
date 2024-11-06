<?php
// Start the session
session_start();

// Check if user is logged in, otherwise redirect to login page
if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    exit;
}

// Calculate age from birthdate
$birthDate = new DateTime($_SESSION['birthdate']);
$today = new DateTime(date("Y-m-d"));
$age = $today->diff($birthDate)->y;

// Retrieve the total from the POST request
$total = isset($_POST['total']) ? $_POST['total'] : 0.00;

// Retrieve user's name, gender, phone number, and address (For simplicity, the address is hardcoded here)
$userName = $_SESSION['login_user'];
$gender = $_SESSION['gender'];
$phone = isset($_SESSION['phone']) ? $_SESSION['phone'] : 'Not provided';  // Check if phone is set
$address = "1234 Syntax Street, Code City";

// Retrieve cart items from session
$cartItems = $_SESSION['cart'];

// Simulate storing the order (In a real-world scenario, you would save this in a database)
$orderId = rand(1000, 9999); // Simulate an order ID

// Clear the cart
$_SESSION['cart'] = array();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }
        .order-details {
            margin-top: 20px;
            text-align: left;
        }
        .order-details h3 {
            margin-bottom: 10px;
        }
        .order-details p, .order-details li {
            margin: 5px 0;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #700F1F;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-button:hover {
            background-color: #FF4500;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Thank you for your order!</h1>
    <p>Your order has been successfully placed and will be processed shortly.</p>

    <div class="order-details">
        <h3>Order Details (Order ID: <?php echo $orderId; ?>)</h3>
        <p><strong>Name:</strong> <?php echo $userName; ?></p>
        <p><strong>Gender:</strong> <?php echo $gender; ?></p>
        <p><strong>Age:</strong> <?php echo $age; ?></p>
        <p><strong>Phone Number:</strong> <?php echo $phone; ?></p>  <!-- Display phone number -->
        <p><strong>Address:</strong> <?php echo $address; ?></p>
        
        <h4>Products Purchased:</h4>
        <ul>
            <?php foreach ($cartItems as $item): ?>
                <li><?php echo $item['name']; ?> - $<?php echo number_format($item['price'], 2); ?></li>
            <?php endforeach; ?>
        </ul>
        
        <p><strong>Total Amount:</strong> $<?php echo $total; ?></p>
        <p><strong>Payment Mode:</strong> Cash</p>
    </div>

    <a href="landing.php" class="back-button">Back to Shop</a>
</div>

</body>
</html>
