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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = $_POST['reservation_id'];
    
    // SQL query to delete the reservation from the reservations table
    $sql = "DELETE FROM reservations WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservation_id);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        echo "<script>
                alert('Reservation deleted successfully!');
                window.location.href = 'admin.php';
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
