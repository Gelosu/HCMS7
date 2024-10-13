<?php
include '../connect.php';  // Database connection

header('Content-Type: application/json');  // Set response to JSON format

$response = array();  // Initialize the response array

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the POST data
    $medicationPatientName = $_POST['medicationPatientName'] ?? '';  // The patient's name
    $medicines = $_POST['medicines'] ?? [];  // Array of IDs (both medicines and supplies)
    $amounts = $_POST['amount'] ?? [];  // Array of corresponding amounts
    $dataTypes = $_POST['data_type'] ?? []; // Array of corresponding data types
    $medicationHealthWorker = $_POST['medicationHealthWorker'] ?? '';  // Health worker info

    // Initialize an array to store the medicines and their amounts
    $medicationDetails = [];
    $medicationNames = [];

    // Fetch medication names based on the provided IDs and their types
    foreach ($medicines as $index => $id) {
        $dataType = $dataTypes[$index] ?? '';  // Fetch corresponding data type

        // Log the ID and data type for debugging
        error_log("Processing ID: $id, Data Type: $dataType");  // Log the ID and type

        if ($dataType === 'medicine') {
            // Fetch from inv_meds table
            $medSql = "SELECT meds_name FROM inv_meds WHERE med_id = ?";
            if ($stmt = $conn->prepare($medSql)) {
                $stmt->bind_param("i", $id);  // Assuming med_id is an integer
                $stmt->execute();
                $stmt->bind_result($meds_name);
                if ($stmt->fetch()) {
                    $medicationNames[$id] = $meds_name;  // It's a medicine
                } else {
                    $medicationNames[$id] = 'Unknown Medicine';  // Default if not found
                    error_log("Medicine not found for ID: $id");  // Log not found
                }
                $stmt->close();
            } else {
                $response['success'] = false;
                $response['error'] = 'Error preparing medication name query: ' . $conn->error;
                echo json_encode($response);
                exit();
            }
        } elseif ($dataType === 'supply') {
            // Fetch from inv_medsup table
            $supplySql = "SELECT prod_name FROM inv_medsup WHERE med_supId = ?";
            if ($stmt = $conn->prepare($supplySql)) {
                $stmt->bind_param("i", $id);  // Assuming med_supId is an integer
                $stmt->execute();
                $stmt->bind_result($prod_name);
                if ($stmt->fetch()) {
                    $medicationNames[$id] = $prod_name;  // It's a supply
                } else {
                    $medicationNames[$id] = 'Unknown Supply';  // Default if not found
                    error_log("Supply not found for ID: $id");  // Log not found
                }
                $stmt->close();
            } else {
                $response['success'] = false;
                $response['error'] = 'Error preparing supply name query: ' . $conn->error;
                echo json_encode($response);
                exit();
            }
        } else {
            error_log("Unknown data type for ID: $id");  // Log unknown data type
        }
    }

    // Combine medicines and their corresponding amounts with names, excluding unknown types
    foreach ($medicines as $index => $medicine) {
        $amount = isset($amounts[$index]) ? $amounts[$index] : 0;
        $type = $dataTypes[$index] ?? 'Unknown Type';  // Get the data type for this item

        // Skip items with 'Unknown Type'
        if ($type === 'Unknown Type') {
            error_log("Skipping medication with Unknown Type for ID: $medicine");  // Log skipping
            continue;  // Skip this entry
        }

        $medicationDetails[] = [
            'name' => $medicationNames[$medicine] ?? 'Unknown Item',
            'amount' => $amount,
            'type' => $type  // Include the data type for tracking
        ];  
    }

    // If there are no valid medication details, respond with an error
    if (empty($medicationDetails)) {
        $response['success'] = false;
        $response['error'] = 'No valid medications to add.';
        echo json_encode($response);
        exit();
    }

    // Convert the medication details array to a JSON string for storage
    $medicationJson = json_encode($medicationDetails);

    // Fetch the patient ID from the patient name
    $patientId = null;
    $patientSql = "SELECT p_name FROM patient WHERE p_id = ?";  // Fetch by patient name
    if ($patientStmt = $conn->prepare($patientSql)) {
        $patientStmt->bind_param("s", $medicationPatientName);
        $patientStmt->execute();
        $patientStmt->bind_result($p_id);
        if ($patientStmt->fetch()) {
            $patientId = $p_id;
        }
        $patientStmt->close();
    } else {
        $response['success'] = false;
        $response['error'] = 'Error preparing patient ID query: ' . $conn->error;
        echo json_encode($response);
        exit();
    }

    if ($patientId === null) {
        $response['success'] = false;
        $response['error'] = 'Patient not found.';
        echo json_encode($response);
        exit();
    }

    // Prepare the SQL query to insert a new medication record, using NOW() for datetime
    $sql = "INSERT INTO p_medication (p_medpatient, p_medication, datetime, a_healthworker) 
            VALUES (?, ?, NOW(), ?)";

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $patientId, $medicationJson, $medicationHealthWorker);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Patient medication added successfully.';

            // Fetch updated medication data to return
            $fetchSql = "
                SELECT 
                    id, 
                    p_medpatient AS patient_name, 
                    p_medication, 
                    datetime AS date_time, 
                    a_healthworker AS healthworker
                FROM p_medication
            ";
            $result = $conn->query($fetchSql);
            $medications = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Decode JSON data for p_medication
                    $row['p_medication'] = json_decode($row['p_medication'], true);
                    $medications[] = $row;
                }
            }
            $response['data'] = $medications;  // Include medications in the response

            // Update inventory for both supplies and medicines
            foreach ($medicines as $index => $id) {
                $amount = isset($amounts[$index]) ? $amounts[$index] : 0;
                $dataType = $_POST['dataTypes'][$index] ?? '';  // Get the data type for this item

                if ($dataType === 'medicine') {
                    $updateSql = "
                        UPDATE inv_meds 
                        SET 
                            stock_avail = stock_avail - ?, 
                            stock_out = stock_out + ?
                        WHERE med_id = ?
                    ";
                } elseif ($dataType === 'supply') {
                    $updateSql = "
                        UPDATE inv_medsup 
                        SET 
                            stck_avl = stck_avl - ?, 
                            stck_out = stck_out + ?
                        WHERE med_supId = ?
                    ";
                } else {
                    continue;  // Skip unknown types
                }

                if ($updateStmt = $conn->prepare($updateSql)) {
                    $updateStmt->bind_param("iis", $amount, $amount, $id); // Bind parameters
                    if (!$updateStmt->execute()) {
                        $response['success'] = false;
                        $response['error'] = 'Error updating inventory: ' . $updateStmt->error;
                        $updateStmt->close();
                        break;  // Stop further processing if there's an error
                    }
                    $updateStmt->close();
                } else {
                    $response['success'] = false;
                    $response['error'] = 'Error preparing inventory update query: ' . $conn->error;
                    break;  // Stop further processing if there's an error
                }
            }
        } else {
            $response['success'] = false;
            $response['error'] = 'Error inserting medication: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $response['success'] = false;
        $response['error'] = 'Error preparing the statement: ' . $conn->error;
    }
} else {
    $response['success'] = false;
    $response['error'] = 'Invalid request method.';
}

$conn->close();

// Return the response in JSON format
echo json_encode($response);
?>
