<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: index.php");
    exit();
}
?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <title>AFAS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" />
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css"
    />
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css"
    />
    <script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <script src="./js/script.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <audio id="notificationSound" src="misc\alarmsound.mp3" type="audio/mpeg">
  Your browser does not support the audio element.
</audio>

  </head>
  <body>
    <nav>
      <input type="checkbox" id="check" />
      <label for="check" class="checkbtn">
        <i class="fas fa-bars"></i>
      </label>
      <label class="logo"
        ><i class="fa-solid fa-house-fire fa-xs"></i> AFAS</label
      >
      <ul>
        <li><a class="active" href="home.php">Dashboard</a></li>
        <li><a href="statistics.php">Statistics</a></li>
        <li><a href="history.php">History</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href=logout.php> Sign Out </a></li>
      </ul>
    </nav>
    <section>
      <div class="content">
        <div id="map"></div>
        <div id="coordinates-card" style="display: none;">
          <div id="coordinates-content">
            <h3>Coordinates</h3>
            <p id="coordinates-info"></p>
          </div>
        </div>

        <div class="alarm-content">
        <div class="title-alarm">
          <i class="fa-regular fa-bell fa-xs"></i>   Alarms   <i class="fa-regular fa-bell fa-xs"></i>
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
          // Function to refresh the table content
          let loopId; // To hold the interval ID for stopping the loop

          function refreshTable() {
              // AJAX request
              $.ajax({
                  url: 'refresh_table.php', // The server-side PHP script to handle the table refresh
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
                        if (currentRowCount <= initialRowCount) {
                            // Stop playing the sound if the row count decreases
                            clearInterval(loopId);
                        } else {
                            document.getElementById('notificationSound').play();
                        }
                    }, 1000); // Adjust the interval as needed
                }
                
                // Update the initial row count for the next comparison
                initialRowCount = updatedRowCount;
            }
        });
    }

          // Function to refresh the table every 5 seconds
          setInterval(function () {
              refreshTable();
          }, 1000);

          // Initial table load
          refreshTable();
      });

    </script>

  </body>
</html>
