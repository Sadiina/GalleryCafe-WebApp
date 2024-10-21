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

// Check if the form is submitted and the menu ID is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['menu-id'])) {
    $menuId = $_POST['menu-id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Delete related entries in the order_items table
        $deleteOrderItemsSql = "DELETE FROM order_items WHERE item_id = ?";
        $stmt = $conn->prepare($deleteOrderItemsSql);
        $stmt->bind_param("i", $menuId);
        $stmt->execute();
        $stmt->close();

        // Delete the menu item from the menu_items table
        $deleteMenuSql = "DELETE FROM menu_items WHERE id = ?";
        $stmt = $conn->prepare($deleteMenuSql);
        $stmt->bind_param("i", $menuId);
        $stmt->execute();
        $stmt->close();

        // Commit the transaction
        $conn->commit();

        echo "Menu item deleted successfully.";
    } catch (mysqli_sql_exception $exception) {
        // Rollback the transaction in case of error
        $conn->rollback();

        echo "Error deleting menu item: " . $exception->getMessage();
    }
}

// Close the database connection
$conn->close();

// Redirect to the admin page after deletion
header("Location: admin.php");
exit();
?>
