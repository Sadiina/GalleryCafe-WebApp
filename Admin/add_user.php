<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection details
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "the_gallery_cafe";

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
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Insert new user into the staff table
    $sql = "INSERT INTO staff (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);

    // Check if the user insertion is successful
    if ($stmt->execute()) {
        echo "User added successfully.";
    } else {
        // Display error message if user insertion fails
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User - The Gallery Café</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
<!-- Admin Dashboard Container -->
<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Admin Dashboard</h2>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="admin.php"><i class="fas fa-calendar-alt"></i> Reservations</a></li>
                <li><a href="add_user.php"><i class="fas fa-user-plus"></i> Add User</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>
    <!-- Main Content -->
    <main class="main-content">
        <header class="main-header">
            <h1>Add New User</h1>
        </header>
        <!-- Add User Form -->
        <form method="POST" action="add_user.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Add User</button>
        </form>
    </main>
</div>
</body>
</html>
