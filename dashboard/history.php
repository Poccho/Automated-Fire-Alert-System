<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ..\index.php");
    exit();
}
?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <title>AFAS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./css/style.css" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css"
    />
    <script src="./js/downloadCSV.js" defer></script>

  </head>
  <body>

<?php
 include "navBar.php";
?>

    <div class="buttons">
        <button id="exportButton" class="print" onclick="downloadFilteredTableAsCSV()">Download CSV</button>
        <input id="searchInput" class="search-input" oninput="filterTable()" placeholder="Search"></input>
    </div>
    <section class="charts">
    <div class="history">
      <table id="dataTable">
      <thead id="thead">
        <tr>
          <th id="th">Barangay</th>
          <th id="th">Cause</th>
          <th id="th">Time</th>
          <th id="th">Coorinates</th>
        </tr>
      </thead>
      <tbody>
        <?php

        include "db/connection.php";

        // Fetch data from the database
        $sql = "SELECT * FROM incident_data";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["barangay"] . "</td>";
                echo "<td>" . $row["cause"] . "</td>";
                echo "<td>" . $row["time"] . "</td>";
                echo "<td>" . $row["coordinates"] . "</td>";
                echo "</tr>";
            }
        } else {
          echo "<tr><td colspan='4' style='text-align: center;'>NO RECORDS FOUND</td></tr>";
        }
        $conn->close();
        ?>
      </tbody>
      </table>
    </div>
    </section>
  </body>
</html>
