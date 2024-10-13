


<!-- MEDICAL SUPPLY INVENTORY -->
<section id="medical_supplies-inventory" class="section">
    <h2>Medical & Emergency Supplies Inventory</h2>
    <div class="search-and-add-container">
        <!-- Search bar container -->
        <div class="search-container">
        <input type="text" id="searchInput" onkeyup="searchTable1(this.value);" placeholder="Search for medical supplies...">
        </div>

        <!-- Button container -->
        <div class="add-button-container">
            <button onclick="openAddMedicalSupplyModal()">Add Medical Supply</button>
            
        </div>
    </div>

<!-- MEDICAL SUPPLY TABLE -->
<div class="table-container">
<table id="medicalSuppliesTable" >
    <thead>
        <tr>
            <th>Supply ID<div class="resizer1"></div></th>
            <th>Supply Name<div class="resizer1"></div></th>
            <th>Stock In<div class="resizer1"></div></th>
            <th>Expiration Date<div class="resizer1"></div></th>
            <th>Stock Available<div class="resizer1"></div></th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
    include 'connect.php';

    
    $sql = "SELECT * FROM inv_medsup";
    $result = $conn->query($sql);

    
    if ($result->num_rows > 0) {
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["sup_id"] . "</td>"; 
            echo "<td>" . $row["prod_name"] . "</td>"; 
            echo "<td>" . $row["stck_in"] . "</td>"; 
            echo "<td>" . $row["stck_expired"] . "</td>"; 
            echo "<td>" . $row["stck_avl"] . "</td>"; 
            echo "<td class='action-icons'>";
            echo "<a onclick=\"openEditMedSupp('" . 
            $row["med_supId"] . "', '" . 
            $row["sup_id"] . "', '" . 
            $row["prod_name"] . "', '" . 
            $row["stck_in"] . "', '" . 
            $row["stck_expired"] . "', '" . 
            $row["stck_avl"] . "')\">";
       echo "<img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'></a>";
       
            echo "<a onclick=\"deleteMedicalSupply('" . $row["med_supId"] . "')\">";
            echo "<img src='ARCHIVE.png' alt='Delete' class='delete-btn' style='width: 20px; height: 20px;'></a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No medical supplies found</td></tr>";
    }

    $conn->close();
    ?>
</tbody>

</table>

<!-- Button to open the modal -->
<button onclick="openMedicalSupplyModal()">View Medical Supply Archive</button>

</div>
</section>
<!-- Modal for adding new medical supply -->
<div id="addMedicalSupplyModal" class="modal1">
    <div class="modal-content1">
        <span class="close" onclick="closeAddMedicalSupplyModal()">&times;</span>
        <h3>Add New Medical & Emergency Supply</h3>
        <form id="addMedicalSupplyForm" onsubmit="submitMedicalSupplyForm(event)">
        <label for="supplyName">Supply Id:</label>
        <input type="text" id="supplyId2" name="supplyId2" required><br><br>

            <label for="supplyName">Supply Name:</label>
            <input type="text" id="supplyName" name="supplyName" required><br><br>
            
            <label for="stockIn">Stock In:</label>
            <input type="number" id="stockIn" name="stockIn" required><br><br>
            
            
            <label for="stockExpired">Expiration Date:</label>
            <input type="date" id="stockExpired" name="stockExpired" required>

            
            <label for="stockAvailable">Stock Available:</label>
            <input type="number" id="stockAvailable" name="stockAvailable" required><br><br>
            
            <input type="submit" value="Submit">
        </form>
    </div>
</div>

<!-- Modal for editing medical supplies -->
<div id="editMedicalSupplyModal" class="modal1">
    <div class="modal-content1">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h3>Edit Medical/Emergency Supply</h3>
        <form id="editForm" onsubmit="submitEditMedicalSupplyForm(event)">
            <input type="hidden" id="editSuppId" name="supplyId"> <!-- med_supId -->
            
            <label for="editSupplyId2">Supply ID:</label>
            <input type="text" id="editSuppId2" name="supplyId2" required><br><br>

            <label for="editSupplyName">Supply Name:</label>
            <input type="text" id="editSupplyName" name="supplyName" required><br><br>
            
            <label for="editStockIn">Stock In:</label>
            <input type="number" id="editStockIn2" name="stockIn2" required><br><br>

            <label for="editStockExp">Expired:</label>
            <input type="date" id="editStockExp2" name="stockExpired2" required><br><br>

            <label for="editStockAvail">Stock Available:</label>
            <input type="number" id="editStockAvail2" name="stockAvailable2" required><br><br>

            <input type="submit" value="Submit">
        </form>
    </div>
</div>


