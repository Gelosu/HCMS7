</section>


        <!-- Patient Medication section -->
        <section id="patient-med" class="section">
            <h2>Patient Medication</h2>
            
            <div class="search-and-add-container">
        <!-- Search bar container -->
        <div class="search-container">
        <input type="text" id="searchInput" onkeyup="searchTable5(this.value);" placeholder="Search for patient medication...">
        </div>

        <!-- Button container -->
        <div class="add-button-container">
            <button onclick="openAddMedicationModal()">Add Patient Medication</button>
        </div>
    </div>
 <!-- Add Patient Medication Modal -->
<!-- Add Medication Modal -->
<div id="addMedicationModal" class="modal5">
    <div class="modal-content5">
        <span class="close" onclick="closeAddMedicationModal()">&times;</span>
        <h3>Add Patient Medication</h3>

        <!-- Form starts here -->
        <form id="addMedicationForm" onsubmit="submitAddMedicationForm(event)">
            <!-- Patient Dropdown -->
            <label for="medicationPatientName">Name of Patient:</label>
            <select id="medicationPatientName" name="medicationPatientName" required>
                <!-- Options will be populated dynamically -->
            </select>
            
            <!-- Medicine Section -->
            <div id="medicineContainer">
                <div class="medicine-entry">
                    <label for="medicines">Medicine:</label>
                    <select class="medicine-dropdown" name="medicines[]" required onchange="updateAmountPlaceholder(this)">
                        <!-- Options will be populated dynamically -->
                    </select>
                    <label for="amount">Amount:</label>
                    <input type="number" name="amount[]" class="medicine-amount" required min="0" placeholder="0">
                    <!-- Hidden input to store the original amount -->
                    <input type="hidden" name="originalAmount[]" class="original-amount">
                    <!-- Delete button -->
                    <button type="hidden" class="delete-medicine-button" disabled onclick="deleteMedicineField(this)">Delete</button>
                </div>
            </div>

            <!-- Add Medicine Button -->
            <button type="button" id="addMedicineButton" onclick="addMedicineField()">Add Another Medicine</button>
            
           <!-- Date and Time Input 
            <label for="medicationDateTime">Date and Time:</label>
            <input type="datetime-local" id="medicationDateTime" name="medicationDateTime" required> -->
            
            <!-- Assigned Healthworker -->
            <label for="medicationHealthWorker">Assigned Healthworker:</label>
            <input type="text" id="medicationHealthWorker" name="medicationHealthWorker" value="<?php echo htmlspecialchars($healthWorker); ?>" readonly>
            
            <!-- Submit Button -->
            <input type="submit" value="Submit">
        </form>
    </div>
</div>







