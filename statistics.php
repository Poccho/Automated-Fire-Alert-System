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

$sql = "SELECT cause, COUNT(*) as count FROM history GROUP BY cause";
$result = $conn->query($sql);

// Initialize arrays to store labels and data for the pie chart
$labels = [];
$data = [];

// Process the result set
while ($row = $result->fetch_assoc()) {
    $labels[] = $row['cause'];
    $data[] = $row['count'];
}

$sql = "SELECT cause, MONTH(time) AS month, COUNT(*) AS count FROM history WHERE YEAR(time) = YEAR(CURRENT_DATE()) GROUP BY cause, MONTH(time)";
$result = $conn->query($sql);

// Initialize arrays to store labels, data, and colors for the area chart
$barangayLabels = [];
$barangayData = [];

// Define colors to match with the pie chart
$colors = [
    "#FF6384",
    "#36A2EB",
    "#FFCE56",
    "#4CAF50",
    "#9966FF",
];

$colorIndex = 0;

// Process the result set
while ($row = $result->fetch_assoc()) {
    $barangayLabels[$row['month']] = date('F', mktime(0, 0, 0, $row['month'], 1));

    $color = $colors[$colorIndex];
    $barangayData[$row['cause']][] = [
        'count' => $row['count'],
        'color' => $color,
    ];

    // Move to the next color or reset to the first one
    $colorIndex = ($colorIndex + 1) % count($colors);
}

// Output fetched data for debugging
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
    <script src="./js/download.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css"
    />

    <!-- Add these script tags in the head of your HTML file -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>



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
        <button id="exportButton" class="print" onclick="exportToPDF()">Download</button>
        <button class="addreport" onclick="openPopup()">Add Record</button>
      </div>
      <div class="piechart">
        <canvas id="barangayPieChart"></canvas>

        <script>
        var barangayData = {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [
                {
                    data: <?php echo json_encode($data); ?>,
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

        var ctx = document
            .getElementById("barangayPieChart")
            .getContext("2d");

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
                        color: "#fff",
                        font: {
                            size: 12,
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
                tooltips: {
                    enabled: true,
                    callbacks: {
                        label: function (tooltipItem, data) {
                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var total = dataset.data.reduce(function (previousValue, currentValue) {
                                return previousValue + currentValue;
                            });
                            var currentValue = dataset.data[tooltipItem.index];
                            var percentage = ((currentValue / total) * 100).toFixed(2);
                            return dataset.label + ": " + percentage + "%";
                        },
                    },
                },
            },
        });
        </script>
      </div>
      <div class="areachart">
        <canvas id="barangayAreaChart" width="1100px" height="700"></canvas>

        <script>
        var barangayLabels = <?php echo json_encode(array_values($barangayLabels)); ?>;
        var barangayData = [];

        <?php
        foreach ($barangayData as $cause => $data) {
            foreach ($data as $entry) {
                echo "barangayData.push({
                        name: '$cause',
                        data: [" . $entry['count'] . "],
                        backgroundColor: '" . $entry['color'] . "',
                    });";
            }
        }
        ?>
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
