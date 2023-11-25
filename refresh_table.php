<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./css/style.css" />
    
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css"
    />
  </head>
</body>
 <table id="dynamic-table">
        <thead>
            <tr>
                <th>Coordinates</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // PHP code to fetch coordinates from the database and display them in the table

            include "connection.php";

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }


            $sql = "SELECT alert_id, coordinates, DATE_FORMAT(alert_time, '%H:%i') AS alert_time FROM alert";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row["alert_id"];
                $coordinates = $row["coordinates"];
                    echo '<tr>
                            <td>' . $coordinates . '</td>
                            <td>
                                <button id="pin" onclick="pinLocation()"><i class="fa-solid fa-map-pin fa-bounce fa-lg"></i></button>
                                <button id="delete" onclick="remove()"><i class="fa-regular fa-trash-can fa-lg"></i></button>
                            </td>
                        </tr>';
            }

            } else {
                echo "<tr><td colspan='3'>No coordinates found</td></tr>";
            }

            $conn->close();
            ?>


        </tbody>
        
    </table>
            <script>
                    // FUNCTIUON FOR PINNING LOCATIONS

                    function pinLocation() {
                    route = L.Routing.control({
                        waypoints: [L.latLng(6.073838, 125.115167), L.latLng("<?php echo $coordinates; ?>")],
                    }).addTo(map);
                    map.setView(["<?php echo $coordinates; ?>"], 20);
                    circle = L.circle(["<?php echo $coordinates; ?>"], {
                        color: "red",
                        fillColor: "#f03",
                        fillOpacity: 0.5,
                        radius: 50,
                    }).addTo(map);
                    }
                    
                    function remove() {
                    map.removeControl(route);
                    map.removeControl(circle);
                    map.setView([6.073838, 125.115167], 100);
                    marker = L.marker([6.073838, 125.115167], { icon: fireDepartment }).addTo(
                        map
                    );
                    }

                    [...links].map((link, index) => {
                    link.addEventListener("click", () => onLinkClick(link, index), false);
                    });

            </script>
</body>
</html>