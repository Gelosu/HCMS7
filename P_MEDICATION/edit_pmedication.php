<?php
include '../connect.php';  // Database connection

header('Content-Type: application/json');  // Set response to JSON format

$response = array();  // Initialize the response array

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Print POST data for debugging
    error_log(print_r($_POST, true));

    // Get the POST data
    $medicationId = $_POST['editMedicationId'] ?? '';  // The medication ID
    $medicationPatientName = $_POST['editMedicationPatientName'] ?? '';  // The patient's name
    $medicines = $_POST['editMedicines'] ?? [];  // Array of medicine IDs from POST
    $amounts = $_POST['editAmount'] ?? [];  // Array of corresponding amounts
    $originalAmounts = $_POST['originalAmount'] ?? [];  // Array of original amounts
    $medicationHealthWorker = $_POST['editMedicationHealthWorker'] ?? ''; // Removed datetime

    // Validate the medication ID
    if (empty($medicationId)) {
        $response['success'] = false;
        $response['error'] = 'Medication ID is missing.';
        echo json_encode($response);
        exit();
    }

    // Initialize an array to store the medicines and their amounts
    $medicationDetails = [];

    // Fetch original medication amounts from `p_medication` table
    $originalMedicationsSql = "SELECT p_medication FROM p_medication WHERE id = ?";
    if ($stmt = $conn->prepare($originalMedicationsSql)) {
        $stmt->bind_param("s", $medicationId);
        $stmt->execute();
        $stmt->bind_result($originalMedicationsJson);
        $stmt->fetch();
        $stmt->close();

        $originalMedications = json_decode($originalMedicationsJson, true);
        $originalAmountsMap = [];
        foreach ($originalMedications as $item) {
            $originalAmountsMap[$item['name']] = $item['amount'];
        }
    } else {
        $response['success'] = false;
        $response['error'] = 'Error fetching original medication data: ' . $conn->error;
        echo json_encode($response);
        exit();
    }

    // Process each medicine entry
    foreach ($medicines as $index => $med_id) {
        // Prepare SQL to fetch medicine name
        $medSql = "SELECT meds_name FROM inv_meds WHERE med_id = ?";
        if ($medStmt = $conn->prepare($medSql)) {
            $medStmt->bind_param("s", $med_id);  // Bind the medicine ID
            $medStmt->execute();
            $medStmt->bind_result($meds_name);
            $medStmt->fetch();
            $medStmt->close();

            // Get the corresponding amount from POST (or default to 0 if not found)
            $amount = isset($amounts[$index]) ? (int)$amounts[$index] : 0;
            $originalAmount = isset($originalAmountsMap[$meds_name]) ? (int)$originalAmountsMap[$meds_name] : 0;

            // Store medicine name and amount in the medication details array
            $medicationDetails[] = [
                'name' => $meds_name,  // Store the fetched medicine name
                'amount' => $amount    // Store the corresponding amount
            ];

            // Determine if the medicine is existing or new
            if ($meds_name) {
                // Existing medicine
                if ($amount > $originalAmount) {
                    // New amount is greater, decrease stock_avail and increase stock_out
                    $stockChange = $amount - $originalAmount;
                    $updateSql = "
                        UPDATE inv_meds 
                        SET 
                            stock_avail = stock_avail - ?, 
                            stock_out = stock_out + ?
                        WHERE med_id = ?
                    ";
                } else {
                    // New amount is less or equal, increase stock_avail and decrease stock_out
                    $stockChange = $originalAmount - $amount;
                    $updateSql = "
                        UPDATE inv_meds 
                        SET 
                            stock_avail = stock_avail + ?, 
                            stock_out = stock_out - ?
                        WHERE med_id = ?
                    ";
                }

                // Prepare and execute the update statement
                if ($updateStmt = $conn->prepare($updateSql)) {
                    $updateStmt->bind_param("iis", $stockChange, $stockChange, $med_id);
                    $updateStmt->execute();
                    $updateStmt->close();
                } else {
                    $response['success'] = false;
                    $response['error'] = 'Error preparing update query for inv_meds: ' . $conn->error;
                    echo json_encode($response);
                    exit();
                }
            } else {
                // New medicine not found in the database
                $insertSql = "
                    INSERT INTO inv_meds (med_id, meds_name, stock_avail, stock_out)
                    VALUES (?, ?, ?, ?)
                ";
                // Assuming you want to initialize stock_avail and stock_out for new entries
                if ($insertStmt = $conn->prepare($insertSql)) {
                    $initialStock = $amount;
                    $insertStmt->bind_param("ssii", $med_id, $meds_name, $initialStock, 0);
                    $insertStmt->execute();
                    $insertStmt->close();
                } else {
                    $response['success'] = false;
                    $response['error'] = 'Error preparing insert query for inv_meds: ' . $conn->error;
                    echo json_encode($response);
                    exit();
                }
            }
        } else {
            // Handle SQL preparation error
            $response['success'] = false;
            $response['error'] = 'Error preparing medication name query: ' . $conn->error;
            echo json_encode($response);
            exit();
        }
    }

    // Convert the medication details array to a JSON string for storage
    $medicationJson = json_encode($medicationDetails);

    // Prepare the SQL query to update the existing medication record
    $sql = "UPDATE p_medication 
            SET p_medpatient = ?, p_medication = ?, a_healthworker = ?, datetime = NOW() 
            WHERE id = ?";

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameters
        $stmt->bind_param("ssss", $medicationPatientName, $medicationJson, $medicationHealthWorker, $medicationId);

        // Execute the statement and check if it was successful
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Patient medication updated successfully.';

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
        } else {
            $response['success'] = false;
            $response['error'] = 'Error updating medication: ' . $stmt->error;
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
