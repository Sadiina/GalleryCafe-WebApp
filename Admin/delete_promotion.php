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

// Check if the form is submitted and the promotion ID is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['promotion-id'])) {
    $promotionId = $_POST['promotion-id'];
    
    // Get the image path from the database
    $sql = "SELECT image FROM promotions WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $promotionId);
    $stmt->execute();
    $stmt->bind_result($imagePath);
    $stmt->fetch();
    $stmt->close();
    
    // Delete the image file from the server
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
    
    // Delete the promotion record from the database
    $sql = "DELETE FROM promotions WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $promotionId);
    if ($stmt->execute()) {
        echo "Promotion deleted successfully.";
    } else {
        echo "Error deleting promotion: " . $stmt->error;
    }
    $stmt->close();
}

// Close the database connection
$conn->close();

// Redirect to the admin page after deletion
header("Location: admin.php");
exit();
?>
