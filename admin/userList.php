<?php
session_start(); // Start the session
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

// Check if the update was successful
if (isset($_SESSION['update_success']) && $_SESSION['update_success']) {
    // Display success Swal notification
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'User updated successfully.',
                    showConfirmButton: true
                });
            });
          </script>";

    // Unset the session variable to prevent displaying the notification again on page refresh
    unset($_SESSION['update_success']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/userList.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" />
</head>

<body>
    <?php include "navBar.php"; ?>

    <form id="filterForm" onsubmit="submitForm(event)">
        <input id="searchInput" class="search-input" type="text" oninput="filterTable()" placeholder="Search">
    </form>

    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>User Type</th>
                <th>Username</th>
                <th>Email</th>
                <th>Station Location</th>
                <th>Barangay Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            <?php
            include "db/connection.php";

            $sql = "SELECT * from users";
            $result = $conn->query($sql);

            if ($result) {
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["user_id"] . "</td>";
                        echo "<td>" . $row["user_type"] . "</td>";
                        echo "<td>" . $row["username"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["station_location"] . "</td>";
                        echo "<td>" . $row["barangay_code"] . "</td>";
                        echo "<td>";
                        // Add onclick event to redirect to edit page with the selected row's ID
                        echo "<i class='fa-regular fa-pen-to-square fa-lg' onclick='editUser(" . $row["user_id"] . ")' Style='margin-right:20px;'></i>";
                        echo "<i class='fa-regular fa-trash-can fa-lg' onclick='confirmDelete(" . $row["user_id"] . ")'></i>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align: center;'>NO RECORDS FOUND</td></tr>";
                }
            } else {
                echo "Error: " . $conn->error;
                echo "<br>Query: " . $sql;
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</body>
<script>
    function filterTable() {
        var searchInput = document.getElementById("searchInput").value;
        $.ajax({
            type: "POST",
            url: "db/filterUser.php",
            data: { search: searchInput },
            success: function (response) {
                $('table tbody').html(response);
            }
        });
    }

    function confirmDelete(userId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to delete this user. This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // User confirmed deletion, trigger AJAX request to delete user
                deleteUser(userId);
            }
        });
    }

    function deleteUser(userId) {
        $.ajax({
            type: "POST",
            url: "db/deleteUser.php",
            data: { userId: userId },
            success: function (response) {
                // Handle success response if needed
                // For example, refresh the table after deletion
                filterTable();
            },
            error: function (xhr, status, error) {
                // Handle error response if needed
                console.error(xhr.responseText);
            }
        });
    }

    function editUser(userId) {
        window.location.href = 'editUser.php?userId=' + userId;
    }
</script>

</html>
