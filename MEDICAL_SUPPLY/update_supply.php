<?php
header('Content-Type: application/json');
include '../connect.php';

// Initialize response array
$response = array();

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if required POST variables are set and not empty
    if (
        isset($_POST['supplyId'], $_POST['supplyId2'], $_POST['supplyName'], $_POST['stockIn2'], $_POST['stockExpired2'], $_POST['stockAvailable2']) && 
        !empty($_POST['supplyId']) && 
        !empty($_POST['supplyId2']) &&  
        !empty($_POST['supplyName']) && 
        !empty($_POST['stockIn2']) && 
        !empty($_POST['stockExpired2']) && 
        !empty($_POST['stockAvailable2'])
    ) {
        // Retrieve form data and sanitize inputs
        $medSupId = (int) $_POST['supplyId']; // Ensure it's an integer for unique ID
        $supId = trim($_POST['supplyId2']); // The input field for supply_id
        $supplyName = trim($_POST['supplyName']);
        $stockIn = (int) $_POST['stockIn2']; // Ensure it's an integer
        $stockExpired = $_POST['stockExpired2']; // Ensure this is a valid date format (Y-m-d)
        $stockAvailable = (int) $_POST['stockAvailable2']; // Ensure it's an integer

        // Get today's date
        $today = date('Y-m-d');

        // Check if the stock is expired
        if ($stockExpired <= $today) {
            // Archive the expired supply
            $insertArchiveSql = "INSERT INTO a_medsup (supplyid, prdctname, expdate, status, stck_avail) 
                                 SELECT sup_id, prod_name, stck_expired, 'Expired', stck_avl FROM inv_medsup WHERE med_supId = ?";
            if ($archiveStmt = $conn->prepare($insertArchiveSql)) {
                $archiveStmt->bind_param("i", $medSupId);
                if ($archiveStmt->execute()) {
                    // Delete from the inv_medsup table after archiving
                    $deleteSql = "DELETE FROM inv_medsup WHERE med_supId = ?";
                    if ($deleteStmt = $conn->prepare($deleteSql)) {
                        $deleteStmt->bind_param("i", $medSupId);
                        if ($deleteStmt->execute()) {
                            // Fetch updated active supplies
                            $activeSuppliesResult = $conn->query("SELECT * FROM inv_medsup");
                            $supplies = $activeSuppliesResult ? $activeSuppliesResult->fetch_all(MYSQLI_ASSOC) : [];

                            // Fetch latest archived supplies
                            $archivedResult = $conn->query("SELECT * FROM a_medsup ORDER BY id DESC");
                            $archivedSupplies = $archivedResult ? $archivedResult->fetch_all(MYSQLI_ASSOC) : [];

                            $response['success'] = true;
                            $response['message'] = 'Supply archived and deleted successfully.';
                            $response['supplies'] = $supplies; // Active supplies
                            $response['archivedData'] = $archivedSupplies; // Archived supplies
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
                    $response['message'] = 'Error archiving record: ' . $archiveStmt->error;
                }
                $archiveStmt->close();
            } else {
                $response['success'] = false;
                $response['message'] = 'Error preparing archive statement: ' . $conn->error;
            }
        } else {
            // Not expired, just update the data in inv_medsup
            $updateSql = "UPDATE inv_medsup SET 
                          sup_id = ?,  
                          prod_name = ?, 
                          stck_in = ?, 
                          stck_expired = ?, 
                          stck_avl = ? 
                          WHERE med_supId = ?";
            if ($stmt = $conn->prepare($updateSql)) {
                // Bind parameters to the statement
                $stmt->bind_param("ssssis", $supId, $supplyName, $stockIn, $stockExpired, $stockAvailable, $medSupId);

                // Execute the update query and check for success
                if ($stmt->execute()) {
                    // Fetch updated active supplies
                    $activeSuppliesResult = $conn->query("SELECT * FROM inv_medsup");
                    $supplies = $activeSuppliesResult ? $activeSuppliesResult->fetch_all(MYSQLI_ASSOC) : [];

                    $response['success'] = true;
                    $response['message'] = 'Supply updated successfully.';
                    $response['supplies'] = $supplies;  // Active supplies only
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Error updating record: ' . $stmt->error;
                }

                // Close the statement
                $stmt->close();
            } else {
                // Error preparing the statement
                $response['success'] = false;
                $response['message'] = 'Error preparing statement: ' . $conn->error;
            }
        }
    } else {
        // Required fields are missing
        $response['success'] = false;
        $response['message'] = 'Missing or empty required fields.';
    }

    // Close the database connection
    $conn->close();
} else {
    // Invalid request method
    $response['success'] = false;
    $response['message'] = 'Invalid request method.';
}

// Output JSON response
echo json_encode($response);
?>