<!-- Medical Supply Archive Modal -->
<div id="medicalSupplyModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeMedicalSupplyModal()">&times;</span>
        <h3>Medical Supply Archive</h3>

        <!-- Search bar for archived medical supplies -->
        <div class="search-container">
            <input type="text" id="searchMedicalSupplyModalInput" onkeyup="searchMedicalSupplyTable(this.value);" placeholder="Search archived medical supplies...">
        </div>

        <!-- Medical Supply Archive Table -->
        <div class="table-container">
            <table id="medicalSuppliesModalArchiveTable">
                <thead>
                    <tr>
                        <th>Supply ID</th>
                        <th>Supply Name</th>
                        <th>Expiration Date</th>
                        <th>Stock Available</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'connect.php';
                    $sql = "SELECT * FROM a_medsup";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["supplyid"] . "</td>";
                            echo "<td>" . $row["prdctname"] . "</td>";
                            echo "<td>" . $row["expdate"] . "</td>";
                            echo "<td>" . $row["stck_avail"] . "</td>";
                            echo "<td>" . $row["status"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No archived medical supplies found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>

// Function to fetch medical supplies
function fetchMedicalSupplies2() {
    fetch('MEDICAL_SUPPLY/latestmedicalsupp.php') // Adjust the path as necessary
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText); // Handle HTTP errors
            }
            return response.text(); // Get the response as text
        })
        .then(data => {
            try {
                const result = JSON.parse(data); // Parse the text to JSON
                if (result.success) {
                    console.log("result in fetching med sup :" , result.data);
                    updateMedicalSupplyTable(result.data); // Call the function to update the table
                } else {
                    console.error('Error fetching medical supplies:', result.message);
                }
            } catch (error) {
                console.error('Error parsing JSON:', error); // Handle JSON parsing error
            }
        })
        .catch(error => console.error('Error:', error)); // Handle fetch error
}

// Function to update the archived medical supply table
function updateMedicalSupplyTable2() {
    fetch('MEDICAL_SUPPLY/getmedicalsupplies.php') // Adjust the path as necessary
        .then(response => response.json())
        .then(result => {
            const tableBody = document.getElementById("medicalSuppliesModalArchiveTable").getElementsByTagName('tbody')[0];
            tableBody.innerHTML = ""; // Clear existing table rows

            if (result.success && result.data.length > 0) {
                console.log("data check: ", result.data)
                result.data.forEach(supply => {
                    const row = document.createElement('tr');

                    // Populate the row with medical supply data
                    row.innerHTML = `
                        <td>${supply.supplyid}</td>
                        <td>${supply.prdctname}</td>
                        <td>${supply.expdate}</td>
                        <td>${supply.stck_avail}</td>
                        <td>${supply.status}</td>
                    `;
                    
                    tableBody.appendChild(row);
                });
            } else {
                // If no archived medical supplies are found
                const row = document.createElement('tr');
                row.innerHTML = "<td colspan='5'>No archived medical supplies found</td>";
                tableBody.appendChild(row);
            }
        })
        .catch(error => console.error('Error fetching archived medical supplies:', error));
}

// Function to open the medical supply archive modal
function openMedicalSupplyModal() {
    updateMedicalSupplyTable2(); // Call the function to update the table
    document.getElementById('medicalSupplyModal').style.display = 'block'; // Show the modal
}


// Function to close the medical supply archive modal
function closeMedicalSupplyModal() {
    document.getElementById('medicalSupplyModal').style.display = 'none';
}

