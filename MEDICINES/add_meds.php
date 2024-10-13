<?php
include '../connect.php';

header('Content-Type: application/json'); // Ensure the response is in JSON format

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $medNumber = $_POST['medNumber'];
    $medName = $_POST['medName'];
    $medDesc = $_POST['medDesc'];
    $stockIn = $_POST['stockIn'];
    
    // Set stockOut to 0 explicitly
    $stockOut = 0; // Always set stockOut to 0
    $stockExp = $_POST['stockExp'];
    $stockAvail = $_POST['stockAvail'];

    // Initialize response array
    $response = [];

    // Get today's date
    $today = date('Y-m-d'); // Format: YYYY-MM-DD

    // Check if the stock expiration date is today or earlier
    if ($stockExp <= $today) {
        // Archive the expired medicine before inserting
        $insertArchiveSql = "INSERT INTO a_meds (meds_num, meds_name, meds_dcrptn, stock_exp, status, stck_avail) 
                             VALUES (?, ?, ?, ?, 'Expired', ?)";
        if ($archiveStmt = $conn->prepare($insertArchiveSql)) {
            $archiveStmt->bind_param("ssssi", 
                $medNumber, 
                $medName, 
                $medDesc, 
                $stockExp, 
                $stockAvail
            );

            if (!$archiveStmt->execute()) {
                $response['success'] = false;
                $response['message'] = "Error archiving expired medicine: " . $archiveStmt->error;
                echo json_encode($response);
                exit();
            }

            $archiveStmt->close();
        } else {
            $response['success'] = false;
            $response['message'] = "Error preparing archive statement: " . $conn->error;
            echo json_encode($response);
            exit();
        }
        
        // No need to insert into inv_meds if expired
        $response['success'] = true;
        $response['message'] = "Expired medicine archived successfully.";
        
        // Fetch the latest archived medicines
        $fetchArchivedSql = "SELECT * FROM a_meds ORDER BY id DESC"; // Adjust 'id' based on your primary key column
        $archivedResult = $conn->query($fetchArchivedSql);

        if ($archivedResult) {
            $archivedMedicines = [];
            while ($row = $archivedResult->fetch_assoc()) {
                $archivedMedicines[] = $row; // Add each archived medicine to the array
            }
            $response['archivedData'] = $archivedMedicines; // Include archived data in the response
        } else {
            $response['success'] = false;
            $response['message'] = "Error fetching archived medicines: " . $conn->error;
        }
        
        echo json_encode($response);
        exit();
    }

    // If the medicine is not expired, proceed to insert into inv_meds
    $sql = "INSERT INTO inv_meds (meds_number, meds_name, med_dscrptn, stock_in, stock_out, stock_exp, stock_avail)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters to the SQL statement
        $stmt->bind_param("sssssss", $medNumber, $medName, $medDesc, $stockIn, $stockOut, $stockExp, $stockAvail);

        if ($stmt->execute()) {
            // After successful insertion, fetch all records from the inv_meds table
            $fetchSql = "SELECT * FROM inv_meds";
            $result = $conn->query($fetchSql);

            if ($result) {
                $medicines = [];
                while ($row = $result->fetch_assoc()) {
                    $medicines[] = $row;
                }

                // Set success response with updated table data
                $response['success'] = true;
                $response['message'] = "Record inserted successfully.";
                $response['data'] = $medicines; // Return all medicine records
            } else {
                // Error fetching the updated list
                $response['success'] = false;
                $response['message'] = "Error fetching updated medicines list: " . $conn->error;
            }
        } else {
            // Error during query execution
            $response['success'] = false;
            $response['message'] = "Error inserting record: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // Error preparing the statement
        $response['success'] = false;
        $response['message'] = "Error preparing statement: " . $conn->error;
    }

    $conn->close();
} else {
    // Invalid request method
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

// Return JSON response
echo json_encode($response);
?>
