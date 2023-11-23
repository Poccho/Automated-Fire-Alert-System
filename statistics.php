<?php
include "connection.php";

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Function to sanitize and validate input
function sanitizeInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $location = sanitizeInput($_POST['location']);
    $barangay = sanitizeInput($_POST['barangay']);
    $time = sanitizeInput($_POST['time']);
    $possibleCause = sanitizeInput($_POST['choices']);

    // Insert the data into the database
    $stmt = $conn->prepare("INSERT INTO history (coordinates, barangay, time, cause) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $location, $barangay, $time, $possibleCause);


    if ($stmt->execute()) {
        // Style the success message as a sliding-out pop-up with JavaScript
        echo '<div id="success-popup" class="popup success-slide-out" style="background-color: #4CAF50; color: #fff; text-align: center; border-radius: 5px; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; width: 320px; height: 50px; line-height: 50px;">';
        echo "Report uploaded successfully!";
        echo '</div>';
        echo '<script>
                setTimeout(function() {
                    var successPopup = document.getElementById("success-popup");
                    successPopup.classList.remove("success-slide-out");
                    successPopup.style.opacity = "0";
                    setTimeout(function() {
                        successPopup.style.display = "none";
                    }, 500); // Adjust the delay based on your animation duration
                }, 3000);
              </script>';
    } else {
        // Style the error message as a sliding-out pop-up with JavaScript
        echo '<div id="error-popup" class="popup error-slide-out" style="background-color: #f44336; color: #fff; text-align: center; border-radius: 5px; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; width: 320px; height: 50px; line-height: 50px;">';
        echo "Error uploading report: " . $stmt->error;
        echo '</div>';
        echo '<script>
                setTimeout(function() {
                    var errorPopup = document.getElementById("error-popup");
                    errorPopup.classList.remove("error-slide-out");
                    errorPopup.style.opacity = "0";
                    setTimeout(function() {
                        errorPopup.style.display = "none";
                    }, 500); // Adjust the delay based on your animation duration
                }, 3000);
              </script>';
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>


<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <title>AFAS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./css/style.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./js/popup.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

  </head>
  <body>
    <nav>
      <input type="checkbox" id="check" />
      <label for="check" class="checkbtn">
        <i class="fas fa-bars"></i>
      </label>
      <label class="logo"
        ><i class="fa-solid fa-house-fire fa-fade fa-xs"></i> AFAS</label
      >
      <ul>
        <li><a href="home.php">Dashboard</a></li>
        <li><a class="active" href="statistics.php">Statistics</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href=logout.php> Sign Out </a></li>
      </ul>
    </nav>
    <section>
      <div class="buttons">
        <button class="print" onclick="downloadPDF()">Print</button>
        <button class="addreport" onclick="openPopup()">Add Record</button>
      </div>
      <div class="piechart">
        <canvas id="barangayPieChart"></canvas>

        <script>
          // Assuming you have data for barangays in Gensan
          var barangayData = {
            labels: [
              "Electrical Issue",
              "Natural Causes",
              "Arson",
              "Human Error",
              "Equipment Malfunction",
            ],
            datasets: [
              {
                data: [65, 63, 49, 62, 37], // You should replace these values with the actual data
                backgroundColor: [
                  "#FF6384",
                  "#36A2EB",
                  "#FFCE56",
                  "#4CAF50",
                  "#9966FF",
                ],
              },
            ],
          };

          // Get the canvas element
          var ctx = document
            .getElementById("barangayPieChart")
            .getContext("2d");

          // Create a pie chart
          var myPieChart = new Chart(ctx, {
            type: "pie",
            data: barangayData,
            options: {
              title: {
                display: true,
                text: "Barangays in Gensan",
              },
              plugins: {
                datalabels: {
                  color: "#fff", // Set label text color
                  font: {
                    size: 12, // Set label font size
                  },
                  formatter: function (value, context) {
                    return context.chart.data.labels[context.dataIndex];
                  },
                },
              },
              legend: {
                display: false,
                position: "top",
                align: "start",
              },
            },
          });
        </script>
      </div>
      <div class="areachart">
        <canvas id="barangayAreaChart" width="1100px" height="700"></canvas>

        <script>
          // Mock data representing fire outbreak cases in multiple barangays
          var barangayLabels = ["January", "February", "March", "April", "May"];
          var barangayData = [
            {
              name: "Electrical Issue",
              data: [10, 15, 8, 20, 12],
              color: "#FF6384",
            },
            {
              name: "Natural Causes",
              data: [8, 12, 15, 10, 18],
              color: "#36A2EB",
            },
            {
              name: "Arson",
              data: [5, 10, 12, 8, 14],
              color: "#FFCE56",
            },
            {
              name: "Human Error",
              data: [6, 9, 13, 14, 20],
              color: "#4CAF50",
            },
            {
              name: "Equipment Malfunction",
              data: [7, 6, 2, 18, 4],
              color: "#9966FF",
            },
            // Add more barangays as needed
          ];

          var ctx = document
            .getElementById("barangayAreaChart")
            .getContext("2d");

          var multiBarangayAreaChart = new Chart(ctx, {
            type: "line",
            data: {
              labels: barangayLabels,
              datasets: barangayData.map((barangay) => ({
                label: barangay.name,
                data: barangay.data,
                backgroundColor: barangay.color, // Fill color under the line
                borderColor: barangay.color, // Line color
                borderWidth: 7,
                pointRadius: 5, // Size of the data points
                pointBackgroundColor: barangay.color, // Color of the data points
                fill: false, // Fill the area under the line
              })),
            },
            options: {
              scales: {
                x: {
                  type: "category",
                  labels: barangayLabels,
                },
                y: {
                  beginAtZero: true,
                },
              },
            },
          });
        </script>
      </div>
      
      <div id="overlay">
        <div id="popup">
          <form class="form" method="POST">
            <p class="title">Add Report</p>
            <p class="message">Fill Up the necessary information needed</p>           

            <label>
              <input id="location" name="location" placeholder="" type="text" class="input" />
              <span>Location</span>
            </label>
            
            <label>
              <input id="barangay" name="barangay" placeholder="" type="text" class="input"/>
              <span>Barangay</span>
            </label>

            <label>
              <input id="time" name="time" placeholder="" type="time" class="input" />
              <span>Time</span>
            </label>
            <label for="choices">Possible Cause:</label>
              <div class="custom-dropdown">
                <select id="choices" name="choices" class="input">
                    <option value="Electrical Issue">Electrical Issue</option>
                    <option value="Natural Causes">Natural Causes</option>
                    <option value="Arson">Arson</option>
                    <option value="Human Error">Human Error</option>
                    <option value="Equipment Malfunction">Equipment Malfunction</option>
                </select>
                <div class="dropdown-list"></div>
              </div>
            <button id="sumbit" class="submit">Submit</button>
          </form>
          <form class="form">
          <button class="cancel" onclick="closePopup()">Cancel</button>
          </form>
            
        </div>
      </div>
    </section>
  </body>
</html>