<!-- Edit Patient Medication Modal -->
<div id="editMedicationModal" class="modal5">
    <div class="modal-content5">
        <span class="close" onclick="closeEditMedicationModal()">&times;</span>
        <h3>Edit Patient Medication</h3>
        
        <!-- Form starts here -->
        <form id="editMedicationForm" onsubmit="submitEditMedicationForm(event)">
            <!-- Patient Dropdown (disabled or read-only if you don't want to change it) -->
            <label for="editMedicationPatientName">Name of Patient:</label>
            <input type="text" id="editMedicationPatientName" name="editMedicationPatientName" readonly required>
            
           <!-- Medicine Section in Edit Modal -->
<div id="editMedicineContainer">
    <!-- Existing Medicine Entry -->
    <div class="medicine-entry">
        <label for="editMedicines">Medicine:</label>
        <select class="medicine-dropdown" name="editMedicines[]" required>
            <!-- Options will be populated dynamically -->
        </select>
        <label for="editAmount">Amount:</label>
        <input type="number" name="editAmount[]" class="medicine-amount" required min="0" placeholder="0" max="">
        <!-- Hidden input for original amount -->
        <input type="hidden" name="originalAmount[]">
    </div>
</div>


            <!-- Add Medicine Button -->
            <button type="button" id="addMedicineButton" onclick="addEditMedicineField()">Add Another Medicine</button>
            
            <!-- Date and Time Input 
            <label for="editMedicationDateTime">Date and Time:</label>
            <input type="datetime-local" id="editMedicationDateTime" name="editMedicationDateTime" required> -->
            
            <!-- Assigned Healthworker -->
            <label for="editMedicationHealthWorker">Assigned Healthworker:</label>
            <input type="text" id="editMedicationHealthWorker" name="editMedicationHealthWorker" readonly>
            
            <!-- Hidden input to store the ID of the medication being edited -->
            <input type="hidden" id="editMedicationId" name="editMedicationId">
            
            <!-- Submit Button -->
            <input type="submit" value="Submit">
        </form>
    </div>
</div>



            <!-- Medications Table -->
            <div class="table-container">
            <table id="medicationtable">
            <thead>
        <tr>
            <th>Patient Name<div class="resizer5"></div></th>
            <th>Medicines with Amount<div class="resizer5"></div></th>
            <th>Date and Time<div class="resizer5"></div></th>
            <th>Assigned Healthworker<div class="resizer5"></div></th>
            <th>Actions<div class="resizer5"></div></th>
        </tr>
    </thead>
    <tbody>
    <?php
include 'connect.php';

// SQL query to fetch medications along with patient name
$sql = "SELECT id, p_medpatient AS patient_name, p_medication AS medication, datetime AS date_time, a_healthworker AS healthworker
        FROM p_medication";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["patient_name"]) . "</td>";

        // Decode the JSON data for p_medication
        $medications = json_decode($row["medication"], true);
        $medicationDetails = [];

        if (is_array($medications)) {
            foreach ($medications as $medication) {
                // Use default values if 'name' or 'amount' keys are missing
                $med_name = isset($medication['name']) ? htmlspecialchars($medication['name']) : 'Unknown Medicine';
                $amount = isset($medication['amount']) ? htmlspecialchars($medication['amount']) : '0';

                // Add formatted medication name and amount to the details array
                $medicationDetails[] = "$amount x $med_name";
            }
        }

        // Format the medication details into a single string
        $medicationDisplay = implode("<br>", $medicationDetails);

        echo "<td>" . $medicationDisplay . "</td>";

        // Format the date and time
        $dateTime = new DateTime($row["date_time"]);
        $formattedDateTime = $dateTime->format('F j, Y \a\t g:i a');

        echo "<td>" . htmlspecialchars($formattedDateTime) . "</td>";
        echo "<td>" . htmlspecialchars($row["healthworker"]) . "</td>";
        echo "<td class='action-icons'>";

        // Edit button
        $id = htmlspecialchars($row["id"]);
        $patient_name = htmlspecialchars($row["patient_name"]);
        $medications_json = htmlspecialchars(json_encode(json_decode($row["medication"], true)));
        $date_time = htmlspecialchars($row["date_time"]);
        $healthworker = htmlspecialchars($row["healthworker"]);

        echo "<a onclick=\"openEditMedicationModal('$id', '$patient_name', '$medications_json',  '$healthworker')\">";
        echo "<img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'></a>";

        // Delete button
        echo "<a onclick=\"deleteMedication('" . htmlspecialchars($row["id"]) . "')\">";
        echo "<img src='delete_icon.png' alt='Delete' class='delete-btn' style='width: 20px; height: 20px;'></a>";

        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No medications found</td></tr>";
}

$conn->close();
?>



    </tbody>
</table>
</div>
        </section>



<script>
    //MEDICATION CHECK


let selectedMedicines = []; // Array to keep track of selected medicines

function updateMedicineOptions() {
    const dropdowns = document.querySelectorAll('.medicine-dropdown');
    dropdowns.forEach(dropdown => {
        const selectedValues = Array.from(dropdown.selectedOptions).map(option => option.value);
        // Hide selected medicines from other dropdowns
        dropdown.querySelectorAll('option').forEach(option => {
            if (selectedValues.includes(option.value)) {
                option.style.display = 'none';
            } else {
                option.style.display = 'block';
            }
        });
    });
}

