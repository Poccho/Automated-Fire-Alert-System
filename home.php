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
        <li><a href="contact.php">Contact</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href=logout.php> Sign Out </a></li>
      </ul>
    </nav>
    <section>
      <div class="content">
        <div id="map"></div>
        <div class="alarms">
          <div class="title-alarm">
          <i class="fa-regular fa-bell fa-xs"></i>   Alarms   <i class="fa-regular fa-bell fa-xs"></i>
          </div>

                 <table id="dynamic-table">
                  <tbody>
                  </tbody>
                 </table>
        </div>
        <div class="btn-div"><button id="switch-layers" onclick="switchTileLayer()">Switch Map Layers</button></div>
      </div>
    </section>
    <script>
        // JavaScript/jQuery code for AJAX
        $(document).ready(function () {
            // Function to refresh the table content
            function refreshTable() {
                // AJAX request
                $.ajax({
                    url: 'refresh_table.php', // The server-side PHP script to handle the table refresh
                    type: 'GET',
                    success: function (data) {
                        // Update the content of the table body
                        $('#dynamic-table tbody').html(data);
                                        if (data === 'new_row') {
                    // Play the notification sound
                    document.getElementById('notificationSound').play();
                    }}
                });
            }

            // Function to refresh the table every second
            setInterval(function () {
                refreshTable();
            }, 1000);

            // Initial table load
            refreshTable();
        });
    </script>

  </body>
</html>
