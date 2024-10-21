<?php
session_start();

// Database connection settings
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - The Gallery Café</title>
    <link rel="stylesheet" href="staff_style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Staff Dashboard</h2>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="#menu"><i class="fas fa-utensils"></i> Menu</a></li>
                    <li><a href="#reservations"><i class="fas fa-calendar-alt"></i> Reservations</a></li>
                    <li><a href="#orders"><i class="fas fa-receipt"></i> Orders</a></li>
                    <li><a href="#parking"><i class="fas fa-parking"></i> Car Parking</a></li>
                    <li><a href="../index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <h1>Welcome, Staff</h1>
            </header>

            <section id="menu" class="dashboard-section">
                <h2>Menu</h2>
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
                                            <td><img src='../Admin/uploads/menu/{$row['image']}' alt='{$row['name']}' width='50'></td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No menu items found</td></tr>";
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
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No reservations found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>

            <section id="orders" class="dashboard-section">
                <h2>Orders</h2>
                <div class="existing-orders">
                    <h3>Order Details</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Item Name</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT o.username, mi.name AS item_name, mi.price 
                                    FROM orders o
                                    JOIN menu_items mi ON o.item_id = mi.id";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$row['username']}</td>
                                            <td>{$row['item_name']}</td>
                                            <td>LKR {$row['price']}</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No orders found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="parking" class="dashboard-section">
                <h2>Car Parking</h2>
                <form id="parking-form" action="admin.php" method="post">
                    <label for="parking-status">Parking Status:</label>
                    <input type="text" id="parking-status" name="parking_status" required>
                    <label for="parking-description">Parking Description:</label>
                    <textarea id="parking-description" name="parking_description" required></textarea>
                    <button type="submit">Add Parking Info</button>
                </form>

                <div class="existing-parking">
                    <h3>Existing Parking Information</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Description</th>
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
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2'>No parking information found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>

<?php
$conn->close();
?>
