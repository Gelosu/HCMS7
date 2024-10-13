<?php
header('Content-Type: application/json');
include '../connect.php';

// Initialize response array
$response = array();

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if medId is provided
    if (isset($_POST['medId'])) {
        $medId = $_POST['medId'];

        // Step 1: Fetch medicine data from inv_meds before deleting
        $fetchSql = "SELECT * FROM inv_meds WHERE med_id = ?";
        if ($fetchStmt = $conn->prepare($fetchSql)) {
            $fetchStmt->bind_param("i", $medId);
            $fetchStmt->execute();
            $result = $fetchStmt->get_result();
            $medicine = $result->fetch_assoc();

            if ($medicine) {
                // Step 2: Insert the data into the a_meds table (archiving)
                $insertSql = "INSERT INTO a_meds (meds_num, meds_name, meds_dcrptn, stock_exp, status, stck_avail) 
                              VALUES (?, ?, ?, ?, 'Archived', ?)";
                if ($insertStmt = $conn->prepare($insertSql)) {
                    $insertStmt->bind_param("ssssi", 
                        $medicine['meds_number'], 
                        $medicine['meds_name'], 
                        $medicine['med_dscrptn'], 
                        $medicine['stock_exp'], 
                        $medicine['stock_avail']
                    );

                    if ($insertStmt->execute()) {
                        // Step 3: Delete the data from inv_meds table after archiving
                        $deleteSql = "DELETE FROM inv_meds WHERE med_id = ?";
                        if ($deleteStmt = $conn->prepare($deleteSql)) {
                            $deleteStmt->bind_param("i", $medId);
                            
                            if ($deleteStmt->execute()) {
                                if ($deleteStmt->affected_rows > 0) {
                                    // Fetch the updated list of medicines
                                    $result = $conn->query("SELECT * FROM inv_meds");

                                    if ($result) {
                                        $medicines = $result->fetch_all(MYSQLI_ASSOC);
                                        $response['medicines'] = $medicines; // Include updated medicines
                                    } else {
                                        $response['success'] = false;
                                        $response['message'] = 'Error fetching updated medicines list: ' . $conn->error;
                                    }

                                    // Fetch the latest archived medicines
                                    $archivedResult = $conn->query("SELECT * FROM a_meds ORDER BY id DESC"); // Adjust 'id' based on your primary key
                                    if ($archivedResult) {
                                        $archivedMedicines = $archivedResult->fetch_all(MYSQLI_ASSOC);
                                        $response['archivedData'] = $archivedMedicines; // Include archived data in the response
                                    } else {
                                        $response['success'] = false;
                                        $response['message'] = 'Error fetching archived medicines: ' . $conn->error;
                                    }

                                    $response['success'] = true;
                                    $response['message'] = 'Medicine archived and deleted successfully';
                                } else {
                                    $response['success'] = false;
                                    $response['message'] = 'No records found to delete';
                                }
                            } else {
                                $response['success'] = false;
                                $response['message'] = 'Error deleting record: ' . $deleteStmt->error;
                            }

                            $deleteStmt->close();
                        } else {
                            $response['success'] = false;
                            $response['message'] = 'Error preparing delete statement: ' . $conn->error;
                        }
                    } else {
                        $response['success'] = false;
                        $response['message'] = 'Error archiving record: ' . $insertStmt->error;
                    }

                    $insertStmt->close();
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Error preparing insert statement: ' . $conn->error;
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'Medicine not found';
            }

            $fetchStmt->close();
        } else {
            $response['success'] = false;
            $response['message'] = 'Error preparing fetch statement: ' . $conn->error;
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'No medicine ID provided';
    }

    $conn->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
}

// Return JSON response
echo json_encode($response);
?>
