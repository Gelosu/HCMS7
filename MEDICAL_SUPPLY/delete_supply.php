<?php
header('Content-Type: application/json');
include '../connect.php';

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the supply ID from POST request
    $medSupId = $_POST['medSupId'];

    // Initialize response array
    $response = [];

    // Prepare SQL to fetch the record that will be deleted
    $fetchSql = "SELECT sup_id, prod_name, stck_expired, stck_avl FROM inv_medsup WHERE med_supId = ?";
    
    if ($fetchStmt = $conn->prepare($fetchSql)) {
        $fetchStmt->bind_param("i", $medSupId);
        $fetchStmt->execute();
        $result = $fetchStmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch the supply record to be deleted
            $supply = $result->fetch_assoc();

            // Prepare SQL insert query for archiving
            $insertSql = "INSERT INTO a_medsup (supplyid, prdctname, expdate, status, stck_avail) VALUES (?, ?, ?, 'Archived', ?)";
            if ($insertStmt = $conn->prepare($insertSql)) {
                $insertStmt->bind_param("ssss", $supply['sup_id'], $supply['prod_name'], $supply['stck_expired'], $supply['stck_avl']);

                // Insert the record into the archive table
                if ($insertStmt->execute()) {
                    // Prepare SQL delete query with placeholder
                    $deleteSql = "DELETE FROM inv_medsup WHERE med_supId = ?";
                    if ($deleteStmt = $conn->prepare($deleteSql)) {
                        $deleteStmt->bind_param("i", $medSupId);
                        
                        if ($deleteStmt->execute()) {
                            // After deletion, fetch the updated list of supplies
                            $fetchSuppliesSql = "SELECT * FROM inv_medsup";
                            $suppliesResult = $conn->query($fetchSuppliesSql);

                            $supplies = [];
                            if ($suppliesResult->num_rows > 0) {
                                while ($row = $suppliesResult->fetch_assoc()) {
                                    $supplies[] = $row;
                                }
                            }

                            // Set success response
                            $response['success'] = true;
                            $response['message'] = "Record archived and deleted successfully";
                            $response['supplies'] = $supplies;
                        } else {
                            // Error during delete query execution
                            $response['success'] = false;
                            $response['message'] = "Error deleting record: " . $deleteStmt->error;
                        }

                        $deleteStmt->close();
                    } else {
                        // Error preparing the delete statement
                        $response['success'] = false;
                        $response['message'] = "Error preparing delete statement: " . $conn->error;
                    }
                } else {
                    // Error during archive insertion
                    $response['success'] = false;
                    $response['message'] = "Error archiving record: " . $insertStmt->error;
                }

                $insertStmt->close();
            } else {
                // Error preparing the insert statement
                $response['success'] = false;
                $response['message'] = "Error preparing insert statement: " . $conn->error;
            }
        } else {
            // No record found with the given medSupId
            $response['success'] = false;
            $response['message'] = "No record found with the provided ID";
        }

        $fetchStmt->close();
    } else {
        // Error preparing the fetch statement
        $response['success'] = false;
        $response['message'] = "Error preparing fetch statement: " . $conn->error;
    }

    $conn->close();
} else {
    // Invalid request method
    $response['success'] = false;
    $response['message'] = "Invalid request method";
}

// Return JSON response
echo json_encode($response);
?>