// Function to search through the medical supply table in the modal
function searchMedicalSupplyTable(inputValue) {
    var searchQuery = inputValue.toLowerCase().trim();
    var table = document.getElementById("medicalSuppliesModalArchiveTable");
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



var addMedicalSupplyModal = document.getElementById("addMedicalSupplyModal"); //ADD MEDICAL SUPPLY 
    var editMedicalSupplyModal =document.getElementById("editMedicalSupplyModal") //EDIT MS

//MEDICAL SUPPLY
// FUNCTION FOR ADDING MEDICAL SUPPLY 
function submitMedicalSupplyForm(event) {
    event.preventDefault(); // Prevent the default form submission behavior
    
    var formData = new FormData(document.getElementById('addMedicalSupplyForm'));

    // Send a POST request to the 'add_medical_supply.php' endpoint with the form data
    fetch('MEDICAL_SUPPLY/add_medical_supply.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        // Ensure the response is in OK status
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text(); // Get the raw text response
    })
    .then(text => {
        console.log('Raw response:', text); // Log the raw response

        try {
            var data = JSON.parse(text); // Parse the text as JSON
            console.log('Parsed JSON:', data);
            
            // Check if the medical supply was archived due to expiration
            if (data.success && data.archivedData) {
                alert('The medical supply was archived because it is expired.');
                updateMedicalSupplyTable2();
                 // Fetch and update the latest inv_medsup table
            } else if (data.success) {
                // Otherwise, update the medical supply table with the new data
                updateMedicalSupplyTable(data.data);
            } else {
                // If there is an error in the response, log it
                console.error('Error:', data.message || 'Unknown error occurred.');
            }

            // Close the modal form after successful submission
            closeAddMedicalSupplyModal();
            updateDashboard(); // Update the dashboard if necessary
        } catch (error) {
            console.error('Error parsing JSON:', error);
            alert('Error: Invalid JSON response');
        }
    })
    .catch(error => {
        console.error('Error submitting form:', error);
        alert('Error submitting form: ' + error.message); // Provide feedback to the user
    });
}



    
function updateMedicalSupplyTable(data) {
    var tableBody = document.querySelector('#medicalSuppliesTable tbody');
    tableBody.innerHTML = ''; 

    if (Array.isArray(data) && data.length > 0) {
        data.forEach(supply => {
            var row = document.createElement('tr');
            row.innerHTML = `
                <td>${escapeHtml(supply.sup_id)}</td> <!-- Assuming this is the correct property -->
                <td>${escapeHtml(supply.prod_name)}</td>
                <td>${escapeHtml(supply.stck_in)}</td>
                <td>${escapeHtml(supply.stck_expired)}</td>
                <td>${escapeHtml(supply.stck_avl)}</td>
                <td class='action-icons'>
                    <a href='#' class='edit-btn' onclick='openEditMedSupp(${supply.med_supId}, "${escapeHtml(supply.sup_id)}", "${escapeHtml(supply.prod_name)}", ${supply.stck_in}, "${supply.stck_expired}", ${supply.stck_avl})'>
                        <img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'>
                    </a>
                    <a href='#' class='delete-btn' onclick='deleteMedicalSupply(${supply.med_supId})'>
                        <img src='ARCHIVE.png' alt='Delete' style='width: 20px; height: 20px;'>
                    </a>
                </td>
            `;
            tableBody.appendChild(row);
        });
    } else {
        tableBody.innerHTML = '<tr><td colspan="6">No medical supplies found</td></tr>';
    }
}

// Helper function to escape HTML special characters
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

    
    // Function to escape special characters for HTML

        // Function to close the add medical supply modal
        function closeAddMedicalSupplyModal() {
            if (addMedicalSupplyModal) {
                addMedicalSupplyModal.style.display = 'none';
            }
        }
    
        // Function to open the add medical supply modal
        function openAddMedicalSupplyModal() {
            if (addMedicalSupplyModal) {
                addMedicalSupplyModal.style.display = 'block';
            }
        }
    
    
        function openEditMedSupp(medSupId, supplyId, supplyName, stockIn, stockExpired, stockAvailable) {

    // Populate the form fields in the edit modal
    document.getElementById('editSuppId').value = medSupId; // Hidden field for med_supId
    document.getElementById('editSuppId2').value = supplyId; // Supply ID field
    document.getElementById('editSupplyName').value = supplyName; // Supply Name
    document.getElementById('editStockIn2').value = stockIn; // Stock In
    document.getElementById('editStockExp2').value = stockExpired; // Expiration Date
    document.getElementById('editStockAvail2').value = stockAvailable; // Stock Available

    // Show the modal
    document.getElementById('editMedicalSupplyModal').style.display = 'block';
    console.log('Edit modal displayed.');
}



    
    function closeEditModal() {
        var modal = document.getElementById("editMedicalSupplyModal");
        if (modal) {
            modal.style.display = 'none';
        }
    }
    
    function submitEditMedicalSupplyForm(event) {
    event.preventDefault(); // Prevent the default form submission behavior

    var formData = new FormData(document.getElementById('editForm'));

    // Send a POST request to the 'update_supply.php' endpoint with the form data
    fetch('MEDICAL_SUPPLY/update_supply.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())  
    .then(data => {
       
        if (data.error) {
            alert('Error: ' + data.error);  
        } else {
            console.log('Success:', data.supplies);
            updateMedicalSupplyTable(data.supplies);
            closeEditModal();
            updateDashboard();
            updateMedicalSupplyTable2();
        }
    })
    .catch(error => console.error('Error submitting form:', error));
}

// Attach the event listener for the form submission
document.getElementById('editForm').addEventListener('submit', submitEditMedicalSupplyForm);



    
    // Function to handle delete MS
    function deleteMedicalSupply(medSupId) {
        if (confirm('Are you sure you want to archive this supply?')) {
            fetch('MEDICAL_SUPPLY/delete_supply.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    medSupId: medSupId
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok.');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    updateMedicalSupplyTable(data.supplies);
                    updateDashboard();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
    


    document.querySelectorAll('#medicalSuppliesTable th .resizer1').forEach(resizer => {
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