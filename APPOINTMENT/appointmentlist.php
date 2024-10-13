    <!-- Patient Appointment section -->
    <section id="patient-appointment" class="section">
    <h2>Patient Appointment</h2>
    <div class="search-and-add-container">
    <!-- Search bar container -->
    <div class="search-container">
        <input type="text" id="searchInput" onkeyup="searchTable4(this.value);" placeholder="Search for appointments...">
    </div>

    <!-- Button container -->
    <div class="add-button-container">
        <button onclick="openAddAppointmentModal()">Add Appointment</button>
    </div>
    </div>

<!-- Add Appointment Modal -->
<div id="addAppointmentModal" class="modal4">
    <div class="modal-content4">
        <span class="close" onclick="closeAddAppointmentModal()">&times;</span>
        <h3>Add Appointment</h3>
        <form id="addAppointmentForm" onsubmit="submitAddAppointmentForm(event)">
            <label for="patientName">Name of Registered Patient:</label>
            <select id="patientName" name="patientName" required>
                <!-- Options will be populated dynamically -->
            </select>
            
            <label for="purpose">Purpose:</label>
            <select id="purpose" name="purpose" required>
                <option value="Consultation">Consultation</option>
                <option value="Follow-Up">Follow-Up</option>
                <option value="Emergency">Emergency</option>
                <!-- Add more options as needed -->
            </select>
            
            <label for="appointmentDateTime">Date and Time:</label>
            <input type="datetime-local" id="appointmentDateTime" name="appointmentDateTime" required>
            
            <label for="healthWorker">Assigned Healthworker:</label>
            <input type="text" id="healthWorker" name="healthWorker" readonly>
            
            <input type="submit" value="Submit">
            
        </form>
    </div>
</div>


<!--  Edit Appointment Modal -->
<div id="editAppointmentModal" class="modal4">
    <div class="modal-content4">
        <span class="close" onclick="closeEditAppointmentModal()">&times;</span>
        <h3>Edit Appointment</h3>
        <form id="editAppointmentForm" onsubmit="submitEditAppointmentForm(event)">
            
            <!-- Display patient name as non-editable -->
            <label for="editPatientName">Name of Patient:</label>
            <input type="text" id="editPatientName" name="editPatientName" readonly>
            
            <label for="editPurpose">Purpose:</label>
            <select id="editPurpose" name="editPurpose" required>
                <option value="Consultation">Consultation</option>
                <option value="Follow-Up">Follow-Up</option>
                <option value="Emergency">Emergency</option>
                <!-- Add more options as needed -->
            </select>
            
            <label for="editAppointmentDateTime">Date and Time:</label>
            <input type="datetime-local" id="editAppointmentDateTime" name="editAppointmentDateTime" required>
            
            <!-- Health worker field set to readonly -->
            <label for="editHealthWorker">Assigned Healthworker:</label>
            <input type="text" id="editHealthWorker" name="editHealthWorker" readonly>
            
            <!-- Hidden field for appointment ID -->
            <input type="hidden" id="editAppointmentId" name="editAppointmentId">
            
            <input type="submit" value="Submit">
           
        </form>
    </div>
</div>





    <!-- Appointments Table -->
    <div class="table-container">
<table id="appointmentsTable">
    <thead>
        <tr>
            <th>Name of Patient<div class="resizer4"></div></th>
            <th>Purpose<div class="resizer4"></div></th>
            <th>Date<div class="resizer4"></div></th>
            <th>Assigned Healthworker<div class="resizer4"></div></th>
            <th>Actions<div class="resizer4"></div></th>
        </tr>
    </thead>
    <tbody>
    <?php
    include 'connect.php'; 

    $sql = "SELECT * FROM p_appointment";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Format date and time
            $datetime = new DateTime($row["datetime"]);
            $formattedDate = $datetime->format('F j, Y');
            $formattedTime = $datetime->format('g:i A');
            $formattedDateTime = $formattedDate . ' ' . $formattedTime;

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["p_name"]) . "</td>"; 
            echo "<td>" . htmlspecialchars($row["p_purpose"]) . "</td>"; 
            echo "<td>" . $formattedDateTime . "</td>"; 
            echo "<td>" . htmlspecialchars($row["a_healthworker"]) . "</td>"; 
            echo "<td class='action-icons'>";
            echo "<a href='#' class='edit-btn' onclick=\"openEditAppointmentModal('" . 
            $row["id"] . "', '" . 
            htmlspecialchars($row["p_name"]) . "', '" . 
            htmlspecialchars($row["p_purpose"]) . "', '" . 
            htmlspecialchars($row["datetime"]) . "', '" . 
            htmlspecialchars($row["a_healthworker"]) . "')\">";
            echo "<img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'></a>";
            
            echo "<a href='#' class='delete-btn' onclick=\"deleteAppointment('" . $row["id"] . "')\">";
            echo "<img src='delete_icon.png' alt='Delete' class='delete-btn' style='width: 20px; height: 20px;'></a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No appointments found</td></tr>";
    }

    $conn->close();
    ?>
    </tbody>
</table>
</div>


<script>
    //APPOINTMENT 

