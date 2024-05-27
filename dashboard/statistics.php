<?php
include "db/connection.php";

include("db/session.php");


include "db/stats.php";
// Function to sanitize and validate input
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <title>AFAS</title>
  <meta charset="utf-8" />
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" />
</head>

<body>
  <?php include "navBar.php"; ?>
  <div class="buttons">
  <button class="addreport" onclick="redirectToReport()">Add Record</button>
  </div>
  <script>
    function redirectToReport() {
        window.location.href = "report.php";
    }
  </script>
  <section class="charts">

    <div class="svg-container">
      <!-- Your SVG -->
      <div class="hover-info" style="background-color:white; border-radius:10px; box-shadow: 0 6px 12px rgba(0, 0, 0, 1);"></div>

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

      $sql = "SELECT 
      b.barangay_name AS barangay,
      COUNT(*) AS occurrence_count 
  FROM 
      report AS i
  JOIN 
      barangay AS b ON i.barangay_no = b.barangay_code
  GROUP BY 
      b.barangay_code;
  ";
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
                    <h2 class="infoTitle" style="color:black;">${pathInfo[pathId].title}</h2>
                    <p class="infoDesc" style="color:black;">${pathInfo[pathId].description}</p>
                `;
          } else {
            hoverInfo.innerHTML = `
                    <h2 class="infoTitle" style="color:black;">${pathId}</h2>
                    <p class="infoDesc" style="color:black;">No Saved Record</p>`;
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

  </section>
</body>

</html>