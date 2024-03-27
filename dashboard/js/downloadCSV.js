
function downloadFilteredTableAsCSV() {
  var table = document.getElementById("dataTable");

  // Check if there are visible rows (not filtered out)
  var visibleRows = Array.from(
    table.querySelectorAll("tbody tr:not([style='display: none;'])")
  );

  if (visibleRows.length === 0) {
    alert("No results found. Cannot download empty table.");
    return;
  }

  // Get table headers
  var headers = Array.from(table.querySelectorAll("thead th")).map(
    (th) => th.innerText
  );

  // Process each visible row
  var csvContent = headers.join(",") + "\n";
  visibleRows.forEach((row) => {
    var rowData = Array.from(row.querySelectorAll("td")).map((td) => {
      // Wrap the field with double quotes if it contains commas
      if (td.innerText.includes(",")) {
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
