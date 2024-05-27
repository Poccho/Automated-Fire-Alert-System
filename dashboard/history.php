<?php
include("db/session.php");
?>

<html lang="en" dir="ltr">

<head>
  <title>AFAS</title>
  <meta charset="utf-8" />
  <script src="./js/downloadCSV.js"></script>
  <link rel="stylesheet" href="./css/style.css" />
  <link rel="stylesheet" href="./css/history.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" />
</head>
<style>
  th {
    background-color: #cc3333;
    /* Darker red for table header */
    color: #fff;
  }

  tr:nth-child(even) {
    background-color: #ffe6e6;
    /* Light red for even rows */
  }

  tr:nth-child(odd) {
    background-color: #fff;
    /* White for odd rows */
  }

  .center {
    text-align: center;
  }

  /* Style the anchor tag for entire row click */
  .row-link {
    cursor: pointer;
  }
</style>

<body>

  <?php include "navBar.php"; ?>

  <div class="buttons">
    <div style="display: flex; align-items: center;">
      <button id="exportButton" class="print" onclick="downloadFilteredTableAsCSV()">Download CSV</button>
      <input id="searchInput" class="search-input" oninput="filterTable()" placeholder="Search">
    </div>
  </div>



  <section class="charts">
    <div class="history" style="height:79vh; overflow: auto;">
      <table id="dataTable">
        <thead id="thead">
          <tr></tr>
            <th>Barangay</th>
            <th>Type of Incident</th>
            <th>Date</th>
            <th>Alarm Time</th>
          </tr>
        </thead>
        <tbody><?php
        include "db/connection.php";

        $sql = "SELECT 
                        i.*, 
                        b.barangay_name
                    FROM 
                        report AS i
                    JOIN 
                        barangay AS b ON i.barangay_no = b.barangay_code";
        $result = $conn->query($sql);

        if ($result) {
          if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
              // Output each row with a class for row-click handling
              echo "<tr class='row-link' data-incident-no='" . $row["incident_no"] . "'>";
              echo "<td style='width: 17%;'>" . $row["barangay_name"] . "</td>";
              echo "<td style='width: 17%;'>" . $row["type_of_incident"] . "</td>";
              echo "<td style='width: 12%;'>" . $row["date"] . "</td>";
              echo "<td style='width: 20%;'>" . $row["alarm_time"] . "</td>";
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='4' style='text-align: center;'>NO RECORDS FOUND</td></tr>";
          }
        } else {
          echo "Error: " . $conn->error;
          echo "<br>Query: " . $sql;
        }

        $conn->close();
        ?>

        </tbody>
      </table>

    </div>


  </section>
  <script>
    // Add click event listener to table rows
    document.querySelectorAll('.row-link').forEach(row => {
      row.addEventListener('click', function() {
        // Get the incident number from the data attribute
        const incidentNo = this.getAttribute('data-incident-no');
        // Redirect to report.php with incident number as parameter
        window.open('reportPreview.php?incident_no=' + incidentNo, '_blank');
      });
    });

    function filterTable() {
      var searchInput = document.getElementById("searchInput").value;
      $.ajax({
        type: "POST",
        url: "db/filter.php",
        data: {
          search: searchInput
        },
        success: function(response) {
          $('#dataTable tbody').html(response);
        }
      });
    }
  </script>


</body>

</html>
