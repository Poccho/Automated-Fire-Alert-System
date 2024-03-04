function filterTable() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("dataTable");
    tr = table.getElementsByTagName("tr");

    // Check if the search input is empty
    if (filter === "") {
        // If empty, display all rows in the table
        for (i = 0; i < tr.length; i++) {
            tr[i].style.display = "";
        }
        // Remove any existing "No results found" rows
        var noResultRows = table.querySelectorAll(".no-result-row");
        noResultRows.forEach(function(row) {
            row.remove();
        });
        return; // Exit the function early
    }

    var resultsFound = false;

    for (i = 0; i < tr.length; i++) {
        var found = false;
        if (i === 0) {
            tr[i].style.display = "";
            continue;  // Skip the header row
        }
        for (j = 0; j < tr[i].getElementsByTagName("td").length; j++) {
            td = tr[i].getElementsByTagName("td")[j];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    resultsFound = true;
                    break;
                }
            }
        }
        tr[i].style.display = found ? "" : "none";
    }

    if (!resultsFound) {
        // If no results found, add a row to the table to display a message
        var noResultRow = table.insertRow(table.rows.length);
        noResultRow.classList.add("no-result-row"); // Add a class to identify the row
        var cell = noResultRow.insertCell(0);
        cell.colSpan = table.rows[0].cells.length; // Span the entire row
        cell.textContent = "No results found.";
        cell.style.textAlign = "center"; // Center the text

    }
    // If results are found, remove the no result row if it exists
    else {
        var noResultRows = table.querySelectorAll(".no-result-row");
        noResultRows.forEach(function(row) {
            row.remove();
        });
    }
}




function downloadFilteredTableAsCSV() {
    var table = document.getElementById("dataTable");

    // Check if there are visible rows (not filtered out)
    var visibleRows = Array.from(table.querySelectorAll("tbody tr:not([style='display: none;'])"));

    if (visibleRows.length === 0) {
        alert("No results found. Cannot download empty table.");
        return;
    }

    // Get table headers
    var headers = Array.from(table.querySelectorAll("thead th")).map(th => th.innerText);

    // Process each visible row
    var csvContent = headers.join(",") + "\n";
    visibleRows.forEach(row => {
        var rowData = Array.from(row.querySelectorAll("td")).map(td => {
            // Wrap the field with double quotes if it contains commas
            if (td.innerText.includes(',')) {
                return '"' + td.innerText + '"';
            } else {
                return td.innerText;
            }
        });
        csvContent += rowData.join(",") + "\n";
    });

    // Add a line to auto-adjust column widths
    csvContent += "\n";

    // Create a Blob and set it as a data URI for the link
    var blob = new Blob([csvContent], { type: "text/csv;charset=utf-8" });
    var url = URL.createObjectURL(blob);

    // Create a hidden link and trigger a click to download the file
    var link = document.createElement("a");
    link.setAttribute("href", url);
    link.setAttribute("download", "filtered_table_data.csv");
    document.body.appendChild(link);
    
    // Trigger the click only if the link is appended to the body
    link.click();

    // Clean up
    document.body.removeChild(link);
}


