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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user-id'];
    $userName = $_POST['user-name'];
    $userEmail = $_POST['user-email'];
    $userRole = $_POST['user-role'];
    $userPassword = $_POST['user-password'];

    // Update the user details in the database
    try {
        // Check if a new password is provided
        if (!empty($userPassword)) {
            $userPasswordHashed = password_hash($userPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ?, password = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $userName, $userEmail, $userRole, $userPasswordHashed, $userId);
        } else {
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
            $stmt->bind_param("sssi", $userName, $userEmail, $userRole, $userId);
        }
        $stmt->execute();
        $stmt->close();
        echo "<script>
                alert('User updated successfully!');
                window.location.href = 'admin_dashboard.html';
              </script>";
    } catch (mysqli_sql_exception $e) {
        echo "<script>
                alert('Error: " . $e->getMessage() . "');
                window.location.href = 'admin_dashboard.html';
              </script>";
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="dashboard-container">
        <main class="main-content">
            <header class="main-header">
                <h1>User Updated</h1>
            </header>
            <section id="users" class="dashboard-section">
                <p>The user has been updated successfully.</p>
                <a href="admin_dashboard.html">Back to Dashboard</a>
            </section>
        </main>
    </div>
</body>
</html>
