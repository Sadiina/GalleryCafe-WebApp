<?php
// Start the session
session_start();

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

// Get selected items from session
$selectedItems = isset($_SESSION['selectedItems']) ? $_SESSION['selectedItems'] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - The Gallery Café</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="logo"><a href="index.php"><img src="img/logo.png" width="150px" height="100px"></a></div>
        <ul>
            <li><a href="index.php#home">Home</a></li>
            <li><a href="index.php#menu">Menu</a></li>
            <li><a href="index.php#promotions">Promotions</a></li>
            <li><a href="index.php#reservations">Reservations</a></li>
            <li><a href="index.php#about">About Us</a></li>
            <li><a href="index.php#contact">Contact</a></li>
        </ul>
        <div class="nav-buttons">
            <a href="order.php" class="order-now-btn">Order Now</a>
        </div>
    </nav>
    <br><br><br><br><br>
    <!-- Checkout Section -->
    <section id="checkout">
        <h2>Checkout</h2>
        <form id="checkout-form" action="process_checkout.php" method="post">
            <h3>Order Summary</h3>
            <div class="order-summary">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Initialize total price
                        $total = 0;

                        // Fetch and display selected items from the database
                        foreach ($selectedItems as $itemId) {
                            $sql = "SELECT name, price FROM menu_items WHERE id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $itemId);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$row['name']}</td>
                                            <td>LKR {$row['price']}</td>
                                          </tr>";
                                    $total += $row['price'];
                                }
                            }

                            $stmt->close();
                        }

                        // Close the database connection
                        $conn->close();
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="1">Total</td>
                            <td><?php echo "LKR $total"; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <h3>Delivery Information</h3>
            <!-- Delivery Information Form -->
            <label for="delivery-name">Name:</label>
            <input type="text" id="delivery-name" name="delivery-name" required>
            <label for="delivery-address">Address:</label>
            <textarea id="delivery-address" name="delivery-address" required></textarea>
            <label for="delivery-phone">Phone:</label>
            <input type="tel" id="delivery-phone" name="delivery-phone" required>
            <label for="delivery-email">Email:</label>
            <input type="email" id="delivery-email" name="delivery-email" required>
            <h3>Payment Information</h3>
            <!-- Payment Information Form -->
            <label for="payment-method">Payment Method:</label>
            <select id="payment-method" name="payment-method" required>
                <option value="credit-card">Credit/Debit Card</option>
                <option value="paypal">PayPal</option>
                <option value="cash-on-delivery">Cash on Delivery</option>
            </select>
            <div id="credit-card-info" style="display: none;">
                <label for="card-number">Card Number:</label>
                <input type="text" id="card-number" name="card-number">
                <label for="card-expiry">Expiry Date:</label>
                <input type="text" id="card-expiry" name="card-expiry">
                <label for="card-cvc">CVC:</label>
                <input type="text" id="card-cvc" name="card-cvc">
            </div>
            <button type="submit">Place Order</button>
        </form>
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

    <script>
        // Show or hide credit card info based on payment method selected
        document.getElementById('payment-method').addEventListener('change', function () {
            if (this.value === 'credit-card') {
                document.getElementById('credit-card-info').style.display = 'block';
            } else {
                document.getElementById('credit-card-info').style.display = 'none';
            }
        });
    </script>
</body>
</html>
