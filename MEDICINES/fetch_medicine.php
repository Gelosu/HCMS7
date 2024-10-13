<?php
include '../connect.php';

header('Content-Type: application/json'); // Ensure the response is in JSON format

// Initialize response array
$response = [];

// Prepare SQL query to fetch all medicine records
$sql = "SELECT * FROM inv_meds";
$result = $conn->query($sql);

if ($result) {
    $medicines = [];
    $today = date('Y-m-d'); // Get today's date in Y-m-d format

    while ($row = $result->fetch_assoc()) {
        // Format the expiration date for display
        $expirationDate = new DateTime($row["stock_exp"]);
        $formattedExpirationDate = $expirationDate->format('F j, Y'); // For display

        // Add formatted expiration date to the row
        $row["stock_exp"] = $formattedExpirationDate;
        $medicines[] = $row;

        // Check if the medicine is expired or expiring today
        if ($expirationDate->format('Y-m-d') <= $today) {
            // Archive the medicine before deleting
            $insertSql = "INSERT INTO a_meds (meds_num, meds_name, meds_dcrptn, stock_exp, status, stck_avail) 
                          VALUES (?, ?, ?, ?, 'Expired', ?)";
            if ($insertStmt = $conn->prepare($insertSql)) {
                $insertStmt->bind_param("ssssi", 
                    $row['meds_number'], 
                    $row['meds_name'], 
                    $row['med_dscrptn'], 
                    $row['stock_exp'], 
                    $row['stock_avail']
                );

                if ($insertStmt->execute()) {
                    // Delete the medicine from inv_meds
                    $deleteSql = "DELETE FROM inv_meds WHERE med_id = ?";
                    if ($deleteStmt = $conn->prepare($deleteSql)) {
                        $deleteStmt->bind_param("i", $row['med_id']); // Assume 'med_id' is available in $row

                        if ($deleteStmt->execute()) {
                            if ($deleteStmt->affected_rows > 0) {
                                // Successfully deleted
                                // You can log this or update the response as needed
                            } else {
                                $response['message'] = 'No records found to delete for med_id ' . $row['med_id'];
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
        }
    }

    // Set success response with the medicine records
    $response['success'] = true;
    $response['data'] = $medicines; // Return all medicine records
} else {
    // Error fetching the records
    $response['success'] = false;
    $response['message'] = "Error fetching medicines list: " . $conn->error;
}

$conn->close();

// Return JSON response
echo json_encode($response);
?>
