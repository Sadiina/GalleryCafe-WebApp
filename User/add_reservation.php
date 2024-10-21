<?php
// Start the session to manage user authentication and data
session_start();

// Check if the form was submitted and the user is logged in
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    // Database connection details
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "gallery_cafe";

    // Create a new database connection
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve user input from the POST request
    $user_id = $_SESSION['user_id']; // User ID from the session
    $date = $_POST['date']; // Reservation date
    $time = $_POST['time']; // Reservation time
    $guests = $_POST['guests']; // Number of guests
    $requests = $_POST['requests']; // Special requests

    // Prepare an SQL statement to insert the reservation details into the database
    $sql = "INSERT INTO reservations (date, time, guests, requests, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", $date, $time, $guests, $requests, $user_id);

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

    // Close the statement and the database connection
    $stmt->close();
    $conn->close();
}
?>
