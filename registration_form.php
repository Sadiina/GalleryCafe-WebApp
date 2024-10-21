<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection details
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "gallery_cafe";

    // Create connection to the database
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // Check if the connection to the database is successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get username and password from the form
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    // Insert new user into the user table
    $sql = "INSERT INTO user (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);

    // Check if the registration is successful
    if ($stmt->execute()) {
        // Redirect to login page with a success message
        echo "<script>
                alert('Registration successful! You can now log in.');
                window.location.href = 'login.php';
              </script>";
    } else {
        // Display error message if registration fails
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form - The Gallery Café</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<nav>
    <div class="logo"><a href="index.php"><img src="img/logo.png" width="150px" height="100px"></a></div>
    <ul>
        <li class="back"><a href="index.php">Back</a></li>
    </ul>
</nav>
<br><br><br><br><br>
<!-- Registration Form -->
<div class="container" id="register">
    <div class="registration-box">
        <center><h2>Register</h2></center>
        <form method="POST" action="register.php">
            <div class="input-box">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-box">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
</div>
<br><br><br>
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
<script src="script.js"></script>
</body>
</html>
