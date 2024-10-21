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
    $itemName = $_POST['menu-item-name'];
    $itemDescription = $_POST['menu-item-description'];
    $itemPrice = $_POST['menu-item-price'];
    $itemCategory = $_POST['menu-item-category'];
    $target_dir = "uploads/menu/";
    $target_file = $target_dir . basename($_FILES["menu-item-image"]["name"]);
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
            // Insert menu item details into database
            $sql = "INSERT INTO menu_items (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdss", $itemName, $itemDescription, $itemPrice, $itemCategory, $target_file);
            $stmt->execute();
            $stmt->close();
            echo "The file ". htmlspecialchars(basename($_FILES["menu-item-image"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close();
?>
