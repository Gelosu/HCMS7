<?php
include '../connect.php';

header('Content-Type: application/json'); // Ensure the response is in JSON format

// Initialize response array
$response = array(); 

// Check if the connection was successful
if ($conn->connect_error) {
    $response['error'] = "Connection failed: " . $conn->connect_error;
    echo json_encode($response);
    exit();
}

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    // Retrieve and sanitize form data
    $supplyId = trim($_POST['supplyId2']);
    $supplyName = trim($_POST['supplyName']);
    $stockIn = (int)$_POST['stockIn']; // Cast to integer
    $stockExpired = $_POST['stockExpired']; // This should be in YYYY-MM-DD format
    $stockAvailable = (int)$_POST['stockAvailable']; // Cast to integer

    // Get today's date
    $today = date('Y-m-d'); // Format: YYYY-MM-DD

    // Check if the stock expiration date is today or earlier
    if ($stockExpired <= $today) {
        // Archive the expired supply before inserting
        $insertArchiveSql = "INSERT INTO a_medsup (supplyid, prdctname, expdate, status, stck_avail) 
                             VALUES (?, ?, ?, 'Expired', ?)";

        if ($archiveStmt = $conn->prepare($insertArchiveSql)) {
            $archiveStmt->bind_param("sssi", 
                $supplyId, 
                $supplyName, 
                $stockExpired, 
                $stockAvailable
            );

            if (!$archiveStmt->execute()) {
                $response['success'] = false;
                $response['message'] = "Error archiving expired supply: " . $archiveStmt->error;
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

        // No need to insert into inv_medsup if expired
        $response['success'] = true;
        $response['message'] = "Expired supply archived successfully.";
        
        // Fetch the latest archived supplies
        $fetchArchivedSql = "SELECT * FROM a_medsup ORDER BY id DESC"; // Adjust 'id' based on your primary key column
        $archivedResult = $conn->query($fetchArchivedSql);

        if ($archivedResult) {
            $archivedSupplies = [];
            while ($row = $archivedResult->fetch_assoc()) {
                $archivedSupplies[] = $row; // Add each archived supply to the array
            }
            $response['archivedData'] = $archivedSupplies; // Include archived data in the response
        } else {
            $response['success'] = false;
            $response['message'] = "Error fetching archived supplies: " . $conn->error;
        }

        echo json_encode($response);
        exit();
    }

    // If the supply is not expired, proceed to insert into inv_medsup
    $sql = "INSERT INTO inv_medsup (sup_id, prod_name, stck_in, stck_out, stck_expired, stck_avl)
            VALUES (?, ?, ?, 0, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters to the SQL statement
        $stmt->bind_param("ssssi", $supplyId, $supplyName, $stockIn, $stockExpired, $stockAvailable);

        if ($stmt->execute()) {
            // After successful insertion, fetch all records from the inv_medsup table
            $fetchSql = "SELECT * FROM inv_medsup";
            $result = $conn->query($fetchSql);

            if ($result) {
                $supplies = [];
                while ($row = $result->fetch_assoc()) {
                    $supplies[] = $row;
                }

                // Set success response with updated table data
                $response['success'] = true;
                $response['message'] = "Record inserted successfully.";
                $response['data'] = $supplies; // Return all supplies records
            } else {
                // Error fetching the updated list
                $response['success'] = false;
                $response['message'] = "Error fetching updated supplies list: " . $conn->error;
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
