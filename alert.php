
    <?php
    // Check if the form is submitted
    if(isset($_GET['coordinates'])) {
        // Get user input
        $coordinates = $_GET['coordinates'];

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
