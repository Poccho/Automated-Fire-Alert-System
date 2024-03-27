<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the script for connecting to the database
    require_once "connection.php";

    // Function to sanitize input
    function sanitizeInput($input)
    {
        return htmlspecialchars(trim($input));
    }

    // Retrieve and sanitize form data
    $email = isset($_POST["email"]) ? sanitizeInput($_POST["email"]) : "";
    $password = isset($_POST["password"]) ? sanitizeInput($_POST["password"]) : "";
    $username = isset($_POST["username"]) ? sanitizeInput($_POST["username"]) : "";
    $stationLocation = isset($_POST["station_location"]) ? sanitizeInput($_POST["station_location"]) : "";
    $barangayCode = isset($_POST["barangay_code"]) ? sanitizeInput($_POST["barangay_code"]) : "";
    $password2 = isset($_POST["password2"]) ? sanitizeInput($_POST["password2"]) : "";

    // Hash the password (using bcrypt for secure hashing)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Validation (you can add more validation as needed)
    if ($password !== $password2) {
        // Passwords do not match
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Password fields do not match.'
            });
        });
        </script>";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email already exists
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Email is already in use.'
                });
            });
            </script>";
        } else {
            // Prepare and bind statement for insertion
            $stmt_insert = $conn->prepare("INSERT INTO users (email, password, username, station_location, barangay_code) VALUES (?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("sssss", $email, $hashedPassword, $username, $stationLocation, $barangayCode);

            // Execute insertion
            if ($stmt_insert->execute() === TRUE) {
                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'User added successfully.'
                    });
                });
                </script>"; // Send success response
            } else {
                // Error in insertion
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error: " . $stmt_insert->error . "'
                            });
                        });
                      </script>";
            }
            
            // Close statement
            $stmt_insert->close();
        }
        
        // Close statement
        $stmt->close();
    }
}
?>
