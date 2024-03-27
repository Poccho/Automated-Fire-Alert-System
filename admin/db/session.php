<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login page if not logged in
    exit();
} elseif (isset($_SESSION['user_type'])) {
    // Check user type and redirect accordingly
    $user_type = $_SESSION['user_type']; // Assuming 'user_type' is stored in session upon login
    
    if ($user_type === 'user') {
        header("Location: ../dashboard/home.php"); // Redirect admin to admin dashboard
        exit();
    }
    // For regular users, do nothing, let them stay on the current page
}
?>
