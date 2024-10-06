<?php
header('Content-Type: application/json');
$pdo = new PDO('mysql:host=localhost;dbname=sleep_tracker', 'root', '');


// Check if the request is POST and the action parameter is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
	$action = $_POST['action'];

	// Call the appropriate function based on the action
	switch ($action) {
		case 'save_sleep_entry':
			saveSleepEntry($pdo);
			break;

			// Add more cases here for different functions, e.g.:
		case 'getSleepData':
			getSleepData($pdo);
			break;
		case 'update_sleep_entry':
			updateSleepEntry($pdo);
			break;

		case 'delete_sleep_entry':
			deleteSleepEntry($pdo);
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
function saveSleepEntry($pdo)
{
	try {
		$date = $_POST['date'];
		$hours = $_POST['hours'];
		$minutes = $_POST['minutes'];



		// Validate the inputs
		if (!empty($date) && is_numeric($hours) && is_numeric($minutes)) {
			// Check if the date already exists
			$stmt = $pdo->prepare("SELECT COUNT(*) FROM sleep_diary WHERE date = ?");
			$stmt->execute([$date]);
			$count = $stmt->fetchColumn();

			if ($count > 0) {
				echo json_encode(['success' => false, 'message' => 'Entry for this date already exists']);
				return; // Exit the function if the date exists
			}

			// Insert new entry
			$stmt = $pdo->prepare("INSERT INTO sleep_diary (date, hours, minutes) VALUES (?, ?, ?)");
			$result = $stmt->execute([$date, $hours, $minutes]);

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


function getSleepData($pdo)
{
	header('Content-Type: application/json');
	try {
		// Query to retrieve the sleep data
		$stmt = $pdo->prepare("SELECT date, hours, minutes FROM sleep_diary ORDER BY date ASC");
		$stmt->execute();
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

function updateSleepEntry($pdo)
{
	try {
		$date = $_POST['date'];
		$hours = $_POST['hours'];
		$minutes = $_POST['minutes'];

		// Validate the inputs
		if (!empty($date) && is_numeric($hours) && is_numeric($minutes)) {
			// Update entry
			$stmt = $pdo->prepare("UPDATE sleep_diary SET hours = ?, minutes = ? WHERE date = ?");
			$result = $stmt->execute([$hours, $minutes, $date]);

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

function deleteSleepEntry($pdo)
{
	try {
		$date = $_POST['date'];

		// Validate the input
		if (!empty($date)) {
			// Delete entry
			$stmt = $pdo->prepare("DELETE FROM sleep_diary WHERE date = ?");
			$result = $stmt->execute([$date]);

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
