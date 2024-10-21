<?php
session_start();

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
    $password = $_POST['password'];

    // Check for admin credentials
    if ($username == 'admin' && $password == '123') {
        $_SESSION['username'] = 'admin';
        $_SESSION['user_id'] = 0; // Example admin ID, you might handle this differently
        header("Location: Admin/admin.php");
        exit();
    }

    // Check for staff credentials
    if ($username == 'staff' && $password == '123') {
        $_SESSION['username'] = 'staff';
        $_SESSION['user_id'] = 0; // Example staff ID, you might handle this differently
        header("Location: Staff/staff.php");
        exit();
    }

    // Check for user credentials
    $sql = "SELECT id, username, password FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verify user credentials
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id']; // Set user ID in session
            header("Location: User/index_user.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with that username.";
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
    <title>Login UI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!-- Navigation Bar -->
<nav>
    <div class="logo"><a href="index.php"><img src="img/logo.png" width="150px" height="100px"></a></div>
    <ul>
        <li class="back"><a href="index.php">Back</a></li>
    </ul>
</nav>
<br><br><br><br><br>
<!-- Login Form -->
<div class="container" id="login">
    <div class="login-box">
        <center><h2>Login</h2></center>
        <form method="POST" action="login.php">
            <div class="input-box">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-box">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <button type="submit">Login</button>
            <h4><p><a href="registration_form.php">Sign Up</a></p></h4>
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
