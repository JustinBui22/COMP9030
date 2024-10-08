<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise Tracker</title>
    <link rel="stylesheet" href="styles/Patient.css">
</head>
<body>
    <div class="container">
        <h1>Exercise Tracker</h1>
        <?php
            require_once "../../common/inc/dbconn.inc.php";

            // Fetch the first patient from the patients table
            $sql = "SELECT id, name FROM patients LIMIT 1";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $patient = $result->fetch_assoc();
                $patientID = $patient['id'];
                $patientName = $patient['name'];
            } else {
                $patientID = '';
                $patientName = 'No patients found';
            }
        ?>
        <form id="exerciseForm">
            <!-- Patient Info -->
            <div class="form-group">
                <label for="patientName">Patient Name:</label>
                <input type="text" id="patientName" name="patientName" value="<?php echo $patientName; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="patientID">Patient ID:</label>
                <input type="text" id="patientID" name="patientID" value="<?php echo $patientID; ?>" readonly>
            </div>

            <!-- Exercise Details -->
            <div class="table-container">
                <div class="table-column">
                    <h3>Exercise Details</h3>
                    <div class="form-group">
                        <label for="exerciseDate">Date:</label>
                        <input type="date" id="exerciseDate" name="exerciseDate" required onchange="loadDailyData()">
                    </div>
                    <table>
                        <tr>
                            <th>Activity</th>
                            <th>Hours</th>
                        </tr>
                        <tr>
                            <td>Running</td>
                            <td><input type="number" min="0" id="runningHours" name="runningHours"></td>
                        </tr>
                        <tr>
                            <td>Cycling</td>
                            <td><input type="number" min="0" id="cyclingHours" name="cyclingHours"></td>
                        </tr>
                        <tr>
                            <td>Others</td>
                            <td><input type="number" min="0" id="otherHours" name="otherHours"></td>
                        </tr>
                    </table>
                </div>

                <!-- Weekly Records Review -->
                <div class="table-column">
                    <h3>Weekly Records Review (One Week)</h3>
                    <table>
                        <tr>
                            <th>Activity</th>
                            <th>Hours</th>
                        </tr>
                        <tr>
                            <td>Total Running</td>
                            <td><input type="number" readonly id="totalRunning"></td>
                        </tr>
                        <tr>
                            <td>Total Cycling</td>
                            <td><input type="number" readonly id="totalCycling"></td>
                        </tr>
                        <tr>
                            <td>Others</td>
                            <td><input type="number" readonly id="totalOthers"></td>
                        </tr>
                    </table>
                </div>

                <!-- Daily Affirmation -->
                <div class="table-column">
                    <h3>Daily Affirmation</h3>
                    <textarea id="affirmation" name="affirmation" placeholder="Enter your daily affirmation"></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="submitBtn">Submit</button>
        </form>
    </div>

    <script src="scripts/exercise_tracker.js"></script>
</body>
</html>