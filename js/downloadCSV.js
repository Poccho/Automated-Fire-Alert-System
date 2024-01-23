function filterTable() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("dataTable");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        var found = false;
        for (j = 0; j < tr[i].getElementsByTagName("td").length; j++) {
            td = tr[i].getElementsByTagName("td")[j];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        tr[i].style.display = found ? "" : "none";
    }
}

function downloadFilteredTableAsCSV() {
    // Get the visible rows (not filtered out)
    var visibleRows = Array.from(document.querySelectorAll("#dataTable tbody tr:not([style='display: none;'])"));

    // Get table headers
    var headers = Array.from(document.querySelectorAll("#dataTable thead th")).map(th => th.innerText);

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
    link.click();

    // Clean up
    document.body.removeChild(link);
}