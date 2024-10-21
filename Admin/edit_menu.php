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
    $id = $_POST['menu-id'];

    // SQL query to fetch the menu item details from the menu_items table
    $sql = "SELECT name, description, price, image, category FROM menu_items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a record is found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "No record found";
    }

    // Close the prepared statement
    $stmt->close();
    
    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu Item - The Gallery Café</title>
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h2>Edit Menu Item</h2>
        <!-- Form to update the menu item -->
        <form action="update_menu.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="menu-id" value="<?php echo $id; ?>">
            <label for="menu-item-name">Item Name:</label>
            <input type="text" id="menu-item-name" name="menu-item-name" value="<?php echo $row['name']; ?>" required>
            <label for="menu-item-description">Description:</label>
            <textarea id="menu-item-description" name="menu-item-description" required><?php echo $row['description']; ?></textarea>
            <label for="menu-item-price">Price:</label>
            <input type="number" step="0.01" id="menu-item-price" name="menu-item-price" value="<?php echo $row['price']; ?>" required>
            <label for="menu-item-category">Category:</label>
            <select id="menu-item-category" name="menu-item-category" required>
                <option value="Sri Lankan" <?php if ($row['category'] == 'Sri Lankan') echo 'selected'; ?>>Sri Lankan</option>
                <option value="Chinese" <?php if ($row['category'] == 'Chinese') echo 'selected'; ?>>Chinese</option>
                <option value="Italian" <?php if ($row['category'] == 'Italian') echo 'selected'; ?>>Italian</option>
                <!-- Add more categories as needed -->
            </select>
            <label for="menu-item-image">Current Image:</label>
            <img src="uploads/menu/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" width="100">
            <label for="menu-item-image">Upload New Image:</label>
            <input type="file" id="menu-item-image" name="menu-item-image" accept="image/*">
            <button type="submit">Update Menu Item</button>
        </form>
        <br>
        <!-- Button to go back to the admin page -->
        <button onclick="window.location.href='admin.php'">Back</button>
    </div>
</body>
</html>
