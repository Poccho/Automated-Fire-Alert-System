<?php
include "db/connection.php";

session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: ..\index.php");
  exit();
}

include "db/stats.php";
// Function to sanitize and validate input
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <title>AFAS</title>
  <meta charset="utf-8" />
  <script src="./js/popup.js"></script>
  <script src="./js/download.js"></script>
  <link rel="stylesheet" href="./css/style.css" />
  <link rel="stylesheet" href="./css/reportForm.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="js/previewMap.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" />
</head>

<body>
  <?php include "navBar.php"; ?>
  <div class="buttons">
    <button id="exportButton" class="print" onclick="exportToPDF()">Download</button>
    <button class="addreport" onclick="openPopup()">Add Record</button>
  </div>
  <section class="charts">

    <div class="svg-container">
      <!-- Your SVG -->
      <div class="hover-info"></div>
      <?php
      include "db/gscMap.php";
      ?>
    </div>
    <script>
      const paths = document.querySelectorAll('.svg-container svg path');
      const hoverInfo = document.querySelector('.hover-info');

      // Dynamic PHP script to generate pathInfo
      <?php
      // Your database connection code here
      $pdo = new PDO("mysql:host=localhost;dbname=4402151_alert", "root", "");

      $sql = "SELECT barangay, COUNT(*) AS occurrence_count FROM incident_data GROUP BY barangay";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $data = [];

      foreach ($results as $row) {
        $barangay = $row['barangay'];
        $count = $row['occurrence_count'];

        // You may customize the description as per your requirements
        $description = "Recorded Fire Outbreaks: $count";

        $data[$barangay] = [
          'title' => ucfirst($barangay),
          'description' => $description,
        ];
      }

      $jsonData = json_encode($data, JSON_PRETTY_PRINT);
      echo "const pathInfo = $jsonData;";
      ?>

      paths.forEach((path) => {
        path.addEventListener('mouseenter', (event) => {
          const pathId = event.target.getAttribute('id');

          const pathBounds = event.target.getBoundingClientRect();

          // Check if pathId exists in pathInfo before accessing its properties
          if (pathInfo[pathId]) {
            hoverInfo.innerHTML = `
                    <h2 class="infoTitle">${pathInfo[pathId].title}</h2>
                    <p class="infoDesc">${pathInfo[pathId].description}</p>
                `;
          } else {
            hoverInfo.innerHTML = `
                    <h2 class="infoTitle">${pathId}</h2>
                    <p class="infoDesc">No Saved Record</p>`;
          }

          hoverInfo.style.display = 'block';
          hoverInfo.style.top = `${pathBounds.top + window.scrollY - hoverInfo.offsetHeight}px`;
          hoverInfo.style.left = `${pathBounds.left}px`;
        });

        path.addEventListener('mouseleave', () => {
          hoverInfo.style.display = 'none';
        });
      });
    </script>

    <div class="barchart" id="linechart">
      <canvas id="fireOccurrencesChart" width="800" height="400"></canvas>
    </div>
    <script src="js/chart.js"></script>


    <div id="overlay">
      <div id="popup" class="reportForm">
        <form class="form" id="form" method="POST">
          <p class="title">Add Report</p>
          <p class="message">Fill Up the necessary information needed</p>
          <label>
            <input id="location" name="location" placeholder="" type="text" class="input" required />
            <span>Location</span>
          </label>
          <label for="choices">
            <div class="custom-dropdown">
              <select id="barangay" name="barangay" class="input" required>
                <option value=""></option>
                <option value="Apopong">Apopong</option>
                <option value="Baluan">Baluan</option>
                <option value="Batomelong">Batomelong</option>
                <option value="Buayan">Buayan</option>
                <option value="Bula">Bula</option>
                <option value="Calumpang">Calumpang</option>
                <option value="City Heights">City Heights</option>
                <option value="Conel">Conel</option>
                <option value="Dadiangas East">Dadiangas East</option>
                <option value="Dadiangas North">Dadiangas North</option>
                <option value="Dadiangas South">Dadiangas South</option>
                <option value="Dadiangas West">Dadiangas West</option>
                <option value="Fatima">Fatima</option>
                <option value="Katangawan">Katangawan</option>
                <option value="Labangal">Labangal</option>
                <option value="Lagao">Lagao</option>
                <option value="Ligaya">Ligaya</option>
                <option value="Mabuhay">Mabuhay</option>
                <option value="Olympog">Olympog</option>
                <option value="San Isidro">San Isidro</option>
                <option value="San Jose">San Jose</option>
                <option value="Siguel">Siguel</option>
                <option value="Sinawal">Sinawal</option>
                <option value="Tambler">Tambler</option>
                <option value="Tinagacan">Tinagacan</option>
                <option value="Upper Labay">Upper Labay</option>
              </select>
              <span>Barangay</span>
              <div class="dropdown-list"></div>
            </div>
          </label>
          <label>
            <input id="date" name="date" placeholder="" type="text" class="input" onfocus="(this.type='date')"
              onblur="(this.type='text')" required />
            <span>Date</span>
          </label>
          <label>
            <input id="time" name="time" placeholder="" type="text" class="input" onfocus="(this.type='time')"
              onblur="(this.type='text')" required />
            <span>Time</span>
          </label>
          <label for="choices">
            <div class="custom-dropdown">
              <select id="choices" name="choices" class="input" required>
                <option value=""></option>
                <option value="Electrical Issue">Electrical Issue</option>
                <option value="Natural Causes">Natural Causes</option>
                <option value="Arson">Arson</option>
                <option value="Human Error">Human Error</option>
                <option value="Equipment Malfunction">Equipment Malfunction</option>
              </select>
              <span>Cause</span>

              <div class="dropdown-list"></div>
            </div>
          </label>
          <button id="submitBtn" class="submit">Submit</button>
        </form>
        <div class="form">
          <button class="cancel" id="cancel" onclick="event.preventDefault(); closePopup();">Cancel</button>
        </div>
      </div>
      <div class="previewMap">
        <div id="previewMap">
        </div>
    </div>
    </div>
  </section>
</body>

</html>