// Function to open the Add Appointment modal
function openAddAppointmentModal() {
    // Fetch patient names and populate dropdown
    fetch('APPOINTMENT/fetch_patient.php')
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                console.log("check result: ", result.data);
                populatePatientDropdown2(result.data);
                // Set the health worker's name
                document.getElementById('healthWorker').value = healthWorkerName;
                document.getElementById('addAppointmentModal').style.display = 'block';
            } else {
                alert('Error fetching patients: ' + result.error);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Function to populate the patient dropdown
function populatePatientDropdown2(patients) {
    var dropdown = document.getElementById('patientName');
    dropdown.innerHTML = ''; // Clear existing options

    var defaultOption = document.createElement('option');
    defaultOption.text = 'Select a patient';
    defaultOption.value = '';
    dropdown.add(defaultOption);

    // Iterate over the list of patient names
    patients.forEach(patient => {
        var option = document.createElement('option');
        option.text = patient; // Use the patient name directly
        option.value = patient; // Use the patient name directly as the value
        dropdown.add(option);
    });
}

// Function to close the Add Appointment modal
function closeAddAppointmentModal() {
    document.getElementById('addAppointmentModal').style.display = 'none';
}

// Function to handle form submission for adding an appointment
function submitAddAppointmentForm(event) {
    event.preventDefault();

    // Append health worker to form data
    var formData = new FormData(document.getElementById('addAppointmentForm'));
    formData.append('healthWorker', healthWorkerName);

    fetch('APPOINTMENT/add_appointment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            closeAddAppointmentModal();
            updateAppointmentTable(result.data);
            updateDashboard(); // Refresh the table with the updated data
        } else {
            alert('Error: ' + result.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Function to format date and time
function formatDateTime(datetime) {
    const date = new Date(datetime);
    // Format date to "Month Day, Year Time AM/PM"
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true
    };
    return date.toLocaleString('en-US', options);
}

// Function to update the appointment table
function updateAppointmentTable(appointments) {
    var tableBody = document.querySelector('#patient-appointment table tbody');
    tableBody.innerHTML = ''; // Clear existing rows

    appointments.forEach(appointment => {
        // Format datetime before displaying
        const formattedDateTime = formatDateTime(appointment.datetime);

        var row = document.createElement('tr');
        row.innerHTML = `
            <td>${appointment.p_name}</td>
            <td>${appointment.p_purpose}</td>
            <td>${formattedDateTime}</td>
            <td>${appointment.a_healthworker}</td>
            <td>
                <a href='#' class='edit-btn' onclick="openEditAppointmentModal(
                    '${appointment.id}',
                    '${appointment.p_name}',
                    '${appointment.p_purpose}',
                    '${appointment.datetime}',
                    '${appointment.a_healthworker}'
                )">
                    <img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'>
                </a>
                <a href='#' class='delete-btn' onclick="deleteAppointment('${appointment.id}')">
                    <img src='delete_icon.png' alt='Delete' style='width: 20px; height: 20px;'>
                </a>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

// Function to open the Edit Appointment modal
function openEditAppointmentModal(id, patientName, purpose, datetime, healthWorker) {
    console.log(id, patientName, purpose, datetime, healthWorker);

    document.getElementById('editAppointmentId').value = id;
    document.getElementById('editPatientName').value = patientName;
    document.getElementById('editPurpose').value = purpose;

    // Convert datetime to 'YYYY-MM-DDTHH:MM' format
    const formattedDateTime = new Date(datetime).toISOString().slice(0, 16);
    document.getElementById('editAppointmentDateTime').value = formattedDateTime;

    document.getElementById('editHealthWorker').value = healthWorker;
    document.getElementById('editAppointmentModal').style.display = 'block';
}

// Function to close the modal
function closeEditAppointmentModal() {
    document.getElementById('editAppointmentModal').style.display = 'none';
}

// Function to handle the form submission for editing an appointment
function submitEditAppointmentForm(event) {
    event.preventDefault(); // Prevent the default form submission

    console.log("Submitting form...");

    const id = document.getElementById('editAppointmentId').value;
    const patientName = document.getElementById('editPatientName').value;
    const purpose = document.getElementById('editPurpose').value;
    const appointmentDateTime = document.getElementById('editAppointmentDateTime').value;
    const healthWorker = document.getElementById('editHealthWorker').value;

    console.log("Form data:", { id, patientName, purpose, appointmentDateTime, healthWorker });

    const xhr = new XMLHttpRequest();
    xhr.open("GET", `/HCMS/APPOINTMENT/edit_appointment.php?editAppointmentId=${encodeURIComponent(id)}&editPatientName=${encodeURIComponent(patientName)}&editPurpose=${encodeURIComponent(purpose)}&editAppointmentDateTime=${encodeURIComponent(appointmentDateTime)}&editHealthWorker=${encodeURIComponent(healthWorker)}`, true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            console.log("Response:", xhr.responseText);
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                updateAppointmentTable(response.appointments);
                updateDashboard();
                alert(response.message);
                document.getElementById('editAppointmentModal').style.display = 'none';
            } else {
                alert(response.message);
            }
        } else {
            alert('Error: ' + xhr.statusText);
        }
    };

    xhr.onerror = function() {
        alert('Request failed.');
    };

    xhr.send();
}

// Delete function
function deleteAppointment(appointmentId) {
    if (confirm('Are you sure you want to delete this appointment?')) {
        fetch('APPOINTMENT/delete_appointment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'appointmentId': appointmentId
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
                updateAppointmentTable(data.appointments); // Refresh the table
                updateDashboard();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error deleting record:', error));
    }
}

</script>