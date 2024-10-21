<?php
session_start();

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
    // Get delivery and payment information from the form
    $deliveryName = $_POST['delivery-name'];
    $deliveryAddress = $_POST['delivery-address'];
    $deliveryPhone = $_POST['delivery-phone'];
    $deliveryEmail = $_POST['delivery-email'];
    $paymentMethod = $_POST['payment-method'];
    $selectedItems = $_SESSION['selectedItems'];

    // Insert delivery information into the orders table
    $sql = "INSERT INTO orders (delivery_name, delivery_address, delivery_phone, delivery_email, payment_method) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $deliveryName, $deliveryAddress, $deliveryPhone, $deliveryEmail, $paymentMethod);

    // Check if the order insertion is successful
    if ($stmt->execute()) {
        // Get the inserted order ID
        $orderId = $stmt->insert_id;

        // Insert selected items into the order_items table
        foreach ($selectedItems as $itemId) {
            $sql = "INSERT INTO order_items (order_id, item_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $orderId, $itemId);
            $stmt->execute();
        }

        // Clear session data
        unset($_SESSION['selectedItems']);

        // Redirect to thank you page
        echo "<script>
                alert('Order placed successfully!');
                window.location.href = 'thanks.php';
              </script>";
        exit();
    } else {
        // Display error message if order insertion fails
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
