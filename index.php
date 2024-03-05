<?php
include "dashboard/db/connection.php";

session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
  // If not logged in, redirect to the login page
  header("Location: dashboard/home.php");
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
<html>

<head>
  <link rel="stylesheet" href="./css/login.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">
  <title>AFAS Log In</title>
</head>

<body>
  <div class="container" id="container">
    <div class="form-container log-in-container">
      <form action="#" method="POST">
        <h1>Login</h1>
        <div></div>
        <input id="username" name="username" type="text" placeholder="username" required />
        <input id="password" name="password" type="password" placeholder="password" required />
        <button class="button">Log In</button>
      </form>
    </div>
    <div class="overlay-container">
      <div class="overlay">
        <div class="overlay-panel overlay-right">
          <img src="./dashboard/misc/logo.png" alt="Company Logo" />
          <h1>Automated Fire Alarm System</h1>
          <p>
            A System Disgned to Aid Our Local Firefighters in their battle
            against fire.
          </p>
        </div>
      </div>
    </div>
  </div>
</body>

</html>