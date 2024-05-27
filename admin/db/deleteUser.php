<?php
// Include database connection
include "connection.php";

// Check if user ID is provided via POST request
if(isset($_POST['userId'])) {
    // Sanitize the input to prevent SQL injection
    $userId = mysqli_real_escape_string($conn, $_POST['userId']);
    
    // SQL query to delete the user
    $sql = "DELETE FROM users WHERE user_id = '$userId'";
    
    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // If deletion is successful, return a success message
        echo "User deleted successfully";
    } else {
        // If an error occurred during deletion, return an error message
        echo "Error deleting user: " . $conn->error;
    }
    
    // Close database connection
    $conn->close();
} else {
    // If user ID is not provided, return an error message
    echo "Error: User ID not provided";
}
?>
