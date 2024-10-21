<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user-id'];

    // Delete the user from the database
    // Assuming you have a PDO connection instance in $pdo
    try {
        // Prepare the SQL statement to delete the user
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        // Execute the statement with the user ID
        $stmt->execute([$userId]);
        echo "User deleted successfully.";
    } catch (PDOException $e) {
        // Display error message if something goes wrong
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="dashboard-container">
        <main class="main-content">
            <header class="main-header">
                <h1>User Deleted</h1>
            </header>
            <section id="users" class="dashboard-section">
                <p>The user has been deleted successfully.</p>
                <a href="admin_dashboard.html">Back to Dashboard</a>
            </section>
        </main>
    </div>
</body>
</html>
