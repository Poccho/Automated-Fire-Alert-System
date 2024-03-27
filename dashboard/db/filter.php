<?php

// Handle the search query
if(isset($_POST['search'])) {
    $search = $_POST['search'];

    include "connection.php";

    $sql = "SELECT 
    i.*, 
    b.barangay_name
FROM 
    incident_data AS i
JOIN 
    barangay AS b ON i.barangay_code = b.barangay_code
WHERE 
    b.barangay_name LIKE '%$search%' OR
    i.cause LIKE '%$search%' OR
    i.time LIKE '%$search%' OR
    CONCAT(i.latitude, ', ', i.longitude) LIKE '%$search%'";


    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                $coordinates = $row["latitude"] . ", " . $row["longitude"];
                echo "<tr onclick='openPopup(\"" . $row["barangay_name"] . "\", \"" . $row["cause"] . "\", \"" . $row["time"] . "\", \"" . addslashes($coordinates) . "\")'>";
                echo "<td style='width: 17%;'>" . $row["barangay_name"] . "</td>";
                echo "<td style='width: 12%;'>" . $row["cause"] . "</td>";
                echo "<td style='width: 20%;'>" . $row["time"] . "</td>";
                echo "<td style='width: 30%;'>" . $coordinates . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4' style='text-align: center;'>NO RECORDS FOUND</td></tr>";
        }
    } else {
        echo "Error: " . $conn->error;
        echo "<br>Query: " . $sql;
    }

    $conn->close();
}
?>
