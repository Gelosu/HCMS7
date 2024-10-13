

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
                    <a href='#' class='edit-btn' onclick='openEditPatient(${patient.p_id}, "${addslashes(patient.p_name)}", "${patient.p_age}", "${patient.p_bday}", "${addslashes(patient.p_address)}", "${addslashes(patient.p_contnum)}", "${addslashes(patient.p_contper)}", "${addslashes(patient.p_contnumper)}", "${addslashes(patient.p_type)}")'>
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
function openEditPatient(patientId, name, age, birthday, address, contactNumber, contactPerson, contactPersonNumber, type) {
    document.getElementById('editPatientId').value = patientId;
    document.getElementById('editName').value = name;
    document.getElementById('editAge').value = age;
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