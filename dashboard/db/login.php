<?php

include "connection.php";

session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
  // If logged in, redirect to the appropriate dashboard based on user type
  if ($_SESSION['user_type'] == 'admin') {
    header("Location: admin/index.php");
  } else {
    header("Location: ./dashboard/home.php");
  }
  exit();
}

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize and validate input
function sanitizeInput($input)
{
  $input = trim($input);
  $input = stripslashes($input);
  $input = htmlspecialchars($input);
  return $input;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get user input
  $username = sanitizeInput($_POST['username']);
  $password = sanitizeInput($_POST['password']);

  // Check if the user exists
  $stmt = $conn->prepare("SELECT user_id, password, user_type, barangay_code FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($userId, $storedPassword, $userType, $barangay_code); // Added $barangay_code to bind_result
    $stmt->fetch();

    // Verify the password
    if (password_verify($password, $storedPassword)) {
      // Start a session
      session_start();

      // Store user information in the session
      $_SESSION['user_id'] = $userId;
      $_SESSION['username'] = $username;
      $_SESSION['user_type'] = $userType;
      $_SESSION['barangay_code'] = $barangay_code;

      // Redirect to the appropriate dashboard based on user type
      if ($userType == 'admin') {
        header("Location: admin/index.php");
      } else {
        header("Location: ./dashboard/home.php");
      }
      exit(); // Make sure to stop script execution after redirection
    } else {
      echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Incorrect username or password!",
              });
            });
          </script>';
    }
  } else {
    echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "User not found!",
              });
            });
          </script>';
  }

  $stmt->close();
}

// Close the database connection
$conn->close();
?>
