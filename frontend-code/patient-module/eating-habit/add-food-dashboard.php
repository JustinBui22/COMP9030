<?php
// Database connection details
include "../../common/inc/dbconn.inc.php";

// Handle new food submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $foodInfo = $_POST['food'];
    $calories = $_POST['calories'];
    $amount = $_POST['amount'];

    // Prepare and bind the SQL statement to insert the data without meal type
    $stmt = $conn->prepare("INSERT INTO food_info (food_name, food_calo, food_amount) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $foodInfo, $calories, $amount); // Removed meal type from binding

    // Execute the SQL statement and fetch the updated list of foods
    if ($stmt->execute()) {
        $result = $conn->query("SELECT * FROM food_info");
        $foodInfo = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($foodInfo);
    } else {
        echo json_encode(["error" => "Failed to insert data"]);
    }

    // Close the statement and exit
    $stmt->close();
    exit;
}

// Retrieve all food from the database
$result = $conn->query("SELECT * FROM food_info");
$foodInfo = [];

if ($result->num_rows > 0) {
    $foodInfo = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Justin Bui">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Navigation Bar -->
    <header>
        <nav class="navbar">
            <div class="logo">
                <h1>EatingHabitPal</h1>
            </div>
            <ul class="nav-links">
                <li><a href="log-food-dashboard.php">Log Food</a></li>
                <li><a href="add-exercise-dashboard.php">Exercise Library</a></li>
                <li><a href="add-food-dashboard.php" style="text-decoration: underline; color: red;">Food Library</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Dashboard Content -->
    <main class="dashboard">
        <section>
            <!-- Search Bar for Foods -->
            <div class="search-bar">
                <form id="searchForm">
                    <input type="search" id="searchQuery" placeholder="Search for a food" required>
                    <input type="submit" value="Search">
                </form>
                <!-- Section for search results message or data -->
                <div id="searchResultsMessage" style="color: red; margin-bottom: 10px;"></div>
            </div>

            <!-- Form to Add New Food -->
            <div class="search-bar">
                <form id="addFoodForm">
                    <input type="text" id="food" name="food" placeholder="Food Name" required>
                    <input type="number" id="calories" name="calories" placeholder="Calories" required>
                    <input type="text" id="amount" name="amount" placeholder="Amount (g)" required>
                    <input type="submit" value="Add New Food">
                </form>
            </div>

            <!-- Display Foods -->
            <div class="nutrient-info">
                <h3>History of Foods:</h3>
                <table class="add-items-tables" id="foodTable">
                    <thead>
                        <tr>
                            <th>Foods</th>
                            <th>Calories</th>
                            <th>Amount (g)</th>
                            <th>Meal Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="foodBody">
                        <!-- Existing foods fetched from the database -->
                        <?php
                        // Retrieve foods from the database
                        $result = $conn->query("SELECT * FROM food_info");

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['food_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['food_calo']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['food_amount']) . '</td>';
                                echo '<td>';
                                echo '<select class="meal-type-dropdown" data-food="' . htmlspecialchars($row['food_name']) . '">'; // Dropdown always visible
                                echo '<option value="breakfast">Breakfast</option>';
                                echo '<option value="lunch">Lunch</option>';
                                echo '<option value="dinner">Dinner</option>';
                                echo '<option value="snack">Snack</option>';
                                echo '</select>';
                                echo '</td>';
                                echo '<td><button class="add-food-btn" data-food=\'{"name": "' . htmlspecialchars($row['food_name']) . '", "calories": ' . intval($row['food_calo']) . ', "amount": ' . intval($row['food_amount']) . '}\' >Add Food</button></td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5" class="small-font" style="text-align: center; " >No foods added yet.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <div class="complete-day-dairy-container">
            <input type="button" id="complete-day-dairy-button" class="complete-day-dairy-button" value="Back" onclick="location.href='log-food-dashboard.php';">
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 EatingHabitPal. All rights reserved.</p>
    </footer>

    <!-- JavaScript to handle form submission and update food list -->
    <script>
        // Helper function to generate food rows
        function generateFoodRow(item) {
            return `
                <tr>
                    <td>${item.food_name}</td>
                    <td>${item.food_calo}</td>
                    <td>${item.food_amount}</td>
                    <td>
                        <select class="meal-type-dropdown" data-food="${item.food_name}">
                            <option value="breakfast">Breakfast</option>
                            <option value="lunch">Lunch</option>
                            <option value="dinner">Dinner</option>
                            <option value="snack">Snack</option>
                        </select>
                    </td>
                    <td>
                        <button class="add-food-btn" 
                            data-food='{"name": "${item.food_name}", "calories": ${item.food_calo}, "amount": ${item.food_amount}}'>Add Food</button>
                    </td>
                </tr>
            `;
        }

        // Function to update food table dynamically
        function updateFoodTable(foodInfo) {
            const foodBody = document.getElementById('foodBody');
            foodBody.innerHTML = foodInfo.map(generateFoodRow).join('');
        }

        document.getElementById('addFoodForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form values
            const formData = new URLSearchParams(new FormData(this)).toString();

            // Send POST request to add food
            fetch('add-food-dashboard.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Reset form and update food table
                    this.reset();
                    updateFoodTable(data);
                })
                .catch(console.error);
        });

        // Search functionality
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const query = document.getElementById('searchQuery').value;

            // Specify the type of search (exercise or food)
            const searchType = 'food';

            // Send GET request to search for exercises or food
            fetch('search-data.php?query=' + encodeURIComponent(query) + '&type=' + searchType)
                .then(response => response.json())
                .then(data => {
                    const foodBody = document.getElementById('foodBody');
                    const searchResultsMessage = document.getElementById('searchResultsMessage');
                    searchResultsMessage.textContent = ''; // Clear previous search message

                    if (data.length > 0) {
                        updateFoodTable(data); // Update table with search results
                    } else {
                        searchResultsMessage.textContent = 'No such food found. Please create a new one.';

                        // Fetch all foods from the database to display history
                        fetch('get-data.php') // Create a separate PHP file to get all food
                            .then(response => response.json())
                            .then(allFoods => {
                                updateFoodTable(allFoods); // Populate table with all food
                            })
                            .catch(console.error);
                    }
                })
                .catch(console.error);
        });

        // Add this inside the delegate event listener for the "Add Food" button
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-food-btn')) {
                // Parse the data-food JSON string
                const foodData = JSON.parse(e.target.getAttribute('data-food'));

                // Now you can access the values directly
                const foodName = foodData.name;
                const foodCalo = foodData.calories;
                const foodAmount = foodData.amount;
                const dropdown = e.target.closest('tr').querySelector('.meal-type-dropdown');

                // Get the selected meal type
                const mealType = dropdown.value;

                // Prepare the data to send to the log food page
                const logData = {
                    food_name: foodName,
                    food_calories: foodCalo,
                    food_amount: foodAmount,
                    meal_type: mealType
                };

                let loggedFoods = JSON.parse(localStorage.getItem('loggedFoods')) || [];
                loggedFoods.push(logData);
                localStorage.setItem('loggedFoods', JSON.stringify(loggedFoods));

                // Use Ajax to send the localStorage data to the server
                fetch('log-food-dashboard.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        loggedFoods
                    })
                }).then(() => {
                    alert('Food added: ' + foodName + ' for ' + mealType);
                    window.location.href = "log-food-dashboard.php";
                }).catch(console.error);
            }
        });
    </script>
</body>

</html>