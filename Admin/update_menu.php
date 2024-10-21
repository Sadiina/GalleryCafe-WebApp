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
    $name = $_POST['menu-item-name'];
    $description = $_POST['menu-item-description'];
    $price = $_POST['menu-item-price'];
    $category = $_POST['menu-item-category'];

    // Handle file upload
    $image = $_FILES['menu-item-image']['name'];
    $target_dir = "uploads/menu/";
    $target_file = $target_dir . basename($image);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file already exists and rename it
    if (file_exists($target_file)) {
        $base_name = pathinfo($target_file, PATHINFO_FILENAME);
        $counter = 1;
        while (file_exists($target_dir . $base_name . "_" . $counter . "." . $imageFileType)) {
            $counter++;
        }
        $target_file = $target_dir . $base_name . "_" . $counter . "." . $imageFileType;
    }

    // Check file size
    if ($_FILES["menu-item-image"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["menu-item-image"]["tmp_name"], $target_file)) {
            $image = basename($target_file); // Use new image name
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Update menu item details
    if (!empty($image)) {
        $sql = "UPDATE menu_items SET name = ?, description = ?, price = ?, category = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissi", $name, $description, $price, $category, $image, $id);
    } else {
        $sql = "UPDATE menu_items SET name = ?, description = ?, price = ?, category = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisi", $name, $description, $price, $category, $id);
    }

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        echo "<script>
                alert('Menu item updated successfully!');
                window.location.href = 'admin.php';
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
