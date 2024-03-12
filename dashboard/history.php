<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: ..\index.php");
  exit();
}
?>

<html lang="en" dir="ltr">

<head>
<title>AFAS</title>
  <meta charset="utf-8" />
  <script src="./js/popup.js"></script>
  <script src="./js/downloadCSV.js"></script>
  <script src="js/previewMap.js"></script>
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" /></head>

<body>

  <?php include "navBar.php"; ?>

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
            <th id="th">Coordinates</th>
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
              echo "<tr onclick='openPopup(\"" . $row["barangay"] . "\", \"" . $row["cause"] . "\", \"" . $row["time"] . "\", \"" . addslashes($row["coordinates"]) . "\")'>";
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

    <div id="overlay">
      <div id="popup" class="reportForm">
        <form class="form" id="form" method="POST">
          <p class="title">Incident Data</p>
          <p class="message">Fill Up the necessary information needed</p>
          <label for="choices">
          <input id="barangay" name="barangay" value="" type="text" class="input" required disabled/>
            <span>Barangay</span>
          </label>
          <label>
            <input id="location" name="location" value="" type="text" class="input" required disabled/>
            <span>Location</span>
          </label>
          <label>
            <input id="address" name="address" value="" type="text" class="input" required disabled/>
            <span>Address</span>
          </label>
          <label>
            <input id="date" name="date" value="" type="text" class="input" required disabled/>
            <span>Date</span>
          </label>
          <label>
            <input id="time" name="time" value="" type="text" class="input" required disabled/>
            <span>Time</span>
          </label>
          <label for="choices">
          <input id="cause" name="cause" value="" type="text" class="input" required disabled/>
            <span>Cause</span>
          </label>
        </form>
        <div class="form">
          <button class="cancel" id="cancel" onclick="event.preventDefault(); closePopupDetails();">Close</button>
        </div>
      </div>
      <div class="previewMap">
        <div id="previewMap">
        </div>
      </div>
    </div>
  </section>

  <script>
function openPopup(barangay, cause, time, coordinates) {

  document.getElementById("barangay").value = barangay;

  // Extract location from coordinates string
  var location = coordinates.replace(/\(([^)]+)\)/, "$1");
  document.getElementById("location").value = location.trim(); // Trim any leading/trailing whitespace

  // Parse the time string to get date and time components
  var dateTime = new Date(time);
  var date = dateTime.toDateString(); // Get the date component
  var time = dateTime.toLocaleTimeString(); // Get the time component

  document.getElementById("date").value = date; // Set the date value
  document.getElementById("time").value = time; // Set the time value

  // Set the value of the input field for cause
  var causeInput = document.getElementById("cause");
  causeInput.value = cause;

  document.getElementById("overlay").style.display = "flex";
}


    function closePopupDetails() {
      document.getElementById("overlay").style.display = "none";
    }

    
  </script>

</body>

</html>
