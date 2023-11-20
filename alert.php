<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alert Form</title>
</head>
<body>
    <h2>Enter Coordinates</h2>
    <form method="post" action="">
        <label for="coordinates">Coordinates:</label>
        <input type="text" name="coordinates" required>

        <button type="submit" name="submit">Submit</button>
    </form>

    <?php
    // Check if the form is submitted
    if(isset($_POST['submit'])) {
        // Get user input
        $coordinates = $_POST['coordinates'];

        // Database connection details
        $host = 'localhost';
        $db = 'firedatabase';
        $user = 'root';
        $pass = '';

        // Create a PDO connection
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        try {
            $pdo = new PDO($dsn, $user, $pass);
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }

        // Insert coordinates into the 'alert' table
        $query = $pdo->prepare("INSERT INTO alert (coordinates) VALUES (?)");
        $query->execute([$coordinates]);

        echo '<p>Coordinates inserted successfully!</p>';
    }
    ?>
</body>
</html>
