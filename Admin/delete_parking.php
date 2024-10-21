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

// Check if the form is submitted and the parking ID is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['parking-id'])) {
    $parkingId = $_POST['parking-id'];
    
    // SQL query to delete the parking info from the parking_info table
    $deleteParkingSql = "DELETE FROM parking_info WHERE id = ?";
    $stmt = $conn->prepare($deleteParkingSql);
    $stmt->bind_param("i", $parkingId);
    $stmt->execute();
    $stmt->close();
    
    // Redirect to the admin page after deletion
    header("Location: admin.php");
    exit();
}

// Close the database connection
$conn->close();
?>
