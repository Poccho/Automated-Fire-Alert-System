<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include "connection.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// delete_coordinates.php

// Include database connection code (connection.php)

// delete_coordinates.php

// Log to check if the file is accessed
error_log("delete_coordinates.php accessed");

// Retrieve latitude and longitude values from POST data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        error_log("Received latitude: " . $latitude);
        error_log("Received longitude: " . $longitude);

        // Assume $conn is your database connection
        $stmt = $conn->prepare("DELETE FROM alert WHERE latitude = ? AND longitude = ?");
        $stmt->bind_param("dd", $latitude, $longitude);

        if ($stmt->execute()) {
            error_log("Deletion successful");
            echo "Deletion successful";
        } else {
            error_log("Error: " . $stmt->error);
            echo "Deletion failed";
        }

        $stmt->close();
    } else {
        error_log("Latitude and longitude not provided");
        echo "Latitude and longitude not provided";
    }
} else {
    error_log("Invalid request method");
    echo "Invalid request method";
}

$conn->close();
?>