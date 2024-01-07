<?php
// Check if the form is submitted
if(isset($_GET['latitude']) && isset($_GET['longitude'])) {
    // Get user input
    $latitude = $_GET['latitude'];
    $longitude = $_GET['longitude'];

    // Database connection details
    $host = 'fdb1032.awardspace.net';
    $db = '4402151_alert';
    $user = '4402151_alert';
    $pass = 'Pocho123!';

    // Create a PDO connection
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $user, $pass);
    } catch (PDOException $e) {
        die('Connection failed: ' . $e->getMessage());
    }

    // Insert latitude and longitude into the 'alert' table
    $query = $pdo->prepare("INSERT INTO alert (latitude, longitude) VALUES (?, ?)");
    $query->execute([$latitude, $longitude]);

    echo '<p>Latitude and Longitude inserted successfully!</p>';
} else {
    echo '<p>Please provide both latitude and longitude parameters.</p>';
}
?>
