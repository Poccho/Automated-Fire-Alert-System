<?php
include("connection.php");
// Check if the form is submitted
if (isset($_GET['latitude']) && isset($_GET['longitude']) && isset($_GET['label']) && isset($_GET['barangay_code'])) {
    // Get user input
    $latitude = $_GET['latitude'];
    $longitude = $_GET['longitude'];
    $label = $_GET['label'];
    $barangay_code = $_GET['barangay_code'];

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Escape user input to prevent SQL injection
    $latitude = mysqli_real_escape_string($conn, $latitude);
    $longitude = mysqli_real_escape_string($conn, $longitude);
    $label = mysqli_real_escape_string($conn, $label);
    $barangay_code = mysqli_real_escape_string($conn, $barangay_code);

    // Insert latitude, longitude, label, and barangay code into the 'alert' table
    $sql = "INSERT INTO alert (latitude, longitude, label, barangay_code) VALUES ('$latitude', '$longitude', '$label', '$barangay_code')";

    if (mysqli_query($conn, $sql)) {
        echo '<p>Data inserted successfully!</p>';
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
} else {
    echo '<p>Please provide latitude, longitude, label, and barangay code parameters.</p>';
}
?>
