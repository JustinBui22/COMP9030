<?php


header('Content-Type: application/json');
$conn = new PDO('mysql:host=localhost;dbname=comp9030', 'root', '');


// Check if the request is POST and the action parameter is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
	$action = $_POST['action'];

	// Call the appropriate function based on the action
	switch ($action) {
		case 'save_sleep_entry':
			saveSleepEntry($conn);
			break;

			// Add more cases here for different functions, e.g.:
		case 'getSleepData':
			getSleepData($conn);
			break;
		case 'update_sleep_entry':
			updateSleepEntry($conn);
			break;

		case 'delete_sleep_entry':
			deleteSleepEntry($conn);
			break;


		default:
			echo json_encode(['success' => false, 'message' => 'Invalid action']);
			break;
	}
} else {
	// Invalid request
	echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

// Function to save a sleep entry
function saveSleepEntry($conn)
{
	try {
		$date = $_POST['date'];
		$hours = $_POST['hours'];
		$minutes = $_POST['minutes'];
		$user_id=$_POST['user_id'];



		// Validate the inputs
		if (!empty($date) && is_numeric($hours) && is_numeric($minutes)) {
			// Check if the date already exists
			$stmt = $conn->prepare("SELECT COUNT(*) FROM sleep_diary WHERE date = ? and user_id=?");
			$stmt->execute([$date,$user_id]);
			$count = $stmt->fetchColumn();

			if ($count > 0) {
				echo json_encode(['success' => false, 'message' => 'Entry for this date already exists']);
				return; // Exit the function if the date exists
			}

			// Insert new entry
			$stmt = $conn->prepare("INSERT INTO sleep_diary (date, hours, minutes,user_id) VALUES (?, ?,?, ?)");
			$result = $stmt->execute([$date, $hours, $minutes,$user_id]);

			if ($result) {
				echo json_encode(['success' => true, 'message' => 'Sleep entry saved']);
			} else {
				echo json_encode(['success' => false, 'message' => 'Failed to save entry']);
			}
		} else {
			echo json_encode(['success' => false, 'message' => 'Invalid input data']);
		}
	} catch (PDOException $e) {
		echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
	} catch (Exception $e) {
		echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
	}
}


function getSleepData($conn)
{
	header('Content-Type: application/json');
	try {
		$user_id = $_POST['user_id'];


$stmt = $conn->prepare("SELECT date, hours, minutes FROM sleep_diary WHERE user_id = ? ORDER BY date ASC");

// Execute the statement
$stmt->execute([$user_id]);

// Fetch all the sleep data
$sleepData = $stmt->fetchAll(PDO::FETCH_ASSOC);


		// Calculate the total hours and minutes from sleep data
		$totalHours = 0;
		$totalMinutes = 0;
		$totalEntries = count($sleepData);

		foreach ($sleepData as $entry) {
			$totalHours += (int)$entry['hours'];    // Cast to int for safety
			$totalMinutes += (int)$entry['minutes']; // Cast to int for safety
		}

		// Convert total minutes to hours and minutes
		$totalHours += floor($totalMinutes / 60);
		$totalMinutes = $totalMinutes % 60;

		// Calculate average hours and minutes
		$averageHours = $totalEntries > 0 ? floor($totalHours / $totalEntries) : 0;
		$averageMinutes = $totalEntries > 0 ? floor($totalMinutes / $totalEntries) : 0;

		// Return sleep data and averages
		echo json_encode([
			'success' => true,
			'sleepData' => $sleepData,
			'averageHours' => $averageHours,
			'averageMinutes' => $averageMinutes
		]);
	} catch (Exception $e) {
		// Handle any exceptions and return a JSON error response
		echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
	}
}

function updateSleepEntry($conn)
{
	try {
		$date = $_POST['date'];
		$hours = $_POST['hours'];
		$minutes = $_POST['minutes'];
		$user_id= $_POST['user_id'];

		// Validate the inputs
		if (!empty($date) && is_numeric($hours) && is_numeric($minutes)) {
			// Update entry
			$stmt = $conn->prepare("UPDATE sleep_diary SET hours = ?, minutes = ? WHERE date = ? and user_id=?");
			$result = $stmt->execute([$hours, $minutes, $date,$user_id]);

			if ($result) {
				echo json_encode(['success' => true, 'message' => 'Sleep entry updated']);
			} else {
				echo json_encode(['success' => false, 'message' => 'Failed to update entry']);
			}
		} else {
			echo json_encode(['success' => false, 'message' => 'Invalid input data']);
		}
	} catch (PDOException $e) {
		echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
	} catch (Exception $e) {
		echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
	}
}

function deleteSleepEntry($conn)
{
	try {
		$date = $_POST['date'];
		$user_id= $_POST['user_id'];

		// Validate the input
		if (!empty($date)) {
			// Delete entry
			$stmt = $conn->prepare("DELETE FROM sleep_diary WHERE date = ? and user_id=?");
			$result = $stmt->execute([$date,$user_id]);

			if ($result) {
				echo json_encode(['success' => true, 'message' => 'Sleep entry deleted']);
			} else {
				echo json_encode(['success' => false, 'message' => 'Failed to delete entry']);
			}
		} else {
			echo json_encode(['success' => false, 'message' => 'Invalid input data']);
		}
	} catch (PDOException $e) {
		echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
	} catch (Exception $e) {
		echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
	}
}
