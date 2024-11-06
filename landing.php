<?php
// Start the session
session_start();

// Check if user is logged in, otherwise redirect to login page
if(!isset($_SESSION['login_user'])){
    header("location: login.php");
    exit;
}

// Check if the session contains gender and birthdate, otherwise set defaults for testing
$gender = isset($_SESSION['gender']) ? $_SESSION['gender'] : 'Male';  // Default Male
$birthdate = isset($_SESSION['birthdate']) ? $_SESSION['birthdate'] : '2010-02-02';  // Default birthdate

// Initialize cart if not already done
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add product to cart
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productImg = $_POST['product_img'];

    // Add the product to the cart
    $_SESSION['cart'][] = array(
        'id' => $productId,
        'name' => $productName,
        'price' => $productPrice,
        'img' => $productImg
    );
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImageExtracto</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Hero Section Background */
        .hero {
            background-image: url('assets/bg.png'); /* Ensure this path is correct */
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
        }

        .hero-content h2 {
            font-size: 3em;
        }

        .hero-content p {
            font-size: 1.5em;
        }

        .cta-button {
            background-color: #8B0000;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        /* Hide the ad section initially */
        #ad-section {
            display: none;
            text-align: center;
            margin-top: 20px;
        }

        img {
            width: 100%;
            max-width: 560px;
            height: auto;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .team-member {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .team-photo {
            color: #666;
        }

        .about-us-header {
            text-align: center;
            margin-bottom: 20px;
        }

        nav ul li a.logout-button {
            background-color: #700F1F;
        }

        nav ul li a.logout-button:hover {
            background-color: #FF4500;
        }

        /* Cart Section */
        .cart-button {
            background-color: #700F1F;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .cart-view {
            display: none;
            position: fixed;
            top: 100px;
            right: 20px;
            background-color: white;
            border: 1px solid #ddd;
            padding: 20px;
            z-index: 100;
        }

        .cart-view h2 {
            margin-bottom: 10px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
        }

        .cart-item img {
            width: 50px;
        }

        .cart-item p {
            margin: 0;
        }

        .total {
            font-weight: bold;
            margin-top: 20px;
        }

        .buy-now-button {
            background-color: #700F1F;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .buy-now-button:hover {
            background-color: #FF4500;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo">
            <h1>SYNTAX</h1>
        </div>
        <nav>
            <ul>
                <li><h2><?php echo $_SESSION['login_user']; ?></h2></li>
                <li><a href="#home" class="nav-button">Home</a></li>
                <li><a href="#shop" class="nav-button">Shop</a></li>
                <li><a href="#about" class="nav-button">About Us</a></li>
                <li><a href="logoutlanding.php" class="nav-button">Logout</a></li>
                <li><a href="#" class="cart-button" id="cart-btn">Cart (<?php echo count($_SESSION['cart']); ?>)</a></li>
            </ul>
        </nav>
    </header>

    <!-- Cart View Section -->
    <div class="cart-view" id="cart-view">
        <h2>Your Cart</h2>
        <?php if (count($_SESSION['cart']) > 0): ?>
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $item):
                $total += $item['price'];
            ?>
                <div class="cart-item">
                    <img src="<?php echo $item['img']; ?>" alt="<?php echo $item['name']; ?>">
                    <p><?php echo $item['name']; ?></p>
                    <p>$<?php echo number_format($item['price'], 2); ?></p>
                </div>
            <?php endforeach; ?>
            <div class="total">Total: $<?php echo number_format($total, 2); ?></div>

            <!-- Buy Now Button -->
            <form action="buy.php" method="post">
                <input type="hidden" name="total" value="<?php echo number_format($total, 2); ?>">
                <button type="submit" class="buy-now-button">Buy Now</button>
            </form>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h2>Big Discounts on Digital Products!</h2>
            <p>Shop the latest products at unbeatable prices!</p>
            <a href="#shop" class="cta-button">Shop Now</a>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products" id="shop">
        <h2>Featured Products</h2>
        <div class="product-grid">
            <form method="post">
                <div class="product">
                    <img src="assets/phone.png" alt="Digital Tools & Software">
                    <h3>Digital Tools & Software</h3>
                    <input type="hidden" name="product_id" value="1">
                    <input type="hidden" name="product_name" value="Digital Tools & Software">
                    <input type="hidden" name="product_price" value="99.99">
                    <input type="hidden" name="product_img" value="assets/phone.png">
                    <button type="submit" name="add_to_cart" class="buy-now">Add to Cart</button>
                </div>
            </form>
            <form method="post">
                <div class="product">
                    <img src="assets/cam.png" alt="Photography & Videography Equipment">
                    <h3>Photography & Videography Equipment</h3>
                    <input type="hidden" name="product_id" value="2">
                    <input type="hidden" name="product_name" value="Photography & Videography Equipment">
                    <input type="hidden" name="product_price" value="199.99">
                    <input type="hidden" name="product_img" value="assets/cam.png">
                    <button type="submit" name="add_to_cart" class="buy-now">Add to Cart</button>
                </div>
            </form>
            <form method="post">
                <div class="product">
                    <img src="assets/DA.png" alt="Stock Photos & Digital Assets">
                    <h3>Stock Photos & Digital Assets</h3>
                    <input type="hidden" name="product_id" value="3">
                    <input type="hidden" name="product_name" value="Stock Photos & Digital Assets">
                    <input type="hidden" name="product_price" value="49.99">
                    <input type="hidden" name="product_img" value="assets/DA.png">
                    <button type="submit" name="add_to_cart" class="buy-now">Add to Cart</button>
                </div>
            </form>
            <form method="post">
                <div class="product">
                    <img src="assets/AI.png" alt="AI-Generated Content Services">
                    <h3>AI-Generated Content Services</h3>
                    <input type="hidden" name="product_id" value="4">
                    <input type="hidden" name="product_name" value="AI-Generated Content Services">
                    <input type="hidden" name="product_price" value="299.99">
                    <input type="hidden" name="product_img" value="assets/AI.png">
                    <button type="submit" name="add_to_cart" class="buy-now">Add to Cart</button>
                </div>
            </form>
        </div>
    </section>


    <!-- About Us Section -->
    <div class="team-grid">
        <div class="about-us-container" id="about">
            <div class="about-us-header">
                <h1>About Us</h1>
                <p>Meet our amazing team!</p>
            </div>

            <div class="team-grid">
                <!-- Team Member 1 -->
                <div class="team-member">
                    <img src="carlo.jpg" alt="Carlo G. Quilla" class="team-photo" />
                    <h2>Carlo G. Quilla</h2>
                    <p>Project Manager</p>
                    <a href="mailto:carloquilla1@gmail.com">carloquilla1@gmail.com</a>
                </div>

                <!-- Team Member 2 -->
                <div class="team-member">
                    <img src="shin.jpg" alt="Shin Yang Yaw" class="team-photo" />
                    <h2>Shin Yang Yaw</h2>
                    <p>Code Architect</p>
                    <a href="mailto:shinyangyaw@gmail.com">shinyangyaw@gmail.com</a>
                </div>

                <!-- Team Member 3 -->
                <div class="team-member">
                    <img src="gabriel.jpg" alt="Gabriel Dometita" class="team-photo" />
                    <h2>Gabriel Dometita</h2>
                    <p>Security Specialist</p>
                    <a href="mailto:GabeDometita@gmail.com">GabeDometita@gmail.com</a>
                </div>

                <!-- Team Member 4 -->
                <div class="team-member">
                    <img src="sebastian.jpg" alt="Sebastian Visperas" class="team-photo" />
                    <h2>Sebastian Visperas</h2>
                    <p>Web Developer</p>
                    <a href="mailto:bastivisperas@gmail.com">bastivisperas@gmail.com</a>
                </div>

                <!-- Team Member 5 -->
                <div class="team-member">
                    <img src="charles.jpg" alt="Charles Raphael Sanchez" class="team-photo" />
                    <h2>Charles Raphael Sanchez</h2>
                    <p>Documentator</p>
                    <a href="mailto:charlessanchez@gmail.com">charlessanchez@gmail.com</a>
                </div>

                <!-- Team Member 6 -->
                <div class="team-member">
                    <img src="ralph.jpg" alt="Ralph Pamintuan" class="team-photo" />
                    <h2>Ralph Pamintuan</h2>
                    <p>Web Developer</p>
                    <a href="mailto:ralphpamintuan@gmail.com">ralphpamintuan@gmail.com</a>
                </div>
            </div>
        </div>
    </div>

        <!-- Advertisement Section -->
        <section id="ad-section">
            <h2>Advertisement:</h2>
            <div id="video-container">
                <!-- GIF will be dynamically added here -->
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

    <script>
        // Cart toggle
        document.getElementById("cart-btn").addEventListener("click", function() {
            var cartView = document.getElementById("cart-view");
            cartView.style.display = cartView.style.display === "none" ? "block" : "none";
        });

        // Advertisement GIF selection logic
        var gender = "<?php echo $gender; ?>";
        var birthdate = "<?php echo $birthdate; ?>";
        var currentYear = new Date().getFullYear();
        var birthYear = new Date(birthdate).getFullYear();
        var age = currentYear - birthYear;
        var gifSrc = "";

        if (gender.trim().toLowerCase() === "female" && age < 18) {
            gifSrc = "FemaleLower18.gif";
        } else if (gender.trim().toLowerCase() === "male" && age < 18) {
            gifSrc = "MaleLower18.gif";
        } else if (gender.trim().toLowerCase() === "female" && age >= 18) {
            gifSrc = "FemaleHigher18.gif";
        } else if (gender.trim().toLowerCase() === "male" && age >= 18) {
            gifSrc = "MaleHigher18.gif";
        }

        if (gifSrc) {
            var imgElement = document.createElement('img');
            imgElement.src = gifSrc;
            imgElement.onerror = function() {
                console.error("Failed to load GIF: " + gifSrc);
            };
            document.getElementById('video-container').appendChild(imgElement);
            document.getElementById("ad-section").style.display = "block";
        }
    </script>
</body>
</html>
