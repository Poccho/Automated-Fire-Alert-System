<?php

function sanitizeInput($input)
{
    return htmlspecialchars(trim($input));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming $conn is your database connection object

    // Get sanitized user input
    $latitude = isset($_POST['latitude']) ? sanitizeInput($_POST['latitude']) : "";
    $longitude = isset($_POST['longitude']) ? sanitizeInput($_POST['longitude']) : "";
    $barangayName = isset($_POST['barangay']) ? sanitizeInput($_POST['barangay']) : "";
    $date = isset($_POST['date']) ? sanitizeInput($_POST['date']) : "";
    $hour = isset($_POST['time']) ? sanitizeInput($_POST['time']) : "";
    $possibleCause = isset($_POST['choices']) ? sanitizeInput($_POST['choices']) : "";

    // Validate inputs
    if (empty($latitude) || empty($longitude) || empty($barangayName) || empty($date) || empty($hour) || empty($possibleCause)) {
        // Handle invalid input
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
        // Concatenate date and hour into a single variable
        $time = $date . ' ' . $hour;

        // Prepare a SELECT statement to retrieve the barangay_code based on the barangay_name
        $selectStmt = $conn->prepare("SELECT barangay_code FROM barangay WHERE barangay_name = ?");
        $selectStmt->bind_param("s", $barangayName);
        $selectStmt->execute();
        $selectResult = $selectStmt->get_result();

        // Check if the barangay_name exists in the barangay table
        if ($selectResult->num_rows > 0) {
            // Fetch the barangay_code
            $row = $selectResult->fetch_assoc();
            $barangayCode = $row['barangay_code'];

            // Prepare the INSERT statement with the retrieved barangay_code
            $insertStmt = $conn->prepare("INSERT INTO incident_data (latitude, longitude, barangay_code, time, cause, time_added) VALUES (?,?, ?, ?, ?, NOW())");
            $insertStmt->bind_param("sssss", $latitude, $longitude, $barangayCode, $time, $possibleCause);

            // Execute the INSERT statement
            if ($insertStmt->execute()) {
                // Handle successful insertion
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Report uploaded successfully.'
                            }).then(() => {
                                // Clear all input fields after successful submission
                                document.getElementById('latitude').value = '';
                                document.getElementById('longitude').value = '';
                                document.getElementById('barangay').value = '';
                                document.getElementById('date').value = '';
                                document.getElementById('time').value = '';
                                document.getElementById('choices').value = '';
                            });
                        });
                      </script>";
            } else {
                // Handle insertion failure
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error uploading report: {$insertStmt->error}'
                            });
                        });
                      </script>";
            }

            // Close prepared statements
            $insertStmt->close();
        } else {
            // Handle case where barangay_name does not exist
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Barangay not found!'
                        });
                    });
                  </script>";
        }

        // Close the SELECT statement
        $selectStmt->close();
    }
}

// Perform SQL query
$sql = "SELECT MONTHNAME(time) AS month, b.barangay_name, COUNT(*) AS count 
FROM incident_data AS i
JOIN barangay AS b ON i.barangay_code = b.barangay_code
WHERE YEAR(time) = YEAR(CURRENT_DATE)
GROUP BY MONTHNAME(time), b.barangay_name;
";

$result = $conn->query($sql);

// Prepare data for Chart.js
$fireOccurrencesData = array();
$barangays = array();
$colors = array('red', 'blue', 'green', 'orange', 'purple', 'cyan', 'magenta', 'yellow', 'lime', 'brown', 'teal', 'indigo', 'pink', 'amber', 'light-blue', 'deep-orange', 'light-green', 'deep-purple', 'cyan', 'amber', 'lime', 'pink', 'teal', 'indigo', 'deep-orange', 'light-green');

// Initialize months array
$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $month = $row['month'];
        $barangay = $row['barangay_name'];
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
