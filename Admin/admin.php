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

// Delete reservation if delete button is clicked
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_reservation_id'])) {
    $reservationId = $_POST['delete_reservation_id'];
    $deleteSql = "DELETE FROM reservations WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $reservationId);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle new promotion upload
    if (isset($_FILES['promo_image'])) {
        $target_dir = "uploads/promotions/";
        $target_file = $target_dir . basename($_FILES["promo_image"]["name"]);
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
        if ($_FILES["promo_image"]["size"] > 5000000) {
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
            if (move_uploaded_file($_FILES["promo_image"]["tmp_name"], $target_file)) {
                // Insert promotion details into database
                $sql = "INSERT INTO promotions (image) VALUES (?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $target_file);
                $stmt->execute();
                $stmt->close();
                echo "The file ". htmlspecialchars(basename($_FILES["promo_image"]["name"])) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Handle new special event
    if (isset($_POST['event_name'])) {
        $eventName = $_POST['event_name'];
        $eventDescription = $_POST['event_description'];
        $eventDate = $_POST['event_date'];

        $sql = "INSERT INTO special_events (event_name, event_description, event_date) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $eventName, $eventDescription, $eventDate);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Special event added successfully!');
                    window.location.href = 'admin.php';
                  </script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // Handle new car parking information
    if (isset($_POST['parking_status']) && isset($_POST['parking_description'])) {
        $parkingStatus = $_POST['parking_status'];
        $parkingDescription = $_POST['parking_description'];

        $sql = "INSERT INTO parking_info (parking_status, parking_description) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $parkingStatus, $parkingDescription);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Car parking information added successfully!');
                    window.location.href = 'admin.php';
                  </script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // Handle order deletion if delete button is clicked
    if (isset($_POST['delete_order_id'])) {
        $orderId = $_POST['delete_order_id'];
        // Delete order items first due to foreign key constraint
        $deleteOrderItemsSql = "DELETE FROM order_items WHERE order_id = ?";
        $stmt = $conn->prepare($deleteOrderItemsSql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $stmt->close();

        // Delete the order
        $deleteOrderSql = "DELETE FROM orders WHERE id = ?";
        $stmt = $conn->prepare($deleteOrderSql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $stmt->close();
    }

    // Handle user deletion if delete button is clicked
    if (isset($_POST['delete_user_id'])) {
        $userId = $_POST['delete_user_id'];
        $deleteUserSql = "DELETE FROM user WHERE id = ?";
        $stmt = $conn->prepare($deleteUserSql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    }

    // Handle special event edit
    if (isset($_POST['edit_event_id'])) {
        $eventId = $_POST['edit_event_id'];
        $eventName = $_POST['edit_event_name'];
        $eventDescription = $_POST['edit_event_description'];
        $eventDate = $_POST['edit_event_date'];

        $sql = "UPDATE special_events SET event_name = ?, event_description = ?, event_date = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $eventName, $eventDescription, $eventDate, $eventId);
        $stmt->execute();
        $stmt->close();
    }

    // Handle parking information edit
    if (isset($_POST['edit_parking_id'])) {
        $parkingId = $_POST['edit_parking_id'];
        $parkingStatus = $_POST['edit_parking_status'];
        $parkingDescription = $_POST['edit_parking_description'];

        $sql = "UPDATE parking_info SET parking_status = ?, parking_description = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $parkingStatus, $parkingDescription, $parkingId);
        $stmt->execute();
        $stmt->close();
    }

    // Handle parking information deletion
    if (isset($_POST['delete_parking_id'])) {
        $parkingId = $_POST['delete_parking_id'];
        $deleteSql = "DELETE FROM parking_info WHERE id = ?";
        $stmt = $conn->prepare($deleteSql);
        $stmt->bind_param("i", $parkingId);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - The Gallery Café</title>
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Admin Dashboard</h2>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="#menu"><i class="fas fa-utensils"></i> Menu</a></li>
                    <li><a href="#reservations"><i class="fas fa-calendar-alt"></i> Reservations</a></li>
                    <li><a href="#orders"><i class="fas fa-receipt"></i> Orders</a></li>
                    <li><a href="#promotions"><i class="fas fa-tags"></i> Promotions</a></li>
                    <li><a href="#special-events"><i class="fas fa-calendar-day"></i> Special Events</a></li>
                    <li><a href="#parking"><i class="fas fa-parking"></i> Car Parking</a></li>
                    <li><a href="#users"><i class="fas fa-users"></i> Users</a></li>
                    <li><a href="../index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <h1>Welcome, Admin</h1>
            </header>

            <section id="menu" class="dashboard-section">
                <h2>Menu</h2>
                <form id="menu-form" action="upload.php" method="post" enctype="multipart/form-data">
                    <label for="menu-item-name">Item Name:</label>
                    <input type="text" id="menu-item-name" name="menu-item-name" required>
                    <label for="menu-item-description">Description:</label>
                    <textarea id="menu-item-description" name="menu-item-description" required></textarea>
                    <label for="menu-item-price">Price:</label>
                    <input type="number" step="0.01" id="menu-item-price" name="menu-item-price" required>
                    <label for="menu-item-category">Category:</label>
                    <select id="menu-item-category" name="menu-item-category" required>
                        <option value="Sri Lankan">Sri Lankan</option>
                        <option value="Chinese">Chinese</option>
                        <option value="Italian">Italian</option>
                        <!-- Add more categories as needed -->
                    </select>
                    <label for="menu-item-image">Image:</label>
                    <input type="file" id="menu-item-image" name="menu-item-image" accept="image/*" required>
                    <button type="submit">Add Menu Item</button>
                </form>

                <div class="existing-menu">
                    <h3>Existing Menu Items</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT id, name, description, price, category, image FROM menu_items";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$row['name']}</td>
                                            <td>{$row['description']}</td>
                                            <td>LKR {$row['price']}</td>
                                            <td>{$row['category']}</td>
                                            <td><img src='uploads/menu/{$row['image']}' alt='{$row['name']}' width='50'></td>
                                            <td>
                                                <form action='edit_menu.php' method='post' style='display:inline;'>
                                                    <input type='hidden' name='menu-id' value='{$row['id']}'>
                                                    <button type='submit'>Edit</button>
                                                </form>
                                                <form action='delete_menu.php' method='post' style='display:inline;'>
                                                    <input type='hidden' name='menu-id' value='{$row['id']}'>
                                                    <button type='submit'>Delete</button>
                                                </form>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No menu items found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="reservations" class="dashboard-section">
                <h2>Reservations</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>No. of Guests</th>
                            <th>Special Requests</th>
                            <th>User Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT reservations.*, user.username FROM reservations JOIN user ON reservations.user_id = user.id";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['date']}</td>
                                        <td>{$row['time']}</td>
                                        <td>{$row['guests']}</td>
                                        <td>{$row['requests']}</td>
                                        <td>{$row['username']}</td>
                                        <td>
                                            <form action='admin.php' method='post' style='display:inline;'>
                                                <input type='hidden' name='delete_reservation_id' value='{$row['id']}'>
                                                <button type='submit'>Delete</button>
                                            </form>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No reservations found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>

            <section id="orders" class="dashboard-section">
                <h2>Orders</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Payment Method</th>
                            <th>Order Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM orders";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($order = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$order['id']}</td>
                                        <td>{$order['delivery_name']}</td>
                                        <td>{$order['delivery_address']}</td>
                                        <td>{$order['delivery_phone']}</td>
                                        <td>{$order['delivery_email']}</td>
                                        <td>{$order['payment_method']}</td>
                                        <td>";
                                
                                // Fetch order items
                                $orderId = $order['id'];
                                $itemSql = "SELECT menu_items.name, menu_items.price FROM order_items 
                                            JOIN menu_items ON order_items.item_id = menu_items.id 
                                            WHERE order_items.order_id = $orderId";
                                $itemResult = $conn->query($itemSql);

                                if ($itemResult->num_rows > 0) {
                                    while ($item = $itemResult->fetch_assoc()) {
                                        echo "{$item['name']} (LKR {$item['price']})<br>";
                                    }
                                } else {
                                    echo "No items";
                                }

                                echo "</td>
                                      <td>
                                          <form action='admin.php' method='post' style='display:inline;'>
                                              <input type='hidden' name='delete_order_id' value='{$order['id']}'>
                                              <button type='submit'>Delete</button>
                                          </form>
                                      </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No orders found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>

            <section id="promotions" class="dashboard-section">
                <h2>Promotions</h2>
                <form id="promo-form" action="admin.php" method="post" enctype="multipart/form-data">
                    <label for="promo_image">Upload Promotion Image:</label>
                    <input type="file" id="promo_image" name="promo_image" accept="image/*" required>
                    <button type="submit">Add Promotion</button>
                </form>
                
                <div class="existing-promotions">
                    <h3>Existing Promotions</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT id, image FROM promotions";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td><img src='uploads/promotions/{$row['image']}' alt='Promotion Image' width='100'></td>
                                            <td>
                                                <form action='delete_promotion.php' method='post' style='display:inline;'>
                                                    <input type='hidden' name='promotion-id' value='{$row['id']}'>
                                                    <button type='submit'>Delete</button>
                                                </form>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2'>No promotions found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Special Events Section -->
            <section id="special-events" class="dashboard-section">
                <h2>Special Events</h2>
                <form id="event-form" action="admin.php" method="post">
                    <label for="event-name">Event Name:</label>
                    <input type="text" id="event-name" name="event_name" required>
                    <label for="event-description">Event Description:</label>
                    <textarea id="event-description" name="event_description" required></textarea>
                    <label for="event-date">Event Date:</label>
                    <input type="date" id="event-date" name="event_date" required>
                    <button type="submit">Add Event</button>
                </form>

                <div class="existing-events">
                    <h3>Existing Events</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Event Name</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM special_events";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$row['event_name']}</td>
                                            <td>{$row['event_description']}</td>
                                            <td>{$row['event_date']}</td>
                                            <td>
                                                <form action='admin.php' method='post' style='display:inline;'>
                                                    <input type='hidden' name='edit_event_id' value='{$row['id']}'>
                                                    <input type='hidden' name='edit_event_name' value='{$row['event_name']}'>
                                                    <input type='hidden' name='edit_event_description' value='{$row['event_description']}'>
                                                    <input type='hidden' name='edit_event_date' value='{$row['event_date']}'>
                                                    <button type='submit'>Edit</button>
                                                </form>
                                                <form action='admin.php' method='post' style='display:inline;'>
                                                    <input type='hidden' name='delete_event_id' value='{$row['id']}'>
                                                    <button type='submit'>Delete</button>
                                                </form>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No events found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_event_id'])): ?>
                <div class="edit-event-form">
                    <h3>Edit Event</h3>
                    <form action="admin.php" method="post">
                        <input type="hidden" name="edit_event_id" value="<?php echo $_POST['edit_event_id']; ?>">
                        <label for="edit-event-name">Event Name:</label>
                        <input type="text" id="edit-event-name" name="edit_event_name" value="<?php echo $_POST['edit_event_name']; ?>" required>
                        <label for="edit-event-description">Event Description:</label>
                        <textarea id="edit-event-description" name="edit_event_description" required><?php echo $_POST['edit_event_description']; ?></textarea>
                        <label for="edit-event-date">Event Date:</label>
                        <input type="date" id="edit-event-date" name="edit_event_date" value="<?php echo $_POST['edit_event_date']; ?>" required>
                        <button type="submit">Update Event</button>
                    </form>
                </div>
                <?php endif; ?>
            </section>

            <section id="parking" class="dashboard-section">
                <h2>Car Parking</h2>
                <form id="parking-form" action="admin.php" method="post">
                    <label for="parking-status">Parking Status:</label>
                    <select id="parking-status" name="parking_status" required>
                        <option value="Available">Available</option>
                        <option value="Full">Full</option>
                        <option value="Closed">Closed</option>
                    </select>
                    <label for="parking-description">Number of Spaces:</label>
                    <input type="number" id="parking-description" name="parking_description" required>
                    <button type="submit">Add Parking Info</button>
                </form>

                <div class="existing-parking">
                    <h3>Existing Parking Information</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Number of Spaces</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM parking_info";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$row['parking_status']}</td>
                                            <td>{$row['parking_description']}</td>
                                            <td>
                                                <form action='admin.php' method='post' style='display:inline;'>
                                                    <input type='hidden' name='edit_parking_id' value='{$row['id']}'>
                                                    <input type='hidden' name='edit_parking_status' value='{$row['parking_status']}'>
                                                    <input type='hidden' name='edit_parking_description' value='{$row['parking_description']}'>
                                                    <button type='submit'>Edit</button>
                                                </form>
                                                <form action='admin.php' method='post' style='display:inline;'>
                                                    <input type='hidden' name='delete_parking_id' value='{$row['id']}'>
                                                    <button type='submit'>Delete</button>
                                                </form>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No parking information found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_parking_id'])): ?>
                <div class="edit-parking-form">
                    <h3>Edit Parking Information</h3>
                    <form action="admin.php" method="post">
                        <input type="hidden" name="edit_parking_id" value="<?php echo $_POST['edit_parking_id']; ?>">
                        <label for="edit-parking-status">Parking Status:</label>
                        <select id="edit-parking-status" name="edit_parking_status" required>
                            <option value="Available" <?php if ($_POST['edit_parking_status'] == 'Available') echo 'selected'; ?>>Available</option>
                            <option value="Full" <?php if ($_POST['edit_parking_status'] == 'Full') echo 'selected'; ?>>Full</option>
                            <option value="Closed" <?php if ($_POST['edit_parking_status'] == 'Closed') echo 'selected'; ?>>Closed</option>
                        </select>
                        <label for="edit-parking-description">Number of Spaces:</label>
                        <input type="number" id="edit-parking-description" name="edit_parking_description" value="<?php echo $_POST['edit_parking_description']; ?>" required>
                        <button type="submit">Update Parking Info</button>
                    </form>
                </div>
                <?php endif; ?>
            </section>

            <!-- Users Section -->
            <section id="users" class="dashboard-section">
                <h2>Users</h2>
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT id, username FROM user";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['username']}</td>
                                        <td>
                                            <form action='admin.php' method='post' style='display:inline;'>
                                                <input type='hidden' name='delete_user_id' value='{$row['id']}'>
                                                <button type='submit'>Delete</button>
                                            </form>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No users found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <script src="script.js"></script>
</body>
</html>

<?php
$conn->close();
?>
