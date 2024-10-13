<?php
include '../connect.php'; // Include database connection

header('Content-Type: application/json'); // Set content type to JSON

$response = array(); // Initialize response array

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $p_name = $_POST['p_name'];
    $p_bday = $_POST['p_bday']; // Assuming 'p_bday' is in 'YYYY-MM-DD' format
    $p_address = $_POST['p_address'];
    $p_contnum = $_POST['p_contnum']; // Contact number field
    $p_contper = $_POST['p_contper'];
    $p_contnumper = $_POST['p_contnumper']; // Contact person number field
    $p_type = $_POST['p_type'];

    // Calculate the age dynamically
    $birthYear = (int) date('Y', strtotime($p_bday)); // Extract the birth year from 'p_bday'
    $currentYear = (int) date('Y'); // Get the current year
    $p_age = $currentYear - $birthYear; // Calculate age

    // Prepare and execute the insert query
    $sql = "INSERT INTO patient (p_name, p_age, p_bday, p_address, p_contnum, p_contper, p_contnumper, p_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $p_name, $p_age, $p_bday, $p_address, $p_contnum, $p_contper, $p_contnumper, $p_type);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Patient record added successfully';

        // Retrieve the latest patient records
        $result = $conn->query("SELECT * FROM patient ORDER BY p_id DESC");
        if ($result->num_rows > 0) {
            $patients = array();
            while ($row = $result->fetch_assoc()) {
                $patients[] = $row;
            }
            $response['patients'] = $patients;
        } else {
            $response['patients'] = array();
        }
    } else {
        if ($conn->errno == 1062) {
            $response['error'] = 'The data already exists!';
        } else {
            $response['error'] = 'An error occurred while processing your request. Please try again later.';
        }
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();

// Output the response in JSON format
echo json_encode($response);
?>
