<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gallery_cafe";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user-id'];

    // Fetch existing user details from the database using $userId
    $sql = "SELECT username, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $email = $row['email'];
    } else {
        echo "No user found";
        $stmt->close();
        $conn->close();
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="dashboard-container">
        <main class="main-content">
            <header class="main-header">
                <h1>Edit User</h1>
            </header>

            <section id="users" class="dashboard-section">
                <h2>Edit User</h2>
                <!-- Form to edit user details -->
                <form id="users-form" action="update_user.php" method="post">
                    <!-- Hidden input to store the user ID -->
                    <input type="hidden" name="user-id" value="<?php echo $userId; ?>">
                    
                    <!-- Input field for the user name, pre-filled with the existing name -->
                    <label for="user-name">User Name:</label>
                    <input type="text" id="user-name" name="user-name" value="<?php echo $username; ?>" required>
                    
                    <!-- Input field for the user email, pre-filled with the existing email -->
                    <label for="user-email">Email:</label>
                    <input type="email" id="user-email" name="user-email" value="<?php echo $email; ?>" required>
                    
                    <!-- Input field for the user password, can be left empty if not changing the password -->
                    <label for="user-password">Password:</label>
                    <input type="password" id="user-password" name="user-password">
                    
                    <!-- Submit button to update the user -->
                    <button type="submit">Update User</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
