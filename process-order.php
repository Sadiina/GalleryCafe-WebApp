<?php
session_start();

// Check if the form is submitted and the user is logged in
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username'])) {
    $user = $_SESSION['username'];
    $items = isset($_POST['cuisine']) ? $_POST['cuisine'] : [];

    // Check if any items are selected
    if (!empty($items)) {
        // Store selected items in session
        $_SESSION['selectedItems'] = $items;

        // Connect to the database
        $conn = new mysqli("localhost", "root", "", "gallery_cafe");

        // Check if the connection to the database is successful
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert each selected item into the orders table
        foreach ($items as $item_id) {
            $sql = "INSERT INTO orders (username, item_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $user, $item_id);
            $stmt->execute();
            $stmt->close();
        }

        // Close the database connection
        $conn->close();

        // Alert the user and redirect to the checkout page
        echo "<script>
                alert('Order successful!');
                window.location.href = 'checkout.php';
              </script>";
    } else {
        // Alert the user if no items are selected and redirect to the order page
        echo "<script>
                alert('No items selected.');
                window.location.href = 'order.php';
              </script>";
    }
} else {
    // Alert the user if they are not logged in and redirect to the login page
    echo "<script>
            alert('You must be logged in to place an order.');
            window.location.href = 'login.php';
          </script>";
}
?>
