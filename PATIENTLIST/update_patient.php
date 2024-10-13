<?php
include '../connect.php'; // Include database connection

header('Content-Type: application/json'); // Set content type to JSON

$response = array(); // Initialize response array

// Check if $conn is valid before proceeding
if ($conn->connect_error) {
    $response['error'] = "Connection failed: " . $conn->connect_error;
    echo json_encode($response);
    exit();
}

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $patient_id = $_POST['patientId']; // Updated to match form field name
    $name = $_POST['name'];
    $bday = $_POST['birthday']; // Updated to match form field name
    $address = $_POST['address'];
    $contact_number = $_POST['contactNumber']; // New field for contact number
    $contact_person = $_POST['contactPerson']; // Updated to match form field name
    $contact_person_number = $_POST['contactPersonNumber']; // New field for contact person number
    $type = $_POST['type'];

    // Calculate the age dynamically based on the birthday
    $birthYear = (int) date('Y', strtotime($bday)); // Extract the birth year from 'bday'
    $currentYear = (int) date('Y'); // Get the current year
    $age = $currentYear - $birthYear; // Calculate the age

    // Prepare and execute update query
    $stmt = $conn->prepare("UPDATE patient SET p_name=?, p_age=?, p_bday=?, p_address=?, p_contnum=?, p_contper=?, p_contnumper=?, p_type=? WHERE p_id=?");
    $stmt->bind_param("ssssssssi", $name, $age, $bday, $address, $contact_number, $contact_person, $contact_person_number, $type, $patient_id);

    if ($stmt->execute()) {
        // Fetch the latest patient data
        $result = $conn->query("SELECT * FROM patient");
        $patients = array();
        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }
        $response['success'] = true;
        $response['message'] = 'Patient record updated successfully';
        $response['patients'] = $patients; // Add patients data to response
    } else {
        $response['error'] = 'Error updating record: ' . $conn->error;
    }

    // Close statement
    $stmt->close();
} else {
    $response['error'] = 'Invalid request method';
}

// Close connection
$conn->close();

// Output the response in JSON format
echo json_encode($response);
?>
