<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['adusername'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch user info from session
$adfirstname = $_SESSION['adfirstname'];
$adsurname = $_SESSION['adsurname'];
$healthWorker = $_SESSION['adfirstname'] . ' ' . $_SESSION['adsurname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STA. MARIA HCMS</title>
    <link rel="stylesheet" href="mamamoadmin.css">
    <link rel="stylesheet" href="style3.css">
   
</head>
<body>

<header>
    <h1>BRGY STA. MARIA HEALTH CENTER</h1>
</header>

<!-- SIDE BAR -->
<div id="sidebar">
    <div id="logo">
        <img src="mary.jpg" alt="Logo">
    </div>
    
    <p class="healthworker-info">
    <span class="healthworker-label">HEALTH WORKER:</span>
    <span class="healthworker-name"><?php echo htmlspecialchars($adfirstname . ' ' . $adsurname); ?></span>
</p>

    
    <ul>
    <li><a href="#" onclick="setActiveSection('dashboard')">Dashboard</a></li>
        <li><a href="#" onclick="setActiveSection('medical_supplies-inventory')">Medical & Emergency Supplies Inventory</a></li>
        <li><a href="#" onclick="setActiveSection('medicine-inventory')">Medicine Inventory</a></li>
        <li><a href="#" onclick="setActiveSection('patient-list')">Patient List</a></li>
        <li><a href="#" onclick="setActiveSection('patient-records')">Patient Records</a></li>
        <li><a href="#" onclick="setActiveSection('patient-appointment')">Patient Appointment</a></li>
        <li><a href="#" onclick="setActiveSection('patient-med')">Patient - Medication</a></li>

        
       
    </ul>
    <button id="logoutBtn">Logout</button>
</div>


<div id="content">
<section id="dashboard" class="section">
    <h2>DASHBOARD</h2>
    
    
    <div class="card-container">
        <div class="card">
            <h3>Total Registered Patients</h3>
            <p id="total-patients">Loading...</p>
        </div>
        <div class="card">
            <h3>Total Medicines</h3>
            <p id="total-medicines">Loading...</p>
        </div>
        <div class="card">
            <h3>Appointments Today</h3>
            <p id="appointments-today">Loading...</p>
        </div>
        <div class="card">
            <h3>Total Medical Supplies</h3>
            <p id="total-medications">Loading...</p>
        </div>
    </div>

    <div id="upcoming-events">
        <h2>Upcoming Events</h2>
        <div id="events-container" class="events-container"> <!-- Events container -->
           
        </div>
        <p id="no-events-message" style="display: none;">Events coming soon...</p>
    </div>
</section>


<?php
      
        include 'PATIENTLIST/patientlist.php';
        include 'MEDICAL_SUPPLY/medicalsupply.php';
        include 'MEDICINES/medicinelist.php';
        include 'APPOINTMENT/appointmentlist.php';
        include 'P_MEDICATION/pmedication.php';
?>

</div>
<script>const healthWorkerName = "<?php echo htmlspecialchars($healthWorker); ?>";</script>
<script src="SEARCH_FILTER.js"> </script>

<script> 




  // Function to set and activate the desired section based on navigation clicks
function setActiveSection(sectionId) {
    window.location.hash = sectionId;
    console.log("section id: ", sectionId)  // Set URL hash
    toggleSection(sectionId);  // Show the selected section
    updateDashboard()
    fetchMedicalSupplies2()
    fetchMedicines()
    updateMedicineTable2()
    updateMedicalSupplyTable2()
}

// Function to toggle visibility of sections
function toggleSection(sectionId) {
    var sections = document.querySelectorAll('.section');
    sections.forEach(function(section) {
        if (section.id === sectionId) {
            section.style.display = 'block';  // Show the selected section
        } else {
            section.style.display = 'none';  // Hide other sections
        }
    });
}

// Logout
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("logoutBtn").addEventListener("click", function() {
        window.location.href = "logout.php";
    });
});

// When the page loads, show the appropriate section based on the URL hash
window.onload = function() {
    var hash = window.location.hash.substring(1);  // Get hash value
    if (hash) {
        // Show the section corresponding to the hash
        toggleSection(hash);
    } else {
        // Default to 'dashboard' section if no hash is present
        setActiveSection('dashboard');
    }
};


function updateMedicalSupplyTable2() {
    fetch('MEDICAL_SUPPLY/getmedicalsupplies.php') // Adjust the path as necessary
        .then(response => response.json())
        .then(result => {
            console.log("results:   ", result.data)
            const tableBody = document.getElementById("medicalSuppliesModalArchiveTable").getElementsByTagName('tbody')[0];
            tableBody.innerHTML = ""; // Clear existing table rows

            if (result.success && result.data.length > 0) {
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

//MEDICINE LIST
function fetchMedicines() {
    fetch('MEDICINES/fetch_medicine.php') // Adjust the path as necessary
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                console.log(result.data)
                updateMedicineTable(result.data); // Call the function to update the table
            } else {
                console.error('Error fetching medicines:', result.message);
            }
        })
        .catch(error => console.error('Error:', error));
}





//DASHBOARD

function updateDashboard() {
    // Fetch dashboard counts
    fetch('DASHBOARD/get_dashboard_counts.php')
        .then(response => {
           
            return response.text(); // Fetch the raw text response
        })
        .then(text => {
            
            
            // Parse the raw text into a JSON object
            const data = JSON.parse(text);
            
            // Update the counts on the dashboard
            document.getElementById('total-patients').textContent = data.total_patients || 0;
            document.getElementById('total-medicines').textContent = data.total_meds || 0;
            document.getElementById('appointments-today').textContent = data.total_appointments || 0;
            document.getElementById('total-medications').textContent = data.total_medications || 0;

            // Fetch upcoming events
            return fetch('DASHBOARD/get_upcoming_events.php'); // Add this line
        })
        .then(response => {
          
            return response.text(); // Fetch the raw text response for events
        })
       // Inside the then block after fetching upcoming events
.then(text => {
    

    // Parse the JSON response
    const events = JSON.parse(text);
    
    const eventsContainer = document.getElementById('events-container');
    eventsContainer.innerHTML = '';  // Clear previous events


    if (events.length > 0) {
        events.forEach(event => {
            const eventCard = document.createElement('div');
            eventCard.className = 'event-card';
            eventCard.style.border = '1px solid #ccc';
            eventCard.style.borderRadius = '5px';
            eventCard.style.padding = '10px';
            eventCard.style.margin = '10px';
            eventCard.style.width = '300px';
            eventCard.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.1)';
            
            eventCard.innerHTML = `
                <h4>${event.event_name}</h4>
                <p><strong>Date:</strong> ${new Date(event.datetime).toLocaleString()}</p>
            `;
            
            eventsContainer.appendChild(eventCard); // Append the event card to the container
          
        });
    } else {
        eventsContainer.innerHTML = '<p id="no-events-message">Events coming soon...</p>'; // Show message if no events
    }
})

        .catch(error => {
            console.error('Error fetching dashboard data:', error);
        });
}

updateDashboard()









</script>
<footer>
    <p>&copy; 2024 BRGY STA. MARIA HEALTH CENTER. All rights reserved.</p>
</footer>

</body>
</html>