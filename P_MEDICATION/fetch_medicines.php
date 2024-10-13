<?php
include '../connect.php'; // Include your database connection file

header('Content-Type: application/json'); // Ensure the response is in JSON format

// Initialize arrays for both inv_meds and inv_medsup data
$medicines = [];
$supplies = [];

// Fetch medicines from inv_meds where stock_avail > 0
$medSql = "SELECT med_id, meds_name, stock_avail FROM inv_meds WHERE stock_avail > 0";
$medResult = $conn->query($medSql);

if ($medResult->num_rows > 0) {
    while ($row = $medResult->fetch_assoc()) {
        $medicines[] = $row; // Store each medicine row in the array
    }
}

// Fetch medical supplies from inv_medsup where stck_avl > 0
$supplySql = "SELECT med_supId, sup_id, prod_name, stck_in, stck_out, stck_expired, stck_avl FROM inv_medsup WHERE stck_avl > 0";
$supplyResult = $conn->query($supplySql);

if ($supplyResult->num_rows > 0) {
    while ($row = $supplyResult->fetch_assoc()) {
        $supplies[] = $row; // Store each supply row in the array
    }
}

// Close the connection
$conn->close();

// Return the data as JSON with both medicines and medical supplies
echo json_encode([
    'success' => true,
    'medicines' => $medicines,  // Data from inv_meds
    'supplies' => $supplies     // Data from inv_medsup
]);
?>
