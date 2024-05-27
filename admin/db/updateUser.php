<?php
session_start(); // Start the session

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_GET['userId'])) {
        $userId = $_GET['userId'];

        // Fetch user details from the database based on the provided user ID
        $sql = "SELECT * FROM users WHERE user_id = $userId";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            // Fetch user details
            $row = $result->fetch_assoc();

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
                $error_msg = "Password fields do not match.";
                
                // Display Swal notification for error
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: '$error_msg',
                                showConfirmButton: true
                            });
                        });
                      </script>";
            } elseif (!strpos($email, '@gmail.com')) { // Check if email ends with "@gmail.com"
                // Email does not end with "@gmail.com"
                $error_msg = "Email must end with '@gmail.com'.";
                
                // Display Swal notification for error
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: '$error_msg',
                                showConfirmButton: true
                            });
                        });
                      </script>";
            } elseif (!empty($password)) { // Check if password is not empty
                // Prepare and bind statement for updating email and password
                $stmt_update = $conn->prepare("UPDATE users SET email = ?, password = ?, username = ?, station_location = ?, barangay_code = ? WHERE user_id = ?");
                $stmt_update->bind_param("sssssi", $email, $hashedPassword, $username, $stationLocation, $barangayCode, $userId);
                
                // Execute update
                if ($stmt_update->execute()) {
                    // Success message
                    $_SESSION['update_success'] = true; // Set session variable to indicate success
                } else {
                    // Error message
                    $error_msg = "Error: " . $stmt_update->error;
                }

                // Close statement
                $stmt_update->close();
            } else { // If password is empty, update only email
                // Prepare and bind statement for updating email only
                $stmt_update = $conn->prepare("UPDATE users SET email = ?, username = ?, station_location = ?, barangay_code = ? WHERE user_id = ?");
                $stmt_update->bind_param("ssssi", $email, $username, $stationLocation, $barangayCode, $userId);
                
                // Execute update
                if ($stmt_update->execute()) {
                    // Success message
                    $_SESSION['update_success'] = true; // Set session variable to indicate success
                } else {
                    // Error message
                    $error_msg = "Error: " . $stmt_update->error;
                }

                // Close statement
                $stmt_update->close();
            }

            // Redirect to userList.php if no error occurred
            if (isset($_SESSION['update_success']) && $_SESSION['update_success']) {
                header("Location: userList.php");
                exit; // Ensure that subsequent code is not executed after redirection
            }
        }
    }
}
?>