function updateAmountPlaceholder(selectElement) {
    const amountInput = selectElement.closest('.medicine-entry').querySelector('.medicine-amount');
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const maxStock = selectedOption.getAttribute('data-stock');
    
    if (maxStock) {
        amountInput.placeholder = maxStock; // Set placeholder
        amountInput.max = maxStock; // Set maximum value
        amountInput.min = 0; // Set minimum value to 0
    } else {
        amountInput.placeholder = '0'; // Default placeholder if no stock data
        amountInput.max = ''; // Remove maximum value constraint
        amountInput.min = 0; // Set minimum value to 0
    }
}


//MEDICATION AND APPOINTMENT BUGGISH WHEN USING SRC SCRIPT!!!

// Open Add Medication Modal
function openAddMedicationModal() {
    // Fetch and populate patient names
    fetch('P_MEDICATION/fetch_patients.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populatePatientDropdown(data.data, 'medicationPatientName');
            } else {
                console.error('Failed to fetch patient data:', data.message);
            }
        })
        .catch(error => console.error('Error fetching patient data:', error));

    // Fetch and populate medicines for the dropdowns
    fetch('P_MEDICATION/fetch_medicines.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
            // Ensure all existing dropdowns are populated
            populateMedicineDropdown(data.medicines, data.supplies);
        } else {
            console.error('Failed to fetch medicine data:', data.message);
        }
    })
    .catch(error => console.error('Error fetching medicine data:', error));

    // Show the modal
    document.getElementById('addMedicationModal').style.display = 'block';
}
function populateMedicineDropdown(medicines, supplies) {
    const dropdowns = document.querySelectorAll('.medicine-dropdown'); // Select all current dropdowns
    dropdowns.forEach(dropdown => {
        dropdown.innerHTML = ''; // Clear existing options

        // Add a default option
        const defaultOption = document.createElement('option');
        defaultOption.text = 'Select an item'; // Default prompt
        defaultOption.value = ''; // Default value
        dropdown.appendChild(defaultOption);

        // Add supplies section
        const suppliesDivider = document.createElement('option');
        suppliesDivider.text = 'SUPPLIES:'; // Divider text
        suppliesDivider.disabled = true; // Disable to prevent selection
        suppliesDivider.style.fontWeight = 'bold'; // Make divider bold
        dropdown.appendChild(suppliesDivider);

        // Populate supplies
        supplies.forEach(supply => {
            const supplyOption = document.createElement('option');
            supplyOption.value = supply.med_supId; // Use supply ID directly
            supplyOption.text = `${supply.prod_name} (Available: ${supply.stck_avl})`; // Display supply name and available stock
            supplyOption.setAttribute('data-stock', supply.stck_avl); // Add stock data attribute
            supplyOption.setAttribute('data-type', 'supply'); // Mark as supply
            dropdown.appendChild(supplyOption);
        });

        // Add medicines section
        const medicinesDivider = document.createElement('option');
        medicinesDivider.text = 'MEDICINES:'; // Divider text
        medicinesDivider.disabled = true; // Disable to prevent selection
        medicinesDivider.style.fontWeight = 'bold'; // Make divider bold
        dropdown.appendChild(medicinesDivider);

        // Populate medicines
        medicines.forEach(medicine => {
            const option = document.createElement('option');
            option.value = medicine.med_id; // Use medicine ID directly
            option.text = `${medicine.meds_name} (Available: ${medicine.stock_avail})`; // Display medicine name and available stock
            option.setAttribute('data-stock', medicine.stock_avail); // Add stock data attribute
            option.setAttribute('data-type', 'medicine'); // Mark as medicine
            dropdown.appendChild(option);
        });
    });
}

// Close Add Medication Modal
function closeAddMedicationModal() {
    document.getElementById('addMedicationModal').style.display = 'none';
}

