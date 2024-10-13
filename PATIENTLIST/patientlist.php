

<div id="content">
<section id="patient-list" class="section">
    <h2>Patient List</h2>

    <div class="search-and-add-container">
        <div class="search-container">
            <input type="text" id="searchInput" onkeyup="searchTable3(this.value);" placeholder="Search for patients...">
        </div>

        <div class="filter-container">
            <select id="categoryDropdown" onchange="filterByCategory(this.value)">
                <option value="">All</option> 
                <option value="Pedia">Pedia</option>
                <option value="Senior">Senior</option>
                <option value="PWD">PWD</option>
                <option value="OPD">OPD</option>
            </select>
        </div>

        <div class="add-button-container">
            <button onclick="openAddPatientModal()">Add Patient</button>
        </div>
    </div>

    <div class="table-container">
        <table id="patientTable">
            <thead>
                <tr>
                    <th>Name<div class="resizer3"></div></th>
                    <th>Age<div class="resizer3"></div></th>
                    <th>Birthday<div class="resizer3"></div></th>
                    <th>Address<div class="resizer3"></div></th>
                    <th>Contact Number<div class="resizer3"></div></th>
                    <th>Contact Person<div class="resizer3"></div></th>
                    <th>Contact Person Number<div class="resizer3"></div></th>
                    <th>Type<div class="resizer3"></div></th>
                    <th>Actions<div class="resizer3"></div></th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'connect.php';

                // Fetch patients from the database
                $sql = "SELECT * FROM patient";
                $result = $conn->query($sql);

                // Output each patient as a table row
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $datetime = new DateTime($row["p_bday"]);
                        $formattedDate = $datetime->format('F j, Y');
                        echo "<tr>
                                <td>" . $row["p_name"] . "</td>
                                <td>" . $row["p_age"] . "</td>
                                <td>" . $formattedDate . "</td>
                                <td>" . $row["p_address"] . "</td>
                                <td>" . $row["p_contnum"] . "</td>
                                <td>" . $row["p_contper"] . "</td>
                                <td>" . $row["p_contnumper"] . "</td>
                                <td>" . $row["p_type"] . "</td>
                                <td>
                                    <a href='#' class='edit-btn' onclick='openEditPatient(" . $row["p_id"] . ", \"" . addslashes($row["p_name"]) . "\", \""  . $row["p_bday"] . "\", \"" . addslashes($row["p_address"]) . "\", \"" . addslashes($row["p_contnum"]) . "\", \"" . addslashes($row["p_contper"]) . "\", \"" . addslashes($row["p_contnumper"]) . "\", \"" . addslashes($row["p_type"]) . "\")'><img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'></a>
                                    <!---
                                    <a href='#' class='delete-btn' onclick='deletePatient(" . $row["p_id"] . ", \"" . addslashes($row["p_name"]) . "\", \""  . $row["p_bday"] . "\", \"" . addslashes($row["p_address"]) . "\", \"" . addslashes($row["p_contnum"]) . "\", \"" . addslashes($row["p_contper"]) . "\", \"" . addslashes($row["p_contnumper"]) . "\", \"" . addslashes($row["p_type"]) . "\")'><img src='delete_icon.png' alt='Delete' style='width: 20px; height: 20px;'></a>
                                     -->
                                    </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No patients found</td></tr>";
                }
                
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</section>

<!-- MODALS SECTION --> 

<!-- Add Patient Modal -->
<div id="addPatientModal" class="modal3">
    <div class="modal-content3">
        <span class="close" onclick="closeAddPatientModal()">&times;</span>
        <h3>Add New Patient</h3>
        <form id="addpatient" onsubmit="submitPatientForm(event)">
            <label for="p_name">Name:</label>
            <input type="text" id="p_name" name="p_name" placeholder="SURNAME, FN, MN" required><br><br>
            
            <!-- <label for="p_age">Age:</label>
            <input type="number" id="p_age" name="p_age" required><br><br> -->
            
            <label for="p_bday">Birthday:</label>
            <input type="date" id="p_bday" name="p_bday" required><br><br>
            
            <label for="p_address">Address:</label>
            <input type="text" id="p_address" name="p_address" required><br><br>
            
            <label for="p_contnum">Contact Number:</label>
<input type="number" id="p_contnum" name="p_contnum" required><br><br>
            
            <label for="p_contper">Contact Person:</label>
            <input type="text" id="p_contper" name="p_contper" required><br><br>
            
            <label for="p_contnumper">Contact Person Number:</label>
