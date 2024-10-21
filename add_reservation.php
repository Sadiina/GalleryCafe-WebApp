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

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $date = $_POST['date'];
    $time = $_POST['time'];
    $guests = $_POST['guests'];
    $requests = $_POST['requests'];

    // SQL query to insert reservation data into the database
    $sql = "INSERT INTO reservations (date, time, guests, requests) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $date, $time, $guests, $requests);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        // If successful, show an alert and redirect to index.php
        echo "<script>
                alert('Reservation added successfully!');
                window.location.href = 'index.php';
              </script>";
    } else {
        // If there was an error, display the error message
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
