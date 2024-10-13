<?php
include '../connect.php'; // Adjust the path as necessary

header('Content-Type: application/json'); // Set the content type to JSON

$response = array();

$sql = "SELECT * FROM a_meds"; // Query to fetch archived medicines
$result = $conn->query($sql);

if ($result) {
    $medicines = array();
    
    while ($row = $result->fetch_assoc()) {
        $medicines[] = $row; // Add each row to the medicines array
    }

    $response['success'] = true;
    $response['data'] = $medicines; // Attach medicines data to the response
} else {
    $response['success'] = false;
    $response['message'] = "Error fetching data: " . $conn->error; // Add error message if needed
}

$conn->close(); // Close the database connection
echo json_encode($response); // Return JSON response
?>
