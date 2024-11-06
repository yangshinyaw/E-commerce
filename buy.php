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

// Retrieve user's name, gender, and address (For simplicity, let's assume the address is hardcoded for now)
$userName = $_SESSION['login_user'];
$gender = $_SESSION['gender'];
$address = "1234 Syntax Street, Code City";

// Retrieve cart items from session
$cartItems = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Now</title>
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
        }
        .summary {
            margin-bottom: 20px;
        }
        .buy-now-button {
            background-color: #700F1F;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .buy-now-button:hover {
            background-color: #FF4500;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Order Summary</h1>
    
    <div class="summary">
        <p><strong>Name:</strong> <?php echo $userName; ?></p>
        <p><strong>Gender:</strong> <?php echo $gender; ?></p>
        <p><strong>Age:</strong> <?php echo $age; ?></p>
        <p><strong>Address:</strong> <?php echo $address; ?></p>
    </div>
    
    <h2>Products:</h2>
    <ul>
        <?php foreach ($cartItems as $item): ?>
            <li><?php echo $item['name']; ?> - $<?php echo number_format($item['price'], 2); ?></li>
        <?php endforeach; ?>
    </ul>
    
    <div class="total">
        <strong>Total: $<?php echo $total; ?></strong>
    </div>

    <form action="process_order.php" method="post">
        <input type="hidden" name="total" value="<?php echo $total; ?>">
        <button type="submit" class="buy-now-button">Confirm Purchase</button>
    </form>
</div>

</body>
</html>
