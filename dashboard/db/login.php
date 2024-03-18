<?php

include "connection.php";

session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
  // If not logged in, redirect to the login page
  header("Location: ../home.php");
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

  // Hash the password (use password_hash() when storing passwords)
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Check if the user exists
  $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($userId, $storedPassword);
    $stmt->fetch();

    // Verify the password
    if (password_verify($password, $storedPassword)) {
      // Start a session
      session_start();

      // Store user information in the session
      $_SESSION['user_id'] = $userId;
      $_SESSION['username'] = $username;

      // Login successful! Redirect to home.php or any other page
      header("Location: dashboard/home.php");
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