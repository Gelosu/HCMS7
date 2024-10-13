
// Function to search MEDICAL SUPPLIES
function searchTable1(inputValue) {
    var searchQuery = inputValue.toLowerCase().trim();
    var table = document.getElementById('medicalSuppliesTable');
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

// Function to search MEDICINE INVENTORY
function searchTable2(inputValue) {
    var searchQuery = inputValue.toLowerCase().trim();
    var table = document.getElementById('medTable'); 
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

function searchTable3(inputValue) {
    var searchQuery = inputValue.toLowerCase().trim();
    var table = document.getElementById('patientTable'); 
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

function searchTable4(inputValue) {
    var searchQuery = inputValue.toLowerCase().trim();
    var table = document.getElementById('appointmentsTable'); 
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

function searchTable6(inputValue) {
    var searchQuery = inputValue.toLowerCase().trim();
    
    // Get the dropdown value to determine which table is currently visible
    var archiveType = document.getElementById("archiveTypeSelect").value;
    var table;
    
    if (archiveType === "medicalsupply") {
        table = document.getElementById("medicalSuppliesArchiveTable");
    } else {
        table = document.getElementById("medicineArchiveTable");
    }

    var rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var cells = row.getElementsByTagName('td');
        var rowContainsQuery = false;

        // Check each cell in the row
        for (var j = 0; j < cells.length; j++) {
            var cellValue = cells[j].textContent || cells[j].innerText; 

            // If the cell contains the search query, mark the row as found
            if (cellValue.toLowerCase().indexOf(searchQuery) > -1) {
                rowContainsQuery = true;
                break; // No need to check other cells if one matches
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



//filter using Dropdown
function filterByCategory(selectedCategory) {
    const table = document.getElementById('patientTable'); 
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) { 
        const categoryCell = rows[i].getElementsByTagName('td')[5]; 

        if (categoryCell) {
            const category = categoryCell.textContent || categoryCell.innerText;

            if (selectedCategory === "" || category.toLowerCase() === selectedCategory.toLowerCase()) {
                rows[i].style.display = ""; 
            } else {
                rows[i].style.display = "none"; 
            }
        }
    }
}