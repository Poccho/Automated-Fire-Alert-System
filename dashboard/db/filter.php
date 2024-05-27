<?php

// Handle the search query
if (isset($_POST['search'])) {
    $search = $_POST['search'];

    include "connection.php";

    $sql = "SELECT 
    i.*, 
    b.barangay_name
FROM 
    report AS i
JOIN 
    barangay AS b ON i.barangay_no = b.barangay_code
WHERE 
    b.barangay_name LIKE '%$search%' OR
    i.type_of_incident LIKE '%$search%' OR
    i.date LIKE '%$search%'";


    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td style='width: 17%;'>" . $row["barangay_name"] . "</td>";
                echo "<td style='width: 17%;'>" . $row["type_of_incident"] . "</td>";
                echo "<td style='width: 12%;'>" . $row["date"] . "</td>";
                echo "<td style='width: 20%;'>" . $row["alarm_time"] . "</td>";

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