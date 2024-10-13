
// Function to open the medical supply archive modal
function openMedicalSupplyModal() {
    document.getElementById('medicalSupplyModal').style.display = 'block';
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
    event.preventDefault(); 
    
    var formData = new FormData(document.getElementById('addMedicalSupplyForm'));

    fetch('MEDICAL_SUPPLY/add_medical_supply.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())  // Get the raw text response
    .then(text => {
        console.log('Raw response:', text);  // Log the raw response
        try {
            var data = JSON.parse(text);  // Convert text to JSON
            console.log('Parsed JSON:', data);
            
            if (data.error) {
                alert('Error: ' + data.error);
            } else {
                updateMedicalSupplyTable(data.data); 
                closeAddMedicalSupplyModal(); 
                updateDashboard();
            }
        } catch (error) {
            console.error('Error parsing JSON:', error);
            alert('Error: Invalid JSON response');
        }
    })
    .catch(error => console.error('Error submitting form:', error));
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
    event.preventDefault(); 

    var formData = new FormData(document.getElementById('editForm'));

    fetch('MEDICAL_SUPPLY/update_supply.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())  // Get the raw text response
    .then(text => {
        console.log('Raw response:', text); // Log the raw response text
        
        // Parse the text as JSON
        try {
            const data = JSON.parse(text);  // Parse JSON
            console.log('Parsed Success:', data.data);
            
            if (data.error) {
                alert('Error: ' + data.error);
            } else {
                updateMedicalSupplyTable(data.data); 
                closeEditModal(); 
                updateDashboard();
            }
        } catch (e) {
            console.error('Error parsing JSON:', e);
            alert('Failed to parse response as JSON.');
        }
    })
    .catch(error => console.error('Error submitting form:', error));
}

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
    