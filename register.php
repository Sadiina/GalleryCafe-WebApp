<?php
session_start();

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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    // Insert new user into the user table
    $sql = "INSERT INTO user (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);

    // Check if the registration is successful
    if ($stmt->execute()) {
        // Set the session username
        $_SESSION['username'] = $username;
        // Redirect to user index page with a success message
        echo "<script>
                alert('Registration successful! You can now log in.');
                window.location.href = 'User/index_user.php';
              </script>";
    } else {
        // Display error message if registration fails
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
