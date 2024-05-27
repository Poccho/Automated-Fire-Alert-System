<?php

function sanitizeInput($input)
{
    return htmlspecialchars(trim($input));
}

// Perform SQL query
$sql = "SELECT MONTHNAME(alarm_time) AS month, b.barangay_name, COUNT(*) AS count 
FROM report AS i
JOIN barangay AS b ON i.barangay_no = b.barangay_code
WHERE YEAR(alarm_time) = YEAR(CURRENT_DATE)
GROUP BY MONTHNAME(alarm_time), b.barangay_name;
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
