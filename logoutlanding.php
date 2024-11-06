<?php
// Start the session
session_start();

// Destroy all session data to log the user out
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Link to your stylesheet -->
    <style>
        /* Styling for the logout page */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .logout-container {
            text-align: center;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .logout-container h1 {
            font-size: 2.5rem;
            color: #8B0000;
            margin-bottom: 20px;
        }

        .logout-container p {
            font-size: 1.25rem;
            margin-bottom: 20px;
        }

        .cta-button {
            padding: 1rem 2rem;
            background-color: #700F1F;
            color: #fff;
            text-decoration: none;
            font-size: 1.2rem;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .cta-button:hover {
            background-color: #FF4500;
        }
    </style>
</head>
<body>

<div class="logout-container">
    <h1>You Have Logged Out</h1>
    <p>Thank you for visiting. We hope to see you again soon!</p>
    <a href="login.php" class="cta-button">Return to Homepage</a>
</div>

</body>
</html>
