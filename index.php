<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gallery_cafe";

// Create connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection to the database is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Gallery Café</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="logo"><a href="index.php"><img src="img/logo.png" width="150px" height="100px"></a></div>
        <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#promotions">Promotions</a></li>
            <li><a href="#Ourspecials">Our Specials</a></li>
            <li><a href="#reservations">Reservations</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="#special-events">Special Events</a></li>
            <li><a href="#parking">Car Parking</a></li>
        </ul>
        <div class="nav-buttons">
            <a href="login.php" class="login-btn">Login</a>
            <a href="login.php" class="order-now-btn">Order Now</a>
        </div>
    </nav>
    <!-- Smooth scroll script -->
    <script>
        document.querySelectorAll('nav a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
    <br><br><br><br><br>
    
    <!-- Home Section -->
    <section id="home">
        <div class="home-content">
            <h1>Welcome to The Gallery Café</h1>
            <img src="img/logo.png" >
            <p>Experience the best dining in Colombo with a variety of delicious cuisines from all around the world.</p>
            <p class="description">At The Gallery Café, we pride ourselves on offering a diverse menu that caters to all taste preferences. Whether you're craving traditional Sri Lankan flavors, Chinese delicacies, or Italian favorites, our carefully crafted dishes are sure to satisfy your palate. Join us for an unforgettable dining experience, complemented by a warm and inviting atmosphere.</p>
        </div>
    </section>
    
    <!-- Promotions Section -->
    <section id="promotions">
        <h2>Our Promotions</h2>
        <div class="promotions-grid">
            <?php
            // Fetch promotions from the database
            $sql = "SELECT image FROM promotions";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='promotion-item'>
                            <img src='Admin/{$row['image']}' alt='Promotion'>
                          </div>";
                }
            } else {
                echo "<p>No promotions available</p>";
            }
            ?>
        </div>
        <p class="ta">T&C Applied is for all Promotions Above</p>
    </section>

    <!-- Menu Section -->
    <section id="Ourspecials">
        <h2>Our Specials</h2>
        <div class="menu-grid">
            <div class="menu-item">
                <img src="img/CK.png" alt="Menu Item 1">
                <h3>Sri Lankan Cuisine</h3>
                <p>Cheese Kottu is a mouthwatering Sri Lankan street food that blends chopped roti, vegetables, spices, and succulent pieces of chicken or beef, all topped with a generous layer of melted cheese for an indulgent, savory delight.</p>
            </div>
            <div class="menu-item">
                <img src="img/CFR.png" alt="Menu Item 2">
                <h3>Chinese Cuisine</h3>
                <p>Chinese Fried Rice is a flavorful dish featuring stir-fried rice with a medley of vegetables, tender meat or seafood, and aromatic soy sauce, creating a perfect balance of taste and texture.</p>
            </div>
            <div class="menu-item">
                <img src="img/CF.png" alt="Menu Item 3">
                <h3>Italian Cuisine</h3>
                <p>Italian pasta is a versatile and beloved dish, featuring perfectly cooked noodles combined with fresh ingredients like tomatoes, garlic, olive oil, and herbs, creating a delightful and flavorful culinary experience.</p>
            </div>
        </div>
    </section>

    <!-- Reservations Section -->
    <section id="reservations">
        <h2>Table Reservations</h2>
        <p>Table Reservations can be only done if you are logged in.</p>
        <a href="login.php" class="login-btn">Login</a>
    </section>

    <!-- About Section -->
    <section id="about">
        <h2>About Us</h2>
        <p>Established in 1920, The Gallery Café began as a quaint tea house in the heart of Colombo, Sri Lanka. Originally founded by the renowned artist and philanthropist, Sir Edmund Galeria, the café quickly became a cultural hub for artists, writers, and intellectuals. Its walls were adorned with Sir Edmund’s personal art collection, making it both a café and an art gallery.</p>
        <p>Over the decades, The Gallery Café has evolved, embracing the modern culinary arts while preserving its rich heritage. The café has hosted numerous famous personalities and cultural events, from poetry readings and art exhibitions to jazz nights and gourmet dinners. Today, The Gallery Café continues to offer a unique blend of artistic ambiance and culinary excellence, serving exquisite dishes inspired by global and local flavors, all while maintaining the charm and elegance of its storied past.</p>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <h2>Contact Us</h2>
        <p>Have any questions? Feel free to reach out to us at +947153611745 or email us at info@thegallerycafe.com.</p>
    </section>

    <!-- Special Events Section -->
    <section id="special-events">
        <h2>Special Events</h2>
        <div class="events-list">
            <?php
            // Fetch special events from the database
            $sql = "SELECT * FROM special_events";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='event-item'>
                            <h3>{$row['event_name']}</h3>
                            <p>{$row['event_description']}</p>
                            <p>Date: {$row['event_date']}</p>
                          </div>";
                }
            } else {
                echo "<p>No special events available</p>";
            }
            ?>
        </div>
    </section>

    <!-- Car Parking Section -->
    <style>
        .parking-item h3 {
            color: white;
        }
    </style>
    <section id="parking">
        <h2>Car Parking Availability</h2>
        <div class="parking-info">
            <?php
            // Fetch car parking information from the database
            $sql = "SELECT * FROM parking_info";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='parking-item'>
                            <h3>{$row['parking_status']}</h3>
                            <p>{$row['parking_description']} Available</p>
                          </div>";
                }
            } else {
                echo "<p>No car parking information available</p>";
            }
            ?>
        </div>
    </section>
    
    <!-- Footer Section -->
    <footer class="footer">
        <div class="container03">
            <div class="row">
                <div class="footer-col">
                    <h4>The Gallery</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="index.php">Dining</a></li>
                        <li><a href="index.php">About Us</a></li>
                        <li><a href="index.php">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Get Help</h4>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Reviews</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Order Using</h4>
                    <ul>
                        <li><a href="https://www.uber.com/">Uber.lk</a></li>
                        <li><a href="https://pickme.lk/">Pickme.lk</a></li>
                        <li><a href="https://www.doordash.com/">doordash.com</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Follow Us</h4>
                    <div class="social-media">
                        <a href="https://www.facebook.com"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.twitter.com"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- External JavaScript file -->
    <script src="script.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
