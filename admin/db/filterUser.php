<?php
// Include your database connection file
if (isset ($_POST['search'])) {
    $search = $_POST['search'];
    include "connection.php";

    $query = "SELECT 
user_id,
user_type,
username,
email,
password,
station_location,
barangay_code
FROM 
users
WHERE 
user_type LIKE '%$search%' OR
username LIKE '%$search%' OR
email LIKE '%$search%' OR
station_location LIKE '%$search%' OR
barangay_code LIKE '%$search%'
";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["user_id"] . "</td>";
            echo "<td>" . $row["user_type"] . "</td>";
            echo "<td>" . $row["username"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["station_location"] . "</td>";
            echo "<td>" . $row["barangay_code"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='center'>No users found</td></tr>";
    }
}
?>