<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // If not logged in, redirect to the login page
  header("Location: ../index.php");
  exit();
}
?>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8" />
  <title>AFAS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" />
  <script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
  <script src="./js/script.js" defer></script>
  <script src="./js/map.js" defer></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
  <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css'
    rel='stylesheet' />
  <audio id="notificationSound" src="misc\alarmsound.mp3" type="audio/mpeg">
    Your browser does not support the audio element.
  </audio>
  <link rel="stylesheet" href="./css/reportForm.css">
  <link rel="stylesheet" href="./css/eta.css">
</head>

<body>
  <?php
  include "navBar.php";
  ?>
  <section>
    <div class="content">
      <div id="map"></div>
        <div class="eta" id="eta">
          <p>ETA: </p>
          <br>
          <p>ETA: </p>
          <br>
          <p>ETA: </p>
          <br>
          <p>ETA: </p>

        </div>
      <div class="alarm-content">
        <div class="title-alarm">
          <i class="fa-regular fa-bell fa-xs"></i> Alarms <i class="fa-regular fa-bell fa-xs"></i>
        </div>
        <div class="alarms">
          <table id="dynamic-table">
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="btn-div"><button id="switch-layers" onclick="switchTileLayer()">Switch Map Layers</button></div>
      </div>
    </div>
  </section>
  <script>
    $(document).ready(function () {
      let loopId; // To hold the interval ID for stopping the loop

      // Function to refresh the table content
      function refreshTable() {
        // AJAX request
        $.ajax({
          url: './db/refresh_table.php', // The server-side PHP script to handle the table refresh
          type: 'GET',
          success: function (data) {
            let initialRowCount = $('#dynamic-table tbody tr').length; // Get the initial row count
            // Update the content of the table body
            $('#dynamic-table tbody').html(data);

            // Get the updated row count after the refresh
            let updatedRowCount = $('#dynamic-table tbody tr').length;

            // Log initial and updated row counts to the console
            console.log('Initial Row Count:', initialRowCount);
            console.log('Updated Row Count:', updatedRowCount);

            // Check if the number of rows increased after the refresh
            if (updatedRowCount > initialRowCount) {
              // Play the notification sound if new rows were added
              document.getElementById('notificationSound').play();

              // Start playing the sound in a loop
              loopId = setInterval(function () {
                let currentRowCount = $('#dynamic-table tbody tr').length;
                if (currentRowCount < initialRowCount || currentRowCount === 2 || currentRowCount === 0) {
                  // Stop playing the sound if the row count decreases
                  clearInterval(loopId);
                } else {
                  document.getElementById('notificationSound').play();
                }
              }, 1000); // Adjust the interval as needed
            }

            // Highlight rows for coordinates already added to the map
            highlightPinnedCoordinates();
          },
          error: function (xhr, status, error) {
            // Show error message using SweetAlert
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Error refreshing table: ' + error
            });
          }
        });
      }

      // Function to refresh the table every 5 seconds
      setInterval(function () {
        refreshTable();
      }, 1000);

      // Initial table load
      refreshTable();

      // Function to highlight rows for coordinates already added to the map
      function highlightPinnedCoordinates() {
        $('#dynamic-table tbody tr').each(function () {
          let coords = $(this).find('td:first').text().trim().split(', '); // Splitting coordinates
          let latitude = parseFloat(coords[0]);
          let longitude = parseFloat(coords[1]);

          if (isCoordinatePinned(latitude, longitude)) {
            $(this).addClass('green-highlight');
          }
        });
      }

      // Function to check if a coordinate is already pinned on the map
      function isCoordinatePinned(latitude, longitude) {
        // Your logic to check if the coordinates are already pinned on the map
        // For example, checking if the route exists for the given coordinates
        for (let i = 0; i < routes.length; i++) {
          if (routes[i].latitude === latitude && routes[i].longitude === longitude) {
            return true;
          }
        }
        return false;
      }

      // Add event listener to the pin button to stop the alarm
      $('#pin-button').click(function () {
        clearInterval(loopId); // Stop the alarm
      });
    });

  </script>


</body>

</html>