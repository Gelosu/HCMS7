<?php
include '../connect.php';

// Get current date and date 7 days from now
$currentDate = new DateTime();
$endDate = new DateTime();
$endDate->modify('+7 days');

// Format dates for binding
$currentDateFormatted = $currentDate->format('Y-m-d H:i:s');
$endDateFormatted = $endDate->format('Y-m-d H:i:s');

// Fetch upcoming events from the database
$sql = "SELECT * FROM events WHERE datetime BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die(json_encode(['error' => 'MySQL prepare error: ' . $conn->error]));
}

$stmt->bind_param("ss", $currentDateFormatted, $endDateFormatted);
$stmt->execute();
$result = $stmt->get_result();

// Store events
$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

// Close the connection
$stmt->close();
$conn->close();

// Return events as JSON
echo json_encode($events);
?>
