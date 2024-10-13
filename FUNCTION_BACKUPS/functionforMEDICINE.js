    // Function to open the medicine archive modal
    function openMedicineModal() {
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
        
                // Check if there is an error in the response
                if (data.error) {
                    alert('Error: ' + data.error);
                } else {
                    // Update the medicine table with the new data
                    updateMedicineTable(data.data);
                    
                    // Close the modal form after successful submission
                    closeAddMedicineModal();
                    updateDashboard();
                }
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
    
    
        // Populate the form fields in the edit modal
        document.getElementById('editMedId').value = medId;
        document.getElementById('editMedNumber').value = medNumber; // Added this line
        document.getElementById('editMedName').value = medName;
        document.getElementById('editMedDesc').value = medDesc;
        document.getElementById('editStockIn').value = stockIn;
        document.getElementById('editStockExp').value = stockExp; // Ensure this is a date format
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