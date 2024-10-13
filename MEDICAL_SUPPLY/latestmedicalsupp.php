<?php
include '../connect.php'; // Adjust the path as necessary

header('Content-Type: application/json'); // Ensure the response is in JSON format

// Initialize response array
$response = [];

// Prepare SQL query to fetch all medical supply records
$sql = "SELECT * FROM inv_medsup"; // Change table name if necessary
$result = $conn->query($sql);

if ($result) {
    $supplies = [];
    $today = date('Y-m-d'); // Get today's date in Y-m-d format

    while ($row = $result->fetch_assoc()) {
        // Check if the supply is expired or expiring today
        if ($row["stck_expired"] <= $today) {
            // Archive the supply before deleting
            $insertSql = "INSERT INTO a_medsup (supplyid, prdctname, expdate, stck_avail, status) 
                          VALUES (?, ?, ?, ?, 'Expired')";
            if ($insertStmt = $conn->prepare($insertSql)) {
                // Prepare data for archiving
                $insertStmt->bind_param("sssi", 
                    $row['sup_id'],     // Mapping sup_id to supplyid
                    $row['prod_name'],  // Mapping prod_name to prdctname
                    $row['stck_expired'], // Mapping stck_expired to expdate
                    $row['stck_avl']    // Mapping stck_avl to stck_avail
                );

                if ($insertStmt->execute()) {
                    // Proceed to delete the supply from inv_medsup
                    $deleteSql = "DELETE FROM inv_medsup WHERE med_supId = ?";
                    if ($deleteStmt = $conn->prepare($deleteSql)) {
                        $deleteStmt->bind_param("i", $row['med_supId']); // Using med_supId for deletion

                        if ($deleteStmt->execute()) {
                            if ($deleteStmt->affected_rows > 0) {
                                // Successfully deleted
                                // Optionally log the deletion or add to response
                            } else {
                                // No rows affected means it was not found for deletion
                                $response['message'] = 'No records found to delete for supplyid ' . $row['sup_id'];
                            }
                        } else {
                            // Error during deletion
                            $response['success'] = false;
                            $response['message'] = 'Error deleting record: ' . $deleteStmt->error;
                        }

                        $deleteStmt->close();
                    } else {
                        // Error preparing delete statement
                        $response['success'] = false;
                        $response['message'] = 'Error preparing delete statement: ' . $conn->error;
                    }
                } else {
                    // Error archiving record
                    $response['success'] = false;
                    $response['message'] = 'Error archiving record: ' . $insertStmt->error;
                }

                $insertStmt->close();
            } else {
                // Error preparing insert statement
                $response['success'] = false;
                $response['message'] = 'Error preparing insert statement: ' . $conn->error;
            }
        } else {
            // If not expired, just add to supplies array
            $supplies[] = $row; // Add the supply record to the supplies array if not expired
        }
    }

    // Set success response with the medical supplies records
    $response['success'] = true;
    $response['data'] = $supplies; // Return all medical supplies records
} else {
    // Error fetching the records
    $response['success'] = false;
    $response['message'] = "Error fetching medical supplies list: " . $conn->error;
}

// Close the database connection
$conn->close();

// Return JSON response
echo json_encode($response);
?>
