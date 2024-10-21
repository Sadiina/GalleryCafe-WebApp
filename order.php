<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Gallery Café</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .items-grid .item p {
            color: black;
        }
        .search-form {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .search-form input[type="text"] {
            padding: 5px;
            font-size: 16px;
            margin-right: 0; 
        }
        .search-form .search-button {
            width: 40px; 
            height: 34px; 
            cursor: pointer;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 0 4px 4px 0; 
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .search-form .search-button i {
            font-size: 16px;
        }
        .items-grid .item img {
            width: 100px; 
            height: auto;
            border-radius: 10px; 
        }
        .items-grid .item {
            text-align: center; 
            margin: 10px; 
        }
        .filter-button {
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            margin-bottom: 20px;
            width: 20px;
            height: 10px;
        }
        .filter-menu {
            color: black;
            display: none;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 4px;
            position: absolute;

        }
        .filter-menu label {
            margin-right: 10px;
        }
        .filter-menu input[type="radio"] {
            margin-right: 5px;
        }
        .selected {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="logo"><a href="index.php"><img src="img/logo.png" width="150px" height="100px"></a></div>
        <ul>
            <li class="back"><a href="User/index_user.php">Back</a></li>
        </ul>
    </nav>

    <!-- Order Section -->
    <section id="order">
        <h2>Order Your Favorite Cuisine</h2>

        <!-- Search Form -->
        <form class="search-form" method="get" action="">
            <input type="text" name="search" placeholder="Search for items...">
            <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
        </form>
        <!-- Filter Button -->
        <button class="filter-button" onclick="toggleFilterMenu()">Filter <i class="fas fa-filter"></i></button>
        
        <!-- Filter Menu -->
        <div class="filter-menu" id="filter-menu">
            <form id="filter-form" method="get" action="">
                <label><input type="radio" name="cuisine" value="Sri Lankan"> Sri Lankan</label>
                <label><input type="radio" name="cuisine" value="Chinese"> Chinese</label>
                <label><input type="radio" name="cuisine" value="Italian"> Italian</label>
                <button type="submit">Apply</button>
            </form>
        </div>
        
        <!-- Order Form -->
        <form id="order-form" action="process-order.php" method="post">
            <div class="items-grid">
                <?php
                // Connect to the database
                $conn = new mysqli("localhost", "root", "", "gallery_cafe");

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Get search and cuisine filters
                $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
                $cuisine = isset($_GET['cuisine']) ? $conn->real_escape_string($_GET['cuisine']) : '';
                
                // Build the SQL query
                $sql = "SELECT id, name, price, image FROM menu_items";
                $conditions = [];
                if (!empty($search)) {
                    $conditions[] = "(name LIKE '%$search%' OR description LIKE '%$search%')";
                }
                if (!empty($cuisine)) {
                    $conditions[] = "category = '$cuisine'";
                }
                if (!empty($conditions)) {
                    $sql .= " WHERE " . implode(' AND ', $conditions);
                }
                
                // Execute the query
                $result = $conn->query($sql);

                // Display the items
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='item' onclick='toggleCheckbox(\"item_{$row['id']}\")'>
                                <img src='Admin/uploads/menu/{$row['image']}' alt='{$row['name']}'>
                                <h3>{$row['name']}</h3>
                                <p>LKR {$row['price']}</p>
                                <input type='checkbox' id='item_{$row['id']}' name='cuisine[]' value='{$row['id']}'>
                              </div>";
                    }
                } else {
                    echo "<p>No items available</p>";
                }

                // Close the connection
                $conn->close();
                ?>
            </div>
            <button type="submit">Continue</button>
        </form>
    </section>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="container03">
            <div class="row">
                <div class="footer-col">
                    <h4>The Gallery</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="index.php">Dining</a></li>
                        <li><a href="index.php">About Us</a></li>
                        <li><a href="index.php">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Get Help</h4>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Reviews</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Order Using</h4>
                    <ul>
                        <li><a href="https://www.uber.com/">Uber.lk</a></li>
                        <li><a href="https://pickme.lk/">Pickme.lk</a></li>
                        <li><a href="https://www.doordash.com/">doordash.com</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Follow Us</h4>
                    <div class="social-media">
                        <a href="https://www.facebook.com"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.twitter.com"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Toggle checkbox selection and item highlight
        function toggleCheckbox(id) {
            var checkbox = document.getElementById(id);
            checkbox.checked = !checkbox.checked;
            var item = document.getElementById(id).parentElement;
            item.classList.toggle('selected');
        }

        // Toggle the display of the filter menu
        function toggleFilterMenu() {
            var filterMenu = document.getElementById('filter-menu');
            filterMenu.style.display = filterMenu.style.display === 'block' ? 'none' : 'block';
        }
    </script>
</body>
</html>
