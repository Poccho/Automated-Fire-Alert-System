<?php
session_start(); // Start the session if not already started

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('success' => false, 'error' => 'User not logged in'));
    exit(); // Terminate script execution
}

include("connection.php"); // Include the database connection file

// Assuming you have a database connection already established
// and the user ID is available
$userId = $_SESSION['user_id']; // Assuming you store user ID in session

// Query to retrieve user's station location from the database
// Replace 'users' with your actual table name and 'station_location' with the appropriate column name
$query = "SELECT station_location FROM users WHERE user_id = $userId";

// Execute the query and fetch the station location
$result = mysqli_query($conn, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $stationLocation = $row['station_location'];

    // Check if station_location is empty
    if ($stationLocation) {
        // Split station_location into latitude and longitude
        list($stationLatitude, $stationLongitude) = explode(',', $stationLocation);
        // Return the station location as JSON
        echo json_encode(array('success' => true, 'latitude' => $stationLatitude, 'longitude' => $stationLongitude));
    } else {
        // Station location not found for the user
        echo json_encode(array('success' => false, 'error' => 'Station location not found for the user'));
    }
} else {
    // Error occurred while executing query
    echo json_encode(array('success' => false, 'error' => mysqli_error($conn)));
}

// Close the database connection
mysqli_close($conn);
?>