// Populate patient dropdown
function populatePatientDropdown(patients, dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    dropdown.innerHTML = ''; // Clear existing options

    const defaultOption = document.createElement('option');
    defaultOption.text = 'Select a patient';
    defaultOption.value = '';
    dropdown.add(defaultOption);

    patients.forEach(patient => {
        const option = document.createElement('option');
        option.value = patient.p_id; // assuming patient ID is 'p_id'
        option.text = patient.p_name; // assuming patient name is 'p_name'
        dropdown.add(option);
    });
}

// Cache medicine options globally to avoid fetching multiple times
let medicineOptions = [];

// Function to fetch and cache medicine options
function fetchMedicineOptions() {
    return fetch('P_MEDICATION/fetch_medicines.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                medicineOptions = data.data;
                populateMedicineDropdown(medicineOptions, data.supplies); // Update this line to pass supplies
            } else {
                throw new Error('Failed to fetch medicine options.');
            }
        });
}

// Function to dynamically add a new medicine input field
function addMedicineField(medName = '', medAmount = '') {
    const container = document.getElementById('medicineContainer');

    const newMedicineEntry = document.createElement('div');
    newMedicineEntry.className = 'medicine-entry';

    // Create new medicine dropdown
    const medicineLabel = document.createElement('label');
    medicineLabel.textContent = "Medicine:";
    newMedicineEntry.appendChild(medicineLabel);

    const newMedicineDropdown = document.createElement('select');
    newMedicineDropdown.className = 'medicine-dropdown';
    newMedicineDropdown.name = 'medicines[]';
    newMedicineDropdown.required = true;
    newMedicineDropdown.innerHTML = document.querySelector('.medicine-dropdown').innerHTML; // Clone existing options
    newMedicineDropdown.addEventListener('change', function() {
        console.log('Selected Medicine/Supply:', this.value); // Log the selected value
        const selectedOptionText = this.options[this.selectedIndex].text; // Get the selected option's text
        console.log('Selected Option Text:', selectedOptionText); // Log the option text
        const stockAvailable = this.options[this.selectedIndex].getAttribute('data-stock'); // Fetch stock data
        console.log('Available Stock:', stockAvailable); // Log available stock
        updateAmountPlaceholder(this); // Ensure this function is called
    });
    newMedicineEntry.appendChild(newMedicineDropdown);

    // Create new amount input
    const amountLabel = document.createElement('label');
    amountLabel.textContent = "Amount:";
    newMedicineEntry.appendChild(amountLabel);

    const newAmountInput = document.createElement('input');
    newAmountInput.type = 'number';
    newAmountInput.name = 'amount[]';
    newAmountInput.className = 'medicine-amount';
    newAmountInput.required = true;
    newAmountInput.min = 0;
    newAmountInput.placeholder = '0'; // Default placeholder
    newAmountInput.max = ''; // Default no max
    newAmountInput.value = medAmount; // Set the default amount value
    newMedicineEntry.appendChild(newAmountInput);

    // Create hidden input for original amount
    const originalAmountInput = document.createElement('input');
    originalAmountInput.type = 'hidden';
    originalAmountInput.name = 'originalAmount[]';
    originalAmountInput.className = 'original-amount';
    originalAmountInput.value = medAmount; // Set the original amount value
    newMedicineEntry.appendChild(originalAmountInput);

    // Create delete button
    const deleteButton = document.createElement('button');
    deleteButton.type = 'button';
    deleteButton.textContent = 'Delete';
    deleteButton.className = 'delete-btn';
    deleteButton.onclick = function() {
        container.removeChild(newMedicineEntry); // Remove this medicine entry
    };
    newMedicineEntry.appendChild(deleteButton);

    // Append the new entry to the medicine container
    container.appendChild(newMedicineEntry);

    // Initialize the placeholder and limits for the newly added medicine dropdown
    updateAmountPlaceholder(newMedicineDropdown);
}

