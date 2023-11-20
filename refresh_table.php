<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css"
    />
  </head>
</body>
 <table id="dynamic-table">
        <thead>
            <tr>
                <th>Coordinates</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
<?php
// PHP code to fetch coordinates from the database and display them in the table
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "firedatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the last known maximum alert_id (you can store it in a file or database)
$lastKnownMaxAlertId = 0; // Replace with your actual logic to get the last known maximum alert_id

$sql = "SELECT alert_id, coordinates, DATE_FORMAT(alert_time, '%H:%i') AS alert_time FROM alert";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["coordinates"] . "</td>
                <td>
                    <button id=\"pin\" onclick=\"changeLocation3()\"><i class=\"fa-solid fa-map-pin fa-bounce fa-lg\"></i></button>
                    <button id=\"delete\" onclick=\"remove3()\"><i class=\"fa-regular fa-trash-can fa-lg\"></i></button>
                </td>
              </tr>";

        // Check if there are new rows based on alert_id
        $currentAlertId = $row["alert_id"];
        if ($currentAlertId > $lastKnownMaxAlertId) {
            echo "<script>console.log('new_row');</script>";
            // Update the last known maximum alert_id
            // You might want to store this value in a file, database, or session
            // to persist it across multiple requests.
            $lastKnownMaxAlertId = $currentAlertId;
        }
    }
} else {
    echo "<tr><td colspan='3'>No coordinates found</td></tr>";
}

$conn->close();
?>


        </tbody>
        
    </table>
</body>
</html>