<?php
// Start the session to manage user authentication and data
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gallery_cafe";

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Reservation Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date'], $_POST['time'], $_POST['guests'])) {
    // Retrieve user input from the POST request
    $date = $_POST['date']; // Reservation date
    $time = $_POST['time']; // Reservation time
    $guests = $_POST['guests']; // Number of guests
    $requests = $_POST['requests']; // Special requests

    // Prepare an SQL statement to insert the reservation details into the database
    $sql = "INSERT INTO reservations (date, time, guests, requests) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $date, $time, $guests, $requests);

    // Execute the SQL statement and check if the insertion was successful
    if ($stmt->execute()) {
        // If successful, display a success message and redirect to the user index page
        echo "<script>
                alert('Reservation successful!');
                window.location.href = 'index_user.php';
              </script>";
    } else {
        // If there was an error, display the error message
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Gallery Café</title>
    <link rel="stylesheet" href="user_style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Styles for the logout button */
        .logout-btn {
            background: none;
            border: none;
            color: inherit;
            font: inherit;
            cursor: pointer;
            padding: 0;
        }

        /* Styles for the profile section */
        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile img {
            border-radius: 50%;
            width: 30px;
            height: 30px;
        }

        .profile .fas {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="logo"><a href="index_user.php"><img src="../img/logo.png" width="150px" height="100px"></a></div>
        <ul>
            <li><a href="index_user.php#home">Home</a></li>
            <li><a href="index_user.php##Ourspecials">Our Specials</a></li>
            <li><a href="index_user.php#promotions">Promotions</a></li>
            <li><a href="index_user.php#reservations">Reservations</a></li>
            <li><a href="index_user.php#about">About Us</a></li>
            <li><a href="index_user.php#contact">Contact</a></li>
            <li><a href="#special-events">Special Events</a></li>
            <li><a href="#parking">Car Parking</a></li>
        </ul>
        <div class="nav-buttons">
            <?php if (isset($_SESSION['username'])): ?>
                <div class="profile">
                    <i class="fas fa-user"></i> 
                    <span><?php echo $_SESSION['username']; ?></span>
                    <a href="logout.php" class="logout-btn">Logout</a>
                    <a href="../order.php" class="order-now-btn">Order Now</a>
                </div>
            <?php else: ?>
                <a href="../order.php" class="order-now-btn">Order Now</a>
            <?php endif; ?>
        </div>
    </nav>
    <script>
        // Smooth scrolling for navigation links
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
    <section id="home">
        <div class="home-content">
        <h1>Welcome to The Gallery Café</h1>
            <img src="img/logo.png" >
            
            <p>Experience the best dining in Colombo with a variety of delicious cuisines from all around the world.</p>
            <p class="description">At The Gallery Café, we pride ourselves on offering a diverse menu that caters to all taste preferences. Whether you're craving traditional Sri Lankan flavors, Chinese delicacies, or Italian favorites, our carefully crafted dishes are sure to satisfy your palate. Join us for an unforgettable dining experience, complemented by a warm and inviting atmosphere.</p>
        </div>
        </div>
    </section>
    <!-- Promotions Section -->
    <section id="promotions">
        <h2>Our Promotions</h2>
        <div class="promotions-grid">
            <?php
            // Fetch and display promotions
            $sql = "SELECT image FROM promotions";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='promotion-item'>
                            <img src='../Admin/{$row['image']}' alt='Promotion'>
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
    <section id="#Ourspecials">
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
        <form id="reservation-form" action="add_reservation.php" method="post">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>
            <label for="time">Time:</label>
            <input type="time" id="time" name="time" required>
            <label for="guests">Number of Guests:</label>
            <input type="number" id="guests" name="guests" required>
            <label for="requests">Special Requests:</label>
            <textarea id="requests" name="requests"></textarea>
            <br>
            <button type="submit">Reserve Table</button>
        </form>
    </section>

    <!-- About Section -->
    <section id="about">
        <h2>About Us</h2>
        <p>Established in 1920, The Gallery Café began as a quaint tea house in the heart of Colombo, Sri Lanka. Originally founded by the renowned artist and philanthropist, Sir Edmund Galeria, the café quickly became a cultural hub for artists, writers, and intellectuals. Its walls were adorned with Sir Edmund’s personal art collection, making it both a café and an art gallery.

        Over the decades, The Gallery Café has evolved, embracing the modern culinary arts while preserving its rich heritage. The café has hosted numerous famous personalities and cultural events, from poetry readings and art exhibitions to jazz nights and gourmet dinners. Today, The Gallery Café continues to offer a unique blend of artistic ambiance and culinary excellence, serving exquisite dishes inspired by global and local flavors, all while maintaining the charm and elegance of its storied past.</p>
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
            // Fetch and display special events
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
    <style>.parking-item h3 {
    color: white;}
    </style>
    <section id="parking">
        <h2>Car Parking Availability</h2>
        <div class="parking-info">
            <?php
            // Fetch and display car parking information
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
                        <li><a href="index_user.php">Home</a></li>
                        <li><a href="index_user.php">Dining</a></li>
                        <li><a href="index_user.php">About Us</a></li>
                        <li><a href="index_user.php">Contact Us</a></li>
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

    <script src="script.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
