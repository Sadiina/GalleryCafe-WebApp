<?php
// Function to generate a unique reference number
function generateReferenceNumber() {
    return strtoupper(uniqid('REF'));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - The Gallery Café</title>
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
            <li class="back"><a href="User/index_user.php">Back</a></li>
        </div>
    </nav>
    <br><br><br><br><br>
    <!-- Thank You Section -->
    <section id="thank-you">
        <div class="thank-you-content">
            <h2>Thank You for Your Order!</h2>
            <p>Your order has been received and is being processed.</p>
            <p>Your reference number is: <strong><?php echo generateReferenceNumber(); ?></strong></p>
            <p>We hope to see you again soon.</p>
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
</body>
</html>
