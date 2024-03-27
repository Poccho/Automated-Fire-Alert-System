<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/userList.css">


</head>

<body>
    <?php include "navBar.php"; ?>

    <form id="filterForm" onsubmit="submitForm(event)"> 
    <input id="searchInput" class="search-input"
            oninput="filterTable()" placeholder="Search">

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
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' style='text-align: center;'>NO RECORDS FOUND</td></tr>";
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
      success: function(response) {
        $('table tbody').html(response);
      }
    });
  }
    </script>
</html>