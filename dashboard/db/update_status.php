<?php
// Include your database connection file
include "connection.php";

// Check if latitude, longitude, and status are provided via POST
if (isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['status'])) {
    // Sanitize input to prevent SQL injection
    $latitude = mysqli_real_escape_string($conn, $_POST['latitude']);
    $longitude = mysqli_real_escape_string($conn, $_POST['longitude']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Update the status in the database without changing alert_time
    $sql = "UPDATE alert SET alert_status = '$status', alert_time = alert_time WHERE latitude = '$latitude' AND longitude = '$longitude'";
    if ($conn->query($sql) === TRUE) {
        // Status updated successfully
        echo "Status updated successfully";
    } else {
        // Error updating status
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    // Error: Missing parameters
    echo "Error: Missing parameters";
}

// Close database connection
$conn->close();
?>
