<?php

function sanitizeInput($input)
{
    return htmlspecialchars(trim($input));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get sanitized user input
    $location = isset($_POST['location']) ? "(" . sanitizeInput($_POST['location']) . ")" : "";
    $barangay = isset($_POST['barangay']) ? sanitizeInput($_POST['barangay']) : "";
    $date = isset($_POST['date']) ? sanitizeInput($_POST['date']) : "";
    $hour = isset($_POST['time']) ? sanitizeInput($_POST['time']) : "";
    $possibleCause = isset($_POST['choices']) ? sanitizeInput($_POST['choices']) : "";

    // Validate inputs
    if (empty($location) || empty($barangay) || empty($date) || empty($hour) || empty($possibleCause)) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please fill in all fields.'
                    });
                });
              </script>";
    } else {
        $time = $date . ' ' . $hour;

        // Insert the data into the database using prepared statements
        $stmt = $conn->prepare("INSERT INTO incident_data (coordinates, barangay, time, cause, time_added) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $location, $barangay, $time, $possibleCause);

        if ($stmt->execute()) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Report uploaded successfully.'
                        }).then(() => {
                            // Clear all input fields after successful submission
                            document.getElementById('location').value = '';
                            document.getElementById('barangay').value = '';
                            document.getElementById('date').value = '';
                            document.getElementById('time').value = '';
                            document.getElementById('choices').value = '';
                        });
                    });
                  </script>";
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error uploading report: {$stmt->error}'
                        });
                    });
                  </script>";
        }

        $stmt->close();
    }
}






// Perform SQL query
$sql = "SELECT cause, MONTHNAME(time) AS month, barangay, COUNT(*) AS count 
        FROM incident_data 
        WHERE YEAR(time) = YEAR(CURRENT_DATE) 
        GROUP BY cause, MONTHNAME(time), barangay";
$result = $conn->query($sql);

// Prepare data for Chart.js
$fireOccurrencesData = array();
$barangays = array();
$colors = array('red', 'blue', 'green', 'orange', 'purple', 'cyan', 'magenta', 'yellow', 'lime', 'brown', 'teal', 'indigo', 'pink', 'amber', 'light-blue', 'deep-orange', 'light-green', 'deep-purple', 'cyan', 'amber', 'lime', 'pink', 'teal', 'indigo', 'deep-orange', 'light-green');

// Initialize months array
$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cause = $row['cause'];
        $month = $row['month'];
        $barangay = $row['barangay'];
        $count = $row['count'];

        if (!isset($fireOccurrencesData[$barangay])) {
            // Initialize data for the barangay with placeholder values for each month
            $fireOccurrencesData[$barangay] = array_fill(0, count($months), 'No recorded value');
            $barangays[] = $barangay;
        }

        // Set the count for the corresponding month
        if (in_array($month, $months)) {
            $monthIndex = array_search($month, $months);
            $fireOccurrencesData[$barangay][$monthIndex] = $count;
        }
    }
}
// Close database connection
$conn->close();

// Convert data to JSON format
$data = array(
    'labels' => $months,
    'datasets' => array()
);

foreach ($barangays as $index => $barangay) {
    $dataset = array(
        'label' => $barangay,
        'data' => array_slice($fireOccurrencesData[$barangay], 0, count($months)), // Slice the data to include only up to March
        'borderColor' => $colors[$index % count($colors)], // Assign colors dynamically
        'fill' => false
    );
    $data['datasets'][] = $dataset;
}

$dataJSON = json_encode($data);
?>

<script>
    var fireOccurrencesData = <?php echo $dataJSON; ?>;
</script>