// Function to populate a specific medicine dropdown (used for both add and edit modals)
function populateMedicineDropdownForEntry(dropdown, selectedMedicine = '') {
    dropdown.innerHTML = ''; // Clear existing options

    const defaultOption = document.createElement('option');
    defaultOption.text = 'Select a medicine or supply'; // Updated default option
    defaultOption.value = '';
    dropdown.add(defaultOption);

    // Get selected medicines from all dropdowns
    const selectedValues = Array.from(document.querySelectorAll('.medicine-dropdown')).map(dropdown => dropdown.value);

    // Loop through available medicine options and add them to the dropdown
    medicineOptions.forEach(item => {
        const isMedicine = item.med_id !== undefined; // Check if it's a medicine
        const isSupply = item.med_supId !== undefined; // Check if it's a supply
        
        // Skip if the item is already selected in other dropdowns
        if (!selectedValues.includes(item.med_id) && !selectedValues.includes(item.med_supId) || 
            (isMedicine && item.med_id === selectedMedicine) || 
            (isSupply && item.med_supId === selectedMedicine)) {
            
            const option = document.createElement('option');
            option.value = isMedicine ? item.med_id : item.med_supId;  // Use the appropriate ID
            option.text = isMedicine ? 
                `${item.meds_name} (Available: ${item.stock_avail})` : 
                `${item.prod_name} (Available: ${item.stck_avl})`; // Differentiate between medicine and supply

            dropdown.add(option);

            // Pre-select the correct medicine/supply if it matches the 'selectedMedicine' value
            if (item.med_id === selectedMedicine || item.med_supId === selectedMedicine) {
                dropdown.value = isMedicine ? item.med_id : item.med_supId;
            }
        }
    });
}