<input type="number" id="p_contnumper" name="p_contnumper" required><br><br>
            
            <label for="p_type">Patient Type:</label>
            <select id="p_type" name="p_type" required>
                <option value="">Select Patient Type</option>
                <option value="Pedia">Pedia</option>
                <option value="Senior">Senior</option>
                <option value="PWD">PWD</option>
                <option value="OPD">OPD</option>
            </select><br><br>
            
            <input type="submit" value="Submit">
        </form>
    </div>
</div>

<!-- Edit Patient Modal -->
<div id="editPatientModal" class="modal3">
    <div class="modal-content3">
        <span class="close" onclick="closeEditPatientModal()">&times;</span>
        <h3>Edit Patient</h3>
        <form id="editPatientForm" onsubmit="submitEditPatientForm(event)">
            <input type="hidden" id="editPatientId" name="patientId">
            
            <label for="editName">Name:</label>
            <input type="text" id="editName" name="name" required><br><br>
            
            <!-- <label for="editAge">Age:</label>
            <input type="number" id="editAge" name="age" required><br><br> -->
            
            <label for="editBirthday">Birthday:</label>
            <input type="date" id="editBirthday" name="birthday" required><br><br>
            
            <label for="editAddress">Address:</label>
            <input type="text" id="editAddress" name="address" required><br><br>
            
            <label for="editContactNumber">Contact Number:</label>
            <input type="text" id="editContactNumber" name="contactNumber" required><br><br>
            
            <label for="editContactPerson">Contact Person:</label>
            <input type="text" id="editContactPerson" name="contactPerson" required><br><br>
            
            <label for="editContactPersonNumber">Contact Person Number:</label>
            <input type="text" id="editContactPersonNumber" name="contactPersonNumber" required><br><br>
            
            <label for="editType">Patient Type:</label>
            <select id="editType" name="type" required>
                <option value="">Select Patient Type</option>
                <option value="Pedia">Pedia</option>
                <option value="Senior">Senior</option>
                <option value="PWD">PWD</option>
                <option value="OPD">OPD</option>
            </select><br><br>
            
            <input type="submit" value="Submit">
        </form>
    </div>
</div>


<script>
    

    var addPatientModal = document.getElementById("addPatientModal")
    var editPatientModal = document.getElementById("editPatientModal")


    function updateDashboard() {
        fetch('DASHBOARD/get_dashboard_counts.php')
        .then(response => response.text())  // Fetch the raw text response
        .then(text => {
            console.log('Raw Response Text:', text);  // Log the raw text for debugging
            
            // Parse the raw text into a JSON object
            const data = JSON.parse(text);
            
            // Update the counts on the dashboard
            document.getElementById('total-patients').textContent = data.total_patients || 0;
            document.getElementById('total-medicines').textContent = data.total_meds || 0;
            document.getElementById('appointments-today').textContent = data.total_appointments || 0;
            document.getElementById('total-medications').textContent = data.total_medications || 0;
        })
        .catch(error => {
            console.error('Error fetching dashboard data:', error);
            // Fallback to '0' in case of error
            document.getElementById('total-patients').textContent = 0;
            document.getElementById('total-medicines').textContent = 0;
            document.getElementById('appointments-today').textContent = 0;
            document.getElementById('total-medications').textContent = 0;
        });
    }
