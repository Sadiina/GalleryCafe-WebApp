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

// Check if the form is submitted and the event ID is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event-id'])) {
    $eventId = $_POST['event-id'];
    
    // SQL query to delete the event from the special_events table
    $deleteEventSql = "DELETE FROM special_events WHERE id = ?";
    $stmt = $conn->prepare($deleteEventSql);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $stmt->close();
    
    // Redirect to the admin page after deletion
    header("Location: admin.php");
    exit();
}

// Close the database connection
$conn->close();
?>