function submitAddMedicationForm(event) {
    event.preventDefault();

    const form = document.getElementById('addMedicationForm');
    const formData = new FormData(form);

    // Create arrays for medicines, amounts, and data types
    const medicines = [];
    const amounts = [];
    const dataTypes = [];

    const medicineDropdowns = document.querySelectorAll('.medicine-dropdown');
    const amountInputs = document.querySelectorAll('.medicine-amount');

    medicineDropdowns.forEach((dropdown, index) => {
        const selectedValue = dropdown.value;
        if (selectedValue) {
            medicines.push(selectedValue);
            amounts.push(amountInputs[index].value);
            const type = dropdown.options[dropdown.selectedIndex].getAttribute('data-type');
            dataTypes.push(type);
        }
    });

    // Append medicines, amounts, and data types to FormData
    medicines.forEach(medicine => formData.append('medicines[]', medicine));
    amounts.forEach(amount => formData.append('amount[]', amount));
    dataTypes.forEach(dataType => formData.append('data_type[]', dataType));

   

    fetch('P_MEDICATION/add_pmedication.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())  // Parse the response as plain text
    .then(result => {
        try {
            // Attempt to parse the result as JSON
            const parsedResult = JSON.parse(result);

            if (parsedResult.success) {
                console.log("Results: ", parsedResult);
                closeAddMedicationModal();
                updateMedicationTable(parsedResult.data);
                updateDashboard();
                fetchMedicines(); // Refresh table with the updated data
            } else {
                console.log('Error adding medication: ' + parsedResult.error);
            }
        } catch (error) {
            console.error('Error parsing response as JSON:', error);
            console.error('Response received:', result);
        }
    })
    .catch(error => console.error('Fetch Error:', error));
}



// Update Medication Table
function updateMedicationTable(medications) {
    const tableBody = document.querySelector('#medicationtable tbody');
    
    if (tableBody) {
        tableBody.innerHTML = ''; // Clear existing rows

        // Check if medications is an array
        if (Array.isArray(medications)) {
            medications.forEach(medication => {
                const row = document.createElement('tr');

                // Ensure p_medication is an array
                let medicinesList = '';
                if (Array.isArray(medication.p_medication)) {
                    medication.p_medication.forEach(med => {
                        // Check if each med object has name and amount
                        if (med.name && med.amount) {
                            medicinesList += `${med.amount}x ${med.name}<br>`;
                        } else {
                            console.warn('Medication object missing name or amount:', med);
                        }
                    });
                } else {
                    console.warn('Expected p_medication to be an array, but got:', medication.p_medication);
                }

                // Escape function parameters for safe usage in HTML attributes
                const escapeHtml = (str) => {
                    return str
                        .replace(/&/g, "&amp;")
                        .replace(/</g, "&lt;")
                        .replace(/>/g, "&gt;")
                        .replace(/"/g, "&quot;")
                        .replace(/'/g, "&#039;");
                };

                // Create and append row
                row.innerHTML = `
                    <td>${escapeHtml(medication.patient_name)}</td>
                    <td>${medicinesList}</td>
                    <td>${formatDateTime(medication.date_time)}</td>
                    <td>${escapeHtml(medication.healthworker)}</td>
                    <td>
                        <a onclick="openEditMedicationModal('${escapeHtml(medication.id)}', '${escapeHtml(medication.patient_name)}', '${escapeHtml(JSON.stringify(medication.p_medication))}', '${escapeHtml(medication.healthworker)}')">
                            <img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'>
                        </a>
                        <a onclick="deleteMedication('${escapeHtml(medication.id)}')">
                            <img src='delete_icon.png' alt='Delete' class='delete-btn' style='width: 20px; height: 20px;'>
                        </a>
                    </td>
                `;

                tableBody.appendChild(row);
            });
        } else {
            console.error('Expected an array of medications, but got:', medications);
        }
    } else {
        console.error('Table body not found. Ensure the table ID and selector are correct.');
    }
}



// Helper function to format date and time
function formatDateTime(dateTime) {
    const date = new Date(dateTime);
    // Customize the format as needed
    return date.toLocaleString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}


// Function to open the Edit Medication Modal and populate the fields
function openEditMedicationModal(id, patientName, medicationsJson, healthworker) {
    console.log("ID:", id);
    console.log("Patient Name:", patientName);
    console.log("Medications JSON:", medicationsJson);
    console.log("Health Worker:", healthworker);

    // Set form values
    document.getElementById('editMedicationId').value = id;
    document.getElementById('editMedicationPatientName').value = patientName;
    document.getElementById('editMedicationHealthWorker').value = healthworker;

    // Parse the JSON string into an object
    let medications;
    try {
        medications = JSON.parse(medicationsJson);
    } catch (error) {
        console.error("Error parsing JSON:", error);
        medications = [];
    }

    if (!Array.isArray(medications)) {
        console.error("Expected medications to be an array but got:", medications);
        medications = [];
    }

    // Populate medicines
    const editMedicineContainer = document.getElementById('editMedicineContainer');
    editMedicineContainer.innerHTML = ''; // Clear existing entries

    // Add each medication dynamically with delete option and pre-select the correct value
    medications.forEach(med => {
        addEditMedicineField(med.name, med.amount); // Pass med.name to pre-select the correct medicine
    });

    // Show the modal
    document.getElementById('editMedicationModal').style.display = 'block';
}


// Format Date and Time for input
function formatDateTimeForInput(datetime) {
    const date = new Date(datetime);
    return date.toISOString().slice(0, 16); // Format for datetime-local input
}

function addEditMedicineField(medName = '', medAmount = '', isReadOnly = false) {
    const editContainer = document.getElementById('editMedicineContainer');
    
    const newMedicineEntry = document.createElement('div');
    newMedicineEntry.className = 'medicine-entry';

    // Create new medicine dropdown
    const medicineLabel = document.createElement('label');
    medicineLabel.textContent = "Medicine:";
    newMedicineEntry.appendChild(medicineLabel);

    const newMedicineDropdown = document.createElement('select');
    newMedicineDropdown.className = 'medicine-dropdown';
    newMedicineDropdown.name = 'editMedicines[]';
    newMedicineDropdown.required = true;
    newMedicineDropdown.disabled = isReadOnly; // Make dropdown read-only if needed

    // Populate the dropdown
    populateMedicineDropdownForEntry(newMedicineDropdown, medName);
    
    newMedicineDropdown.addEventListener('change', function() {
        // Update selected medicines array
        selectedMedicines = Array.from(document.querySelectorAll('.medicine-dropdown')).map(dropdown => dropdown.value);
        updateMedicineOptions();
    });

    newMedicineEntry.appendChild(newMedicineDropdown);

    // Create new amount input
    const amountLabel = document.createElement('label');
    amountLabel.textContent = "Amount:";
    newMedicineEntry.appendChild(amountLabel);

    const newAmountInput = document.createElement('input');
    newAmountInput.type = 'number';
    newAmountInput.name = 'editAmount[]';
    newAmountInput.required = true;
    newAmountInput.value = medAmount; // Set the default amount value
    newAmountInput.min = '0'; // Default minimum value
    newAmountInput.disabled = isReadOnly; // Make amount input read-only if needed
    newMedicineEntry.appendChild(newAmountInput);

    // Create delete button
    const deleteButton = document.createElement('button');
    deleteButton.type = 'button';
    deleteButton.textContent = 'Delete';
    deleteButton.className = 'delete-btn2';
    deleteButton.onclick = function() {
        // Ensure there's at least one entry left before allowing deletion
        if (editContainer.children.length > 1) {
            editContainer.removeChild(newMedicineEntry); // Remove this medicine entry
            // Recalculate selected medicines
            selectedMedicines = Array.from(document.querySelectorAll('.medicine-dropdown')).map(dropdown => dropdown.value);
            updateMedicineOptions();
        } else {
            alert('You must have at least one medicine entry.');
        }
    };
    newMedicineEntry.appendChild(deleteButton);

    // Append the new entry to the medicine container
    editContainer.appendChild(newMedicineEntry);

    // Populate dropdown and pre-select correct medicine
    if (medicineOptions.length > 0) {
        populateMedicineDropdownForEntry(newMedicineDropdown, medName); // Pass the medName to pre-select
    } else {
        // Fetch medicine options if not already cached
        fetchMedicineOptions().then(() => {
            populateMedicineDropdownForEntry(newMedicineDropdown, medName); // Pass the medName to pre-select
        });
    }

    // Update options for all dropdowns
    updateMedicineOptions();
}


// Close Edit Medication Modal
function closeEditMedicationModal() {
    document.getElementById('editMedicationModal').style.display = 'none';
}



// Submit Edit Medication Form
function submitEditMedicationForm(event) {
    event.preventDefault();
    const form = document.getElementById('editMedicationForm');
    const formData = new FormData(form);

    fetch('P_MEDICATION/edit_pmedication.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())  // Get response as text
    .then(text => {
        console.log("Response Text: ", text);  // Log raw response text for debugging
        try {
            const result = JSON.parse(text);  // Parse the text as JSON

            if (result.success) {
                console.log("Edit result: ", result);
                closeEditMedicationModal();
                updateMedicationTable(result.data);
                updateDashboard();
                fetchMedicines();
            } else {
                alert('Error editing medication: ' + result.error);
            }
        } catch (error) {
            console.error('Error parsing JSON:', error);
        }
    })
    .catch(error => console.error('Error:', error));
}



function deleteMedication(medicationId) {
    if (confirm('Are you sure you want to delete this medication? Take note to update the stocks again in MEDICINE INVENTORY.')) {
        fetch('P_MEDICATION/delete_pmedication.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ medId: medicationId })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                console.log("resultsss: ", result.data)
                updateMedicationTable(result.data);
                updateDashboard(); 
                fetchMedicines();// Refresh table with the updated data
            } else {
                alert('Error deleting medication: ' + result.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}


function searchTable5(inputValue) {
    var searchQuery = inputValue.toLowerCase().trim();
    var table = document.getElementById("medicationtable"); 
    var rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var cell = row.getElementsByTagName('td')[0]; 

        if (cell) {
            var cellValue = cell.textContent || cell.innerText; 
           
            if (cellValue.toLowerCase().indexOf(searchQuery) > -1) {
                row.style.display = ''; 
            } else {
                row.style.display = 'none'; 
            }
        }
    }
}


document.querySelectorAll('#medicationtable th .resizer5').forEach(resizer => {
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