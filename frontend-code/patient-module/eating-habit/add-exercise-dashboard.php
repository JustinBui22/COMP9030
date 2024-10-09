<?php
// Database connection details
include "../../common/inc/dbconn.inc.php";

// Handle new exercise submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $exercise = $_POST['exercise'];
    $calories = $_POST['calories'];
    $duration = $_POST['duration'];

    $stmt = $conn->prepare("INSERT INTO exercises (ex_name, ex_calo, ex_duration) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $exercise, $calories, $duration);

    // Execute the SQL statement and fetch the updated list of exercises
    if ($stmt->execute()) {
        $result = $conn->query("SELECT * FROM exercises");
        $exercises = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($exercises);
    } else {
        echo json_encode(["error" => "Failed to insert data"]);
    }
    $stmt->close();
    exit;
}

// Retrieve all exercises from the database
$result = $conn->query("SELECT * FROM exercises");
$exercises = [];

if ($result->num_rows > 0) {
    $exercises = $result->fetch_all(MYSQLI_ASSOC);
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
                <li><a href="add-exercise-dashboard.php" style="text-decoration: underline; color: red;">Exercise Library</a></li>
                <li><a href="add-food-dashboard.php">Food Library</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Dashboard Content -->
    <main class="dashboard">
        <section>
            <!-- Search Bar for Exercises -->
            <div class="search-bar">
                <form id="searchForm">
                    <input type="search" id="searchQuery" placeholder="Search for an exercise" required>
                    <input type="submit" value="Search">
                </form>

                <!-- Section for search results message or data -->
                <div id="searchResultsMessage" class="search-results-message"></div>
            </div>

            <!-- Form to Add New Exercise -->
            <div class="search-bar">
                <form id="addExerciseForm">
                    <input type="text" id="exercise" name="exercise" placeholder="Exercise Name" required>
                    <input type="number" id="calories" name="calories" placeholder="Calories Burnt" required>
                    <input type="text" id="duration" name="duration" placeholder="Duration (minutes)" required>
                    <input type="submit" value="Add Exercise">
                </form>
            </div>

            <!-- Display Exercises -->
            <div class="nutrient-info">
                <h3>History of Exercises:</h3>
                <table class="add-items-tables" id="exerciseTable">
                    <thead>
                        <tr>
                            <th>Exercises</th>
                            <th>Calories Burnt</th>
                            <th>Duration (Minutes)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="exerciseBody">
                        <?php
                        // Retrieve exercises from the database
                        $result = $conn->query("SELECT * FROM exercises");

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['ex_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['ex_calo']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['ex_duration']) . '</td>';
                                echo '<td><button class="add-exercise-btn" data-exercise=\'{"name": "' . htmlspecialchars($row['ex_name']) . '", "calories": ' . intval($row['ex_calo']) . ', "duration": ' . intval($row['ex_duration']) . '}\' >Add Exercise</button></td>';

                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4" class="small-font" style="text-align: center; " >No exercises added yet.</td></tr>';
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

    <footer>
        <p>&copy; 2024 EatingHabitPal. All rights reserved.</p>
    </footer>

    <!-- JavaScript to update exercise list -->
    <script>
        // Function to generate exercise rows
        function generateExerciseRow(item) {
            return `
                <tr>
                    <td>${item.ex_name}</td>
                    <td>${item.ex_calo}</td>
                    <td>${item.ex_duration}</td>
                    <td><button class="add-exercise-btn" 
                    data-exercise='{"name": "${item.ex_name}", "calories": ${item.ex_calo}, "duration": ${item.ex_duration}}'>Add Exercise</button>
                    </td>
                </tr>
            `;
        }

        // Function to update exercise table dynamically
        function updateExerciseTable(exercises) {
            const exerciseBody = document.getElementById('exerciseBody');
            exerciseBody.innerHTML = exercises.map(generateExerciseRow).join('');
        }

        document.getElementById('addExerciseForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new URLSearchParams(new FormData(this)).toString();

            // Send POST request to add exercise
            fetch('add-exercise-dashboard.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    this.reset();
                    updateExerciseTable(data);
                })
                .catch(console.error);
        });

        // Search
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const query = document.getElementById('searchQuery').value;

            // Specify the type of search (exercise or food)
            const searchType = 'exercise';

            // Send GET request to search for exercises or food
            fetch('search-data.php?query=' + encodeURIComponent(query) + '&type=' + searchType)
                .then(response => response.json())
                .then(data => {
                    const exerciseBody = document.getElementById('exerciseBody');
                    const searchResultsMessage = document.getElementById('searchResultsMessage');
                    searchResultsMessage.textContent = '';

                    if (data.length > 0) {
                        updateExerciseTable(data);
                    } else {
                        searchResultsMessage.textContent = 'No such exercise found. Please create a new one.';

                        // Fetch all exercises from the database to display history
                        fetch('get-data.php')
                            .then(response => response.json())
                            .then(allExercises => {
                                updateExerciseTable(allExercises);
                            })
                            .catch(console.error);
                    }
                })
                .catch(console.error);
        });

        // Dynamically added "Add Exercise" buttons
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-exercise-btn')) {
                const exerciseData = JSON.parse(e.target.getAttribute('data-exercise'));

                const exName = exerciseData.name;
                const exCalo = exerciseData.calories;
                const exDuration = exerciseData.duration;

                const logData = {
                    ex_name: exName,
                    ex_calories: exCalo,
                    ex_duration: exDuration,
                };

                let loggedExercies = JSON.parse(localStorage.getItem('loggedExercies')) || [];
                loggedExercies.push(logData);
                localStorage.setItem('loggedExercies', JSON.stringify(loggedExercies));

                fetch('log-food-dashboard.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        loggedExercies
                    })
                }).then(() => {
                    alert('Exercise added: ' + exName);
                    window.location.href = "log-food-dashboard.php";
                }).catch(console.error);
            }
        });
    </script>
</body>

</html>