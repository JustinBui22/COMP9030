<?php
session_start();
include 'db.php';

// Retrieve logged foods from session or initialize an array
$loggedFoods = isset($_SESSION['loggedFoods']) ? $_SESSION['loggedFoods'] : [];
$loggedExercises = isset($_SESSION['loggedExercises']) ? $_SESSION['loggedExercises'] : [];

// Categorization logic based on food names or exercises
$breakfastFoods = isset($_SESSION['breakfastFoods']) ? $_SESSION['breakfastFoods'] : [];
$lunchFoods = isset($_SESSION['lunchFoods']) ? $_SESSION['lunchFoods'] : [];
$dinnerFoods = isset($_SESSION['dinnerFoods']) ? $_SESSION['dinnerFoods'] : [];
$snackFoods = isset($_SESSION['snackFoods']) ? $_SESSION['snackFoods'] : [];

$exercises = isset($_SESSION['exercises']) ? $_SESSION['exercises'] : [];

// Check if there's POST data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Handle deletion of food or exercise
    if (isset($_POST['delete_type']) && isset($_POST['delete_index'])) {
        $deleteType = $_POST['delete_type'];
        $deleteIndex = intval($_POST['delete_index']);

        switch ($deleteType) {
            case 'breakfast':
                if (isset($breakfastFoods[$deleteIndex])) {
                    array_splice($breakfastFoods, $deleteIndex, 1); // Remove the selected item
                    $_SESSION['breakfastFoods'] = $breakfastFoods;
                }
                break;
            case 'lunch':
                if (isset($lunchFoods[$deleteIndex])) {
                    array_splice($lunchFoods, $deleteIndex, 1);
                    $_SESSION['lunchFoods'] = $lunchFoods;
                }
                break;
            case 'dinner':
                if (isset($dinnerFoods[$deleteIndex])) {
                    array_splice($dinnerFoods, $deleteIndex, 1);
                    $_SESSION['dinnerFoods'] = $dinnerFoods;
                }
                break;
            case 'snack':
                if (isset($snackFoods[$deleteIndex])) {
                    array_splice($snackFoods, $deleteIndex, 1);
                    $_SESSION['snackFoods'] = $snackFoods;
                }
                break;
            case 'exercise':
                if (isset($exercises[$deleteIndex])) {
                    array_splice($exercises, $deleteIndex, 1);
                    $_SESSION['exercises'] = $exercises;
                }
                break;
        }
    }
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['loggedFoods'])) {
        $loggedFoodsFromAddFood = $data['loggedFoods'];
        $lastIndex = count($loggedFoodsFromAddFood) - 1;
        $loggedFoods = $loggedFoodsFromAddFood[$lastIndex];

        if (isset($loggedFoods)) {
            switch ($loggedFoods['meal_type']) {
                case 'breakfast':
                    $breakfastFoods[] = $loggedFoods;
                    break;
                case 'lunch':
                    $lunchFoods[] = $loggedFoods;
                    break;
                case 'dinner':
                    $dinnerFoods[] = $loggedFoods;
                    break;
                case 'snack':
                    $snackFoods[] = $loggedFoods;
                    break;
            }
        }
    }

    if (isset($data['loggedExercies'])) {
        $loggedExercisesFromAddExercise = $data['loggedExercies'];
        $lastIndex = count($loggedExercisesFromAddExercise) - 1;
        $loggedExercises = $loggedExercisesFromAddExercise[$lastIndex];

        if (isset($loggedExercises)) {
            $exercises[] =  $loggedExercises;
        }
    }


    // Save updated logged foods back to the session
    $_SESSION['loggedFoods'] = isset($loggedFoodsFromAddFood) ? $loggedFoodsFromAddFood : [];
    $_SESSION['loggedExercises'] = isset($loggedExercisesFromAddExercise) ? $loggedExercisesFromAddExercise : [];

    $_SESSION['breakfastFoods'] = $breakfastFoods;
    $_SESSION['lunchFoods'] = $lunchFoods;
    $_SESSION['dinnerFoods'] = $dinnerFoods;
    $_SESSION['snackFoods'] = $snackFoods;

    $_SESSION['exercises'] = $exercises;

    // unset($_SESSION['loggedFoods']);
    // unset($_SESSION['loggedExercises']);

    // unset($_SESSION['breakfastFoods']);
    // unset($_SESSION['lunchFoods']);
    // unset($_SESSION['dinnerFoods']);
    // unset($_SESSION['snackFoods']);

    // unset($_SESSION['exercises']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Justin Bui">
    <title>Log Food</title>
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
                <li><a href="log-food-dashboard.php" style="text-decoration: underline; color: red;">Log Food</a></li>
                <li><a href="add-exercise-dashboard.php">Exercise Library</a></li>
                <li><a href="add-food-dashboard.php">Food Library</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="dashboard">
        <h3 class="big-heading">Today's Summary</h3>
        <section>
            <!-- Meals Nutrients Info -->
            <div class="nutrient-info">
                <h3>Logged Nutrient Infos</h3>

                <!-- Breakfast Table -->
                <h3 class="meal-heading">Breakfasts</h3>
                <table class="calories-tables">
                    <thead>
                        <tr>
                            <th>Food</th>
                            <th>Calories</th>
                            <th>Amount (g)</th>
                            <th>Action</th> <!-- Column for delete buttons -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($breakfastFoods) > 0) {
                            foreach ($breakfastFoods as $index => $foodRow) {
                                echo '<tr>';
                                echo '<td class="small-font">' . htmlspecialchars($foodRow['food_name']) . '</td>';
                                echo '<td class="small-font">' . htmlspecialchars($foodRow['food_calories']) . '</td>';
                                echo '<td class="small-font">' . htmlspecialchars($foodRow['food_amount']) . '</td>';
                                echo '<td>
                                        <form method="POST">
                                            <input type="hidden" name="delete_type" value="breakfast">
                                            <input type="hidden" name="delete_index" value="' . $index . '">
                                            <button type="submit" class="delete-button">Remove</button>
                                        </form>
                                      </td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4" class="small-font" style="text-align: center;">No breakfasts logged yet.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <a href="add-food-dashboard.php" class="add-food-button">Add Food</a>

                <!-- Lunch Table -->
                <h3 class="meal-heading">Lunch</h3>
                <table class="calories-tables">
                    <thead>
                        <tr>
                            <th>Food</th>
                            <th>Calories</th>
                            <th>Amount (g)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($lunchFoods) > 0) {
                            foreach ($lunchFoods as $index => $foodRow) {
                                echo '<tr>';
                                echo '<td class="small-font">' . htmlspecialchars($foodRow['food_name']) . '</td>';
                                echo '<td class="small-font">' . htmlspecialchars($foodRow['food_calories']) . '</td>';
                                echo '<td class="small-font">' . htmlspecialchars($foodRow['food_amount']) . '</td>';
                                echo '<td>
                                        <form method="POST">
                                            <input type="hidden" name="delete_type" value="lunch">
                                            <input type="hidden" name="delete_index" value="' . $index . '">
                                            <button type="submit" class="delete-button">Remove</button>
                                        </form>
                                      </td>';
                                echo '</tr>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4" class="small-font" style="text-align: center;" >No lunches logged yet.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <a href="add-food-dashboard.php" class="add-food-button">Add Food</a>

                <!-- Dinner Table -->
                <h3 class="meal-heading">Dinner</h3>
                <table class="calories-tables">
                    <thead>
                        <tr>
                            <th>Food</th>
                            <th>Calories</th>
                            <th>Amount (g)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($dinnerFoods) > 0) {
                            foreach ($dinnerFoods as $index => $foodRow) {
                                echo '<tr>';
                                echo '<td class="small-font">' . htmlspecialchars($foodRow['food_name']) . '</td>';
                                echo '<td class="small-font">' . htmlspecialchars($foodRow['food_calories']) . '</td>';
                                echo '<td class="small-font">' . htmlspecialchars($foodRow['food_amount']) . '</td>';
                                echo '<td>
                                        <form method="POST">
                                            <input type="hidden" name="delete_type" value="dinner">
                                            <input type="hidden" name="delete_index" value="' . $index . '">
                                            <button type="submit" class="delete-button">Remove</button>
                                        </form>
                                      </td>';
                                echo '</tr>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4" class="small-font" style="text-align: center;" >No dinners logged yet.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <a href="add-food-dashboard.php" class="add-food-button">Add Food</a>

                <!-- Snacks Table -->
                <h3 class="meal-heading">Snacks</h3>
                <table class="calories-tables">
                    <thead>
                        <tr>
                            <th>Food</th>
                            <th>Calories</th>
                            <th>Amount (g)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($snackFoods) > 0) {
                            foreach ($snackFoods as $index => $foodRow) {
                                echo '<tr>';
                                echo '<td class="small-font">' . htmlspecialchars($foodRow['food_name']) . '</td>';
                                echo '<td class="small-font">' . htmlspecialchars($foodRow['food_calories']) . '</td>';
                                echo '<td class="small-font">' . htmlspecialchars($foodRow['food_amount']) . '</td>';
                                echo '<td>
                                        <form method="POST">
                                            <input type="hidden" name="delete_type" value="snack">
                                            <input type="hidden" name="delete_index" value="' . $index . '">
                                            <button type="submit" class="delete-button">Remove</button>
                                        </form>
                                      </td>';
                                echo '</tr>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4" class="small-font" style="text-align: center;" >No snacks logged yet.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <a href="add-food-dashboard.php" class="add-food-button">Add Food</a>
            </div>

            <!-- Exercise Log -->
            <div class="exercise-info">
                <h3 class="meal-heading">Logged Exercises</h3>
                <table class="calories-tables">
                    <thead>
                        <tr>
                            <th>Exercise</th>
                            <th>Calories Burned</th>
                            <th>Duration (min)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($exercises) > 0) {
                            foreach ($exercises as $index => $exerciseRow) {
                                echo '<tr>';
                                echo '<td class="small-font">' . htmlspecialchars($exerciseRow['ex_name']) . '</td>';
                                echo '<td class="small-font">' . htmlspecialchars($exerciseRow['ex_calories']) . '</td>';
                                echo '<td class="small-font">' . htmlspecialchars($exerciseRow['ex_duration']) . '</td>';
                                echo '<td>
                                        <form method="POST">
                                            <input type="hidden" name="delete_type" value="exercise">
                                            <input type="hidden" name="delete_index" value="' . $index . '">
                                            <button type="submit" class="delete-button">Remove</button>
                                        </form>
                                      </td>';
                                echo '</tr>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4" class="small-font">No exercises logged yet.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <a href="add-exercise-dashboard.php" class="add-food-button">Add Exercise</a>
            </div>

        </section>
    </main>

    <footer>
        <p>&copy; 2024 EatingHabitPal. All rights reserved.</p>
    </footer>
</body>

</html>