
<!-- MEDICINE INVETORY DITO --> 

<section id="medicine-inventory" class="section">
        <h2>Medicine Inventory</h2>
        
        <div class="search-and-add-container">
        <!-- Search bar container -->
        <div class="search-container">
        <input type="text" id="searchInput" onkeyup="searchTable2(this.value);" placeholder="Search for medicine...">
        </div>

        <!-- Button container -->
        <div class="add-button-container">
            <button onclick="openAddMedicineModal()">Add Medicine</button>
        </div>
    </div>
    <div class="table-container">
    <table id="medTable">
        <thead>
            <tr>
            <th>Medicine Number<div class="resizer2"></div></th>
                <th>Medicine Name<div class="resizer2"></div></th>
                <th>Description<div class="resizer2"></div></th>
                <th>Stock In<div class="resizer2"></div></th>
            
                <th>Expiration Date<div class="resizer2"></div></th>
                <th>Stock Available<div class="resizer2"></div></th>
                <th>Action<div class="resizer2"></div></th>
            </tr>
        </thead>
        <tbody>
        <?php
include 'connect.php';

$sql = "SELECT * FROM inv_meds";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td><div class='cell-content'>" . htmlspecialchars($row["meds_number"]) . "</div></td>";
        echo "<td><div class='cell-content'>" . htmlspecialchars($row["meds_name"]) . "</div></td>";
        echo "<td><div class='cell-content'>" . htmlspecialchars($row["med_dscrptn"]) . "</div></td>";
        echo "<td><div class='cell-content'>" . htmlspecialchars($row["stock_in"]) . "</div></td>";
        
        // Format the expiration date for both display and input
        $expirationDate = new DateTime($row["stock_exp"]);
        $formattedExpirationDateForDisplay = $expirationDate->format('F j, Y'); // For display
        $formattedExpirationDateForInput = $expirationDate->format('Y-m-d'); // For input
        
        // Display in the desired format
        echo "<td><div class='cell-content'>" . $formattedExpirationDateForDisplay . "</div></td>";
        echo "<td><div class='cell-content'>" . htmlspecialchars($row["stock_avail"]) . "</div></td>";
        echo "<td class='action-icons'>";

        echo "<a onclick=\"openEditMed('" . 
            $row["med_id"] . "', '" . 
            addslashes($row["meds_number"]) . "', '" . 
            addslashes($row["meds_name"]) . "', '" . 
            addslashes($row["med_dscrptn"]) . "', '" . 
            htmlspecialchars($row["stock_in"]) . "', '" . 
            $formattedExpirationDateForInput . "', '" .  // Pass the input format
            htmlspecialchars($row["stock_avail"]) . "')\">";

        echo "<img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'></a>";

        echo "<a onclick=\"deleteMedicine('" . $row["med_id"] . "')\">";
        echo "<img src='ARCHIVE.png' alt='Delete' class='delete-btn' style='width: 20px; height: 20px;'></a>";
        
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>No medicines found</td></tr>";
}


$conn->close();
?>

        </tbody>
    </table>
 <!-- Button to open the medicine archive modal -->
<button onclick="openMedicineModal()">View Medicine Archive</button>

</div>

    </section>



       


<!-- Modal for adding new medicine -->
<div id="addMedicineModal" class="modal2">
    <div class="modal-content2">
        <span class="close" onclick="closeAddMedicineModal()">&times;</span>
        <h3>Add New Medicine</h3>
        <form id="addmedicine" onsubmit="submitMedicineForm(event)">

        <label for="medNumber">Medicine Number:</label>
        <input type="text" id="medNumber" name="medNumber" required><br><br>
            <label for="medName">Medicine Name:</label>
            <input type="text" id="medName" name="medName" required><br><br>
            
            <label for="medDesc">Description:</label>
            <input type="text" id="medDesc" name="medDesc" required><br><br>
            
            <label for="stockIn">Stock In:</label>
            <input type="number" id="stockIn" name="stockIn" required><br><br>
            
            
            <label for="stockExp">Expiration Date:</label>
            <input type="date" id="stockExp" name="stockExp" required><br><br>
            
            <label for="stockAvail">Stock Available:</label>
            <input type="number" id="stockAvail" name="stockAvail" required><br><br>
            
            <input type="submit" value="Submit">
        </form>
    </div>
</div>



<!-- Modal for editing medicine -->
<div id="editMedicineModal" class="modal2">
    <div class="modal-content2">
        <span class="close" onclick="closeEditMedModal()">&times;</span>
        <h3>Edit Medicine</h3>
        <form id="editForm2" onsubmit="submitEditMedicineForm(event)">
            <input type="hidden" id="editMedId" name="medId">
            
            <label for="editMedNumber">Medicine Number:</label>
            <input type="text" id="editMedNumber" name="medNumber" required><br><br>
            
            <label for="editMedName">Medicine Name:</label>
            <input type="text" id="editMedName" name="medName" required><br><br>
            
            <label for="editMedDesc">Description:</label>
            <input type="text" id="editMedDesc" name="medDesc" required><br><br>
            
            <label for="editStockIn">Stock In:</label>
            <input type="number" id="editStockIn" name="stockIn" required><br><br>
            
            
            <label for="editStockExp">Expiration Date:</label>
            <input type="date" id="editStockExp" name="stockExp" required><br><br>
            
            <label for="editStockAvail">Stock Available:</label>
            <input type="number" id="editStockAvail" name="stockAvail" required><br><br>
            
            <input type="submit" value="Update">
        </form>
    </div>
</div>


<!-- Medicine Archive Modal -->
<div id="medicineModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeMedicineModal()">&times;</span>
        <h3>Medicine Archive</h3>

        <!-- Search bar for archived medicines -->
        <div class="search-container">
            <input type="text" id="searchMedicineModalInput" onkeyup="searchMedicineTable(this.value);" placeholder="Search archived medicines...">
        </div>

        <!-- Medicine Archive Table -->
        <div class="table-container">
            <table id="medicineModalArchiveTable">
                <thead>
                    <tr>
                        <th>Medicine Number</th>
                        <th>Medicine Name</th>
                        <th>Description</th>
                        <th>Expiration Date</th>
                        <th>Stock Available</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'connect.php';
                    $sql = "SELECT * FROM a_meds";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["meds_num"] . "</td>"; // Medicine Number
                            echo "<td>" . $row["meds_name"] . "</td>"; // Medicine Name
                            echo "<td>" . $row["meds_dcrptn"] . "</td>"; // Description
                            echo "<td>" . $row["stock_exp"] . "</td>"; // Expiration Date
                            echo "<td>" . $row["stck_avail"] . "</td>"; // Stock Available
                            echo "<td>" . $row["status"] . "</td>"; // Status (default: Archived)
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No archived medicines found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>

// Function to update the archived medicine table
function updateMedicineTable2() {
    fetch('MEDICINES/getlatestmedicine.php') // Adjust the path as necessary
        .then(response => response.json())
        .then(result => {
            const tableBody = document.getElementById("medicineModalArchiveTable").getElementsByTagName('tbody')[0];
            tableBody.innerHTML = ""; // Clear existing table rows

            if (result.success && result.data.length > 0) {
                result.data.forEach(medicine => {
                    const row = document.createElement('tr');

                    // Populate the row with medicine data
                    row.innerHTML = `
                        <td>${medicine.meds_num}</td>
                        <td>${medicine.meds_name}</td>
                        <td>${medicine.meds_dcrptn}</td>
                        <td>${medicine.stock_exp}</td>
                        <td>${medicine.stck_avail}</td>
                        <td>${medicine.status}</td>
                    `;
                    
                    tableBody.appendChild(row);
                });
            } else {
                // If no archived medicines are found
                const row = document.createElement('tr');
                row.innerHTML = "<td colspan='6'>No archived medicines found</td>";
                tableBody.appendChild(row);
            }
        })
        .catch(error => console.error('Error fetching archived medicines:', error));
}


    // Function to open the medicine archive modal
function openMedicineModal() {
    updateMedicineTable2();
    document.getElementById('medicineModal').style.display = 'block';
}


// Function to close the medicine archive modal
function closeMedicineModal() {
    document.getElementById('medicineModal').style.display = 'none';
}

// Function to search through the medicine table in the modal
function searchMedicineTable(inputValue) {
    var searchQuery = inputValue.toLowerCase().trim();
    var table = document.getElementById("medicineModalArchiveTable");
    var rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var cells = row.getElementsByTagName('td');
        var rowContainsQuery = false;

        // Check each cell in the row
        for (var j = 0; j < cells.length; j++) {
            var cellValue = cells[j].textContent || cells[j].innerText;
            if (cellValue.toLowerCase().indexOf(searchQuery) > -1) {
                rowContainsQuery = true;
                break;
            }
        }

        // Show or hide the row based on the search query
        if (rowContainsQuery) {
            row.style.display = ''; // Show the row
        } else {
            row.style.display = 'none'; // Hide the row
        }
    }
}




//MEDICINES
var addMedicineModal = document.getElementById("addMedicineModal"); //ADD MEDICINE
    var editMedicineModal =document.getElementById("editMedicineModal") //EDIT MEDICINE

    //MEDICINE

    
    // FUNCTION FOR ADDING MEDICINE
    function submitMedicineForm(event) {
    event.preventDefault(); // Prevent the default form submission behavior

    // Get the form data from the form with ID 'addmedicine'
    var formData = new FormData(document.getElementById('addmedicine'));

    // Send a POST request to the 'add_meds.php' endpoint with the form data
    fetch('MEDICINES/add_meds.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        // Ensure the response is in JSON format
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json(); // Parse the JSON response
    })
    .then(data => {
        console.log('Success:', data);

        // Check if the medicine was archived due to expiration
        if (data.success && data.archivedData) {
            alert('The medicine was archived because it is expired.');
            updateMedicineTable2(); // Fetch and update the latest inv_meds table
        } else if (data.success) {
            // Otherwise, update the medicine table with the new data
            updateMedicineTable(data.data);
        } else {
            // If there is an error in the response, log it
            console.error('Error:', data.message || 'Unknown error occurred.');
        }

        // Close the modal form after successful submission
        closeAddMedicineModal();
        updateDashboard(); // Update the dashboard if necessary
    })
    .catch(error => {
        console.error('Error submitting form:', error);
        alert('Error submitting form: ' + error.message); // Provide feedback to the user
    });
}


    // Function to update the medicine table with new data
    function updateMedicineTable(medicines) {
        var tableBody = document.querySelector('#medTable tbody');
        tableBody.innerHTML = ''; // Clear existing table rows
    
        if (medicines.length > 0) {
            medicines.forEach(med => {
                var row = document.createElement('tr');
                
                // Format the expiration date to "September 5, 2026"
                var expirationDate = new Date(med.stock_exp);
                var options = { year: 'numeric', month: 'long', day: 'numeric' };
                var formattedExpirationDate = expirationDate.toLocaleDateString('en-US', options);
    
                row.innerHTML = `
                    <td>${med.meds_number}</td>
                    <td>${med.meds_name}</td>
                    <td>${med.med_dscrptn}</td>
                    <td>${med.stock_in}</td>
                    <td>${formattedExpirationDate}</td> <!-- Use formatted expiration date -->
                    <td>${med.stock_avail}</td>
                    <td class='action-icons'>
                        <a href='#' class='edit-btn' onclick="openEditMed(
                            '${med.med_id}', 
                            '${med.meds_number}', 
                            '${med.meds_name}', 
                            '${med.med_dscrptn}', 
                            ${med.stock_in}, 
                            '${med.stock_exp}', 
                            ${med.stock_avail}
                        )">
                            <img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'>
                        </a>
                        <a href='#' class='delete-btn' onclick="deleteMedicine(${med.med_id})">
                            <img src='ARCHIVE.png' alt='Delete' style='width: 20px; height: 20px;'>
                        </a>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="8">No medicines found</td></tr>'; // Adjust colspan to match the number of columns
        }
    }
    

    



function closeAddMedicineModal() {
    if (addMedicineModal) {
        addMedicineModal.style.display = 'none';
    }
}

function openAddMedicineModal() {
    if (addMedicineModal) {
        addMedicineModal.style.display = 'block'; 
    }
}

//Update MEds
// Update Medicine
function openEditMed(medId, medNumber, medName, medDesc, stockIn, stockExp, stockAvailable) {
    // Log each parameter to see what's being passed to the function
    console.log('Opening Edit Medicine Modal:');
    console.log('Medicine ID:', medId);
    console.log('Medicine Number:', medNumber);
    console.log('Medicine Name:', medName);
    console.log('Medicine Description:', medDesc);
    console.log('Stock In:', stockIn);
    console.log('Stock Expiration:', stockExp);
    console.log('Stock Available:', stockAvailable);

    // Format the stock expiration date to YYYY-MM-DD
    const formattedStockExp = new Date(stockExp).toISOString().split('T')[0]; // Convert to YYYY-MM-DD format
    console.log('Formatted Stock Expiration:', formattedStockExp); // Log to verify format

    // Populate the form fields in the edit modal
    document.getElementById('editMedId').value = medId;
    document.getElementById('editMedNumber').value = medNumber;
    document.getElementById('editMedName').value = medName;
    document.getElementById('editMedDesc').value = medDesc;
    document.getElementById('editStockIn').value = stockIn;
    document.getElementById('editStockExp').value = formattedStockExp; // Ensure the correct format is applied
    document.getElementById('editStockAvail').value = stockAvailable;

    // Show the modal
    console.log('Edit modal displayed.'); // Confirm modal is being displayed
    document.getElementById('editMedicineModal').style.display = 'block';
}




// Function to close the edit medicine modal
function closeEditMedModal() {
    var modal = document.getElementById("editMedicineModal");
    if (modal) {
        modal.style.display = 'none';
    }
}

// Function to submit the edit form data asynchronously
function submitEditMedicineForm(event) {
    event.preventDefault();  // Prevent form from reloading the page

    var formData = new FormData(document.getElementById('editForm2'));  

    fetch('MEDICINES/update_meds.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())  
    .then(data => {
        console.log('Success:', data);
        if (data.error) {
            alert('Error: ' + data.error);  
        } else {
            // Update table with the correct data field
            updateMedicineTable(data.medicines); 
            closeEditMedModal();  
            updateDashboard();
            updateMedicineTable2();
        }
    })
    .catch(error => console.error('Error submitting form:', error));
}

document.getElementById('editForm2').addEventListener('submit', submitEditMedicineForm);




// Function to handle delete medicine
function deleteMedicine(medId) {
    if (confirm('Are you sure you want to delete this item?')) {
        fetch('MEDICINES/delete_meds.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'medId': medId
            })
        })
        .then(response => {
            // Check if the response is in JSON format
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                throw new Error('Unexpected content type: ' + contentType);
            }
        })
        .then(data => {
            if (data.success) {
                updateMedicineTable2();
                updateMedicineTable(data.medicines); 
                updateDashboard();
                document.querySelector(`#medRow${medId}`).remove(); 
               
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error deleting record:', error));
    }
}


document.querySelectorAll('#medTable th .resizer2').forEach(resizer => {
    let startX, startWidth;

    resizer.addEventListener('mousedown', e => {
        startX = e.clientX;
        startWidth = resizer.parentElement.offsetWidth;
        document.addEventListener('mousemove', handleMouseMove);
        document.addEventListener('mouseup', () => {
            document.removeEventListener('mousemove', handleMouseMove);
        });
    });

    function handleMouseMove(e) {
        const newWidth = startWidth + (e.clientX - startX);
        resizer.parentElement.style.width = `${newWidth}px`;
        const index = Array.from(resizer.parentElement.parentElement.children).indexOf(resizer.parentElement);
        Array.from(resizer.parentElement.parentElement.parentElement.querySelectorAll('tbody tr')).forEach(row => {
            row.children[index].style.width = `${newWidth}px`;
        });
    }
});

</script>