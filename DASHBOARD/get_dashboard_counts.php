<?php
include '../connect.php';

header('Content-Type: application/json');

// Initialize response array
$response = array();

// Query to count total patients
$patientQuery = "SELECT COUNT(*) as total_patients FROM patient";
$patientResult = $conn->query($patientQuery);
if ($patientResult) {
    $row = $patientResult->fetch_assoc();
    $response['total_patients'] = $row['total_patients'];
} else {
    $response['total_patients'] = 0;
}

// Query to count total medicines
$medsQuery = "SELECT COUNT(*) as total_meds FROM inv_meds";
$medsResult = $conn->query($medsQuery);
if ($medsResult) {
    $row = $medsResult->fetch_assoc();
    $response['total_meds'] = $row['total_meds'];
} else {
    $response['total_meds'] = 0;
}

// Query to count total appointments for today
$appointmentsQuery = "SELECT COUNT(*) as total_appointments FROM p_appointment WHERE DATE(datetime) = CURDATE()";
$appointmentsResult = $conn->query($appointmentsQuery);
if ($appointmentsResult) {
    $row = $appointmentsResult->fetch_assoc();
    $response['total_appointments'] = $row['total_appointments'];
} else {
    $response['total_appointments'] = 0;
}

// Query to count total medications
$medicationsQuery = "SELECT COUNT(*) as total_medications FROM inv_medsup";
$medicationsResult = $conn->query($medicationsQuery);
if ($medicationsResult) {
    $row = $medicationsResult->fetch_assoc();
    $response['total_medications'] = $row['total_medications'];
} else {
    $response['total_medications'] = 0;
}

$conn->close();

// Return the counts as JSON
echo json_encode($response);
?>