//PATIENTS
function submitPatientForm(event) {
    event.preventDefault(); 

    var formData = new FormData(document.getElementById('addpatient')); 

    fetch('PATIENTLIST/patientprocess.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // Change to response.text()
    .then(text => {
        console.log('Success:', text);
        
        try {
            const data = JSON.parse(text); // Parse the text response to JSON
            if (data.error) {
                alert('Error: ' + data.error);
            } else {
                if (Array.isArray(data.patients)) {
                    updatePatientTable(data.patients);
                    updateDashboard();
                } else {
                    console.error('Expected an array of patients but got:', data.patients);
                    alert('Failed to retrieve patient data.');
                }
                closeAddPatientModal();
            }
        } catch (e) {
            console.error('Parsing error:', e);
            alert('Failed to parse response. Please try again.');
        }
    })
    .catch(error => console.error('Error submitting form:', error));
}

// Function to update the patient table with new data
// Function to update the patient table with new data
function updatePatientTable(patients) {
    var tableBody = document.querySelector('#patientTable tbody');
    tableBody.innerHTML = ''; // Clear existing table rows

    if (patients.length > 0) {
        patients.forEach(patient => {
            // Format the birthday for display
            const date = new Date(patient.p_bday);
            const formattedDate = date.toLocaleDateString('en-US', {
                year: 'numeric', month: 'long', day: 'numeric'
            });

            var row = document.createElement('tr');
            row.innerHTML = `
                <td>${htmlspecialchars(patient.p_name)}</td>
                <td>${htmlspecialchars(patient.p_age)}</td>
                <td>${formattedDate}</td> <!-- Display formatted birthday -->
                <td>${htmlspecialchars(patient.p_address)}</td>
                <td>${htmlspecialchars(patient.p_contnum)}</td> <!-- Updated contact number -->
                <td>${htmlspecialchars(patient.p_contper)}</td>
                <td>${htmlspecialchars(patient.p_contnumper)}</td> <!-- Updated contact person number -->
                <td>${htmlspecialchars(patient.p_type)}</td>
                <td>
                    <a href='#' class='edit-btn' onclick='openEditPatient(${patient.p_id}, "${addslashes(patient.p_name)}", "${patient.p_bday}", "${addslashes(patient.p_address)}", "${addslashes(patient.p_contnum)}", "${addslashes(patient.p_contper)}", "${addslashes(patient.p_contnumper)}", "${addslashes(patient.p_type)}")'>
                        <img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'>
                    </a>
                    <a href='#' class='delete-btn' onclick='deletePatient(${patient.p_id})'>
                        <img src='delete_icon.png' alt='Delete' style='width: 20px; height: 20px;'>
                    </a>
                </td>
            `;
            tableBody.appendChild(row);
        });
    } else {
        tableBody.innerHTML = '<tr><td colspan="8">No patients found</td></tr>'; // Updated colspan
    }
}

// Function to escape HTML for security
// Function to escape HTML for security
function htmlspecialchars(str) {
    if (str === undefined || str === null) return ''; // Return an empty string for undefined or null
    return str.replace(/&/g, '&amp;')
              .replace(/</g, '&lt;')
              .replace(/>/g, '&gt;')
              .replace(/"/g, '&quot;')
              .replace(/'/g, '&#039;');
}

// Function to escape single quotes for JS
function addslashes(str) {
    return str.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
}


// Function to close the add patient modal
function closeAddPatientModal() {
    var addPatientModal = document.getElementById('addPatientModal'); // Ensure you have this element
    if (addPatientModal) {
        addPatientModal.style.display = 'none';
    }
}


// Function to open the add patient modal
function openAddPatientModal() {
    if (addPatientModal) {
        addPatientModal.style.display = 'block'; 
    }
}


//UPDATE FUNCTION
// Function to open the edit patient modal and populate it with data
function openEditPatient(patientId, name, birthday, address, contactNumber, contactPerson, contactPersonNumber, type) {
    document.getElementById('editPatientId').value = patientId;
    document.getElementById('editName').value = name;
    document.getElementById('editBirthday').value = birthday;
    document.getElementById('editAddress').value = address;
    document.getElementById('editContactNumber').value = contactNumber; // Added contact number
    document.getElementById('editContactPerson').value = contactPerson;
    document.getElementById('editContactPersonNumber').value = contactPersonNumber; // Added contact person number
    document.getElementById('editType').value = type;

    // Show the modal
    var editPatientModal = document.getElementById('editPatientModal');
    editPatientModal.style.display = 'block';
}


// Function to close the edit patient modal
function closeEditPatientModal() {
    var editPatientModal = document.getElementById('editPatientModal');
    editPatientModal.style.display = 'none';
}

// Function to handle the form submission for editing a patient
function submitEditPatientForm(event) {
    event.preventDefault(); 

    var formData = new FormData(document.getElementById('editPatientForm')); 

    fetch('PATIENTLIST/update_patient.php', { 
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) 
    .then(text => {
        let data;
        try {
            data = JSON.parse(text); 
        } catch (error) {
            throw new Error('Failed to parse JSON response: ' + error.message);
        }

        console.log('Success:', data); 
        if (data.error) {
            alert('Error: ' + data.error); 
        } else {
            updatePatientTable(data.patients); 
            closeEditPatientModal(); 
            updateDashboard();
        }
    })
    .catch(error => console.error('Error submitting form:', error)); 
}

//Delete function
function deletePatient(patientId) {
    if (confirm('Are you sure you want to delete this patient?')) {
        fetch('PATIENTLIST/delete_patient.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'patientId': patientId
            })
        })
        .then(response => {
        
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                throw new Error('Unexpected content type: ' + contentType);
            }
        })
        .then(data => {
            if (data.success) {
                updatePatientTable(data.patients); 
                updateDashboard(); 
                document.querySelector(`#patientRow${patientId}`).remove();
                
            } else {
                alert('Error: ' + data.message); 
            }
        })
        .catch(error => console.error('Error deleting record:', error)); 
    }
}

document.querySelectorAll('#patientTable th .resizer3').forEach(resizer => {
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