<?php
include '../connect.php'; // Adjust the path as necessary

header('Content-Type: application/json'); // Set the content type to JSON

$response = array();

// Query to fetch archived medical supplies
$sql = "SELECT * FROM a_medsup"; 
$result = $conn->query($sql);

if ($result) {
    $supplies = array();
    
    while ($row = $result->fetch_assoc()) {
        $supplies[] = $row; // Add each row to the supplies array
    }

    $response['success'] = true;
    $response['data'] = $supplies; // Attach supplies data to the response
} else {
    $response['success'] = false;
    $response['message'] = "Error fetching data: " . $conn->error; // Add error message if needed
}

$conn->close(); // Close the database connection
echo json_encode($response); // Return JSON response
?>
