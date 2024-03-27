<?php
session_start();

if (!isset ($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<html>

<head>
</head>

<body>
    <table id="dynamic-table">
        <thead>
            <tr>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // PHP code to fetch coordinates from the database and display them in the table
            include "connection.php";

            if ($conn->connect_error) {
                die ("Connection failed: " . $conn->connect_error);
            }

            $sql = "(SELECT latitude, longitude, label, COUNT(*) AS count FROM alert GROUP BY latitude, longitude HAVING COUNT(*) > 1 ORDER BY alert_time ASC)
UNION 
(SELECT latitude, longitude, label, COUNT(*) AS count FROM alert GROUP BY latitude, longitude HAVING COUNT(*) = 1 ORDER BY alert_time ASC)
";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $building = $row["label"];
                    $latitude = $row["latitude"];
                    $longitude = $row["longitude"];
                    $count = $row["count"];
                    echo '<tr id="' . $latitude . ',' . $longitude . '">
                            <td>' . $building . ' (#' . $count . ')' . '</td>
                            <td>
                                <button id="pin" onclick="pinLocation(' . $latitude . ', ' . $longitude . ', \'' . $building . '\')"><i class="fa-solid fa-map-pin fa-bounce fa-lg"></i></button>
                                <button id="remove-route" onclick="removeRoute(' . $latitude . ', ' . $longitude . ')"><i class="fa-solid fa-eraser fa-lg"></i></button>
                                <button id="delete" onclick="remove(' . $latitude . ', ' . $longitude . ', \'' . $building . '\')"><i class="fa-regular fa-trash-can fa-lg"></i></button>
                            </td>
                        </tr>';

                }
            } else {
                echo "<tr><td colspan='3' style='text-align: center;'>No coordinates found</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>

    <script>
        let routes = []; // Store routes for each set of coordinates
        function pinLocation(latitude, longitude, building) {
            var etaContainer = document.getElementById("eta-container");
            var div = document.createElement("div");
            div.classList.add("eta");
            div.classList.add(`${latitude}-${longitude}`); // Adding latitude and longitude as class
            div.innerHTML = `<p>Address: ${building}</p><p>Latitude: ${latitude}, Longitude: ${longitude}</p><p>ETA: <strong><span id='eta-value'></span></strong></p>`;
            etaContainer.appendChild(div); // Append ETA div to container

            let existingRoute = findRoute(latitude, longitude);
            if (existingRoute) {
                // Route already exists, just set the map view and highlight the table cells
                map.setView([latitude, longitude], 20);
                // Calculate and update ETA
                calculateAndUpdateETA(existingRoute);
            } else {
                // Use AJAX to fetch user's location and set as the first waypoint when pinning a route
                $.ajax({
                    url: 'db/user_location.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        if (data.success) {
                            // Route doesn't exist, create a new one
                            let route = L.Routing.control({
                                waypoints: [L.latLng(data.latitude, data.longitude), L.latLng(latitude, longitude)],
                                router: L.Routing.osrmv1({
                                    serviceUrl: 'https://router.project-osrm.org/route/v1',
                                    profile: 'car' // You can change this based on your vehicle type
                                }),
                                draggableWaypoints: false, // Disable dragging of waypoints
                                addWaypoints: false, // Disable adding new waypoints
                                lineOptions: {
                                    styles: [{ color: 'red', opacity: 0.6, weight: 4 }]
                                }
                            }).addTo(map);

                            let circle = L.circle([latitude, longitude], {
                                color: "red",
                                fillColor: "#f03",
                                fillOpacity: 0.5,
                                radius: 25,
                            }).addTo(map);

                            circle._path.classList.add("pulsating-circle");

                            // Store the route with corresponding coordinates
                            routes.push({ latitude, longitude, route, circle });

                            // Set the map view
                            map.setView([latitude, longitude], 19);

                            // Calculate and update ETA
                            calculateAndUpdateETA(route);

                            // Debugging: Log the latitude and longitude values
                            console.log("Latitude:", latitude);
                            console.log("Longitude:", longitude);
                        } else {
                            console.error('Failed to retrieve user location');
                            // Route doesn't exist, set a default view
                            map.setView([latitude, longitude], 19);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error occurred while retrieving user location:', error);
                        // Route doesn't exist, set a default view
                        map.setView([latitude, longitude], 19);
                    }
                });
            }
        }

        function calculateAndUpdateETA(routeControl) {
            routeControl.on('routesfound', function (e) {
                var routes = e.routes;
                if (routes && routes.length > 0) {
                    var eta = routes[0].summary.totalTime; // Total time in seconds
                    var etaSpan = document.getElementById("eta-value");
                    etaSpan.textContent = formatETA(eta);
                }
            });
        }

        function formatETA(seconds) {
            var hours = Math.floor(seconds / 3600);
            var minutes = Math.floor((seconds % 3600) / 60);
            return hours + "h " + minutes + "m";
        }





        function findRoute(latitude, longitude) {
            // Find the route with given coordinates
            return routes.find(route => route.latitude === latitude && route.longitude === longitude);
        }

        function remove(latitude, longitude) {

            let existingRoute = findRoute(latitude, longitude);

            if (existingRoute) {
                // Set the map view to the coordinates before showing the confirmation alert
                map.setView([latitude, longitude], 20);

                // Ask for confirmation using SweetAlert
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with deletion
                        let xhr = new XMLHttpRequest();
                        xhr.open("POST", "db/delete_coordinates.php", true);
                        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === XMLHttpRequest.DONE) {
                                if (xhr.status === 200) {
                                    console.log("Connected to delete_coordinates.php successfully");
                                    let response = xhr.responseText;
                                    if (response === "Deletion successful") {
                                        console.log("Database deletion successful");
                                        deleteRoute(latitude, longitude);
                                        removeETA(latitude, longitude);
                                        Swal.fire(
                                            'Deleted!',
                                            'Your file has been deleted.',
                                            'success'
                                        )
                                        $.ajax({
                                            url: 'db/user_location.php',
                                            type: 'GET',
                                            dataType: 'json',
                                            success: function (data) {
                                                if (data.success) {
                                                    // Set the map view to the user's station location
                                                    map.setView([data.latitude, data.longitude], 20);
                                                } else {
                                                    console.error('Failed to retrieve user station location');
                                                }
                                            },
                                            error: function (xhr, status, error) {
                                                console.error('Error occurred while retrieving user station location:', error);
                                            }
                                        });
                                    } else {
                                        console.error("Database deletion failed");
                                        Swal.fire(
                                            'Failed!',
                                            'Database deletion failed.',
                                            'error'
                                        )
                                    }
                                } else {
                                    console.error("Connection to delete_coordinates.php failed");
                                    Swal.fire(
                                        'Failed!',
                                        'Connection to server failed.',
                                        'error'
                                    )
                                }
                            }
                        };
                        xhr.send("latitude=" + latitude + "&longitude=" + longitude);
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Coordinates not pinned',
                    text: 'Coordinates can only be deleted if they are pinned on the map to prevent accidental deletion.',
                    confirmButtonText: 'OK'
                });
            }
        }

        function deleteRoute(latitude, longitude) {
            console.log("Deleting route: Lat - " + latitude + ", Long - " + longitude);

            let indexToDelete = -1;
            // Find the index of the route to delete based on coordinates
            for (let i = 0; i < routes.length; i++) {
                if (routes[i].latitude === latitude && routes[i].longitude === longitude) {
                    indexToDelete = i;
                    break;
                }
            }

            if (indexToDelete !== -1) {
                console.log("Removing route from map");
                // Remove the route and circle from the map
                map.removeControl(routes[indexToDelete].route);
                map.removeLayer(routes[indexToDelete].circle);
                routes.splice(indexToDelete, 1);
                map.setView([6.073838, 125.115167], 100);
                L.marker([6.073838, 125.115167], { icon: fireDepartmentIcon }).addTo(map);

            } else {
                console.error("Route not found in routes array");
            }
        }

        function removeRoute(latitude, longitude) {
            let existingRoute = findRoute(latitude, longitude);

            if (existingRoute) {
                // Ask for confirmation using SweetAlert
                Swal.fire({
                    title: 'Remove Route?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with deletion
                        deleteRoute(latitude, longitude);

                        // Log the constructed class name
                        var etaClass = `eta ${latitude}-${longitude}`;
                        console.log("ETA Class:", etaClass);

                        // Remove ETA
                        console.log("Removing ETA...");
                        removeETA(latitude, longitude); // Ensure that removeETA is called

                        // Send an AJAX request to retrieve the user's station location
                        $.ajax({
                            url: 'db/user_location.php',
                            type: 'GET',
                            dataType: 'json',
                            success: function (data) {
                                if (data.success) {
                                    // Set the map view to the user's station location
                                    map.setView([data.latitude, data.longitude], 20);
                                } else {
                                    console.error('Failed to retrieve user station location');
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('Error occurred while retrieving user station location:', error);
                            }
                        });

                        Swal.fire(
                            'Route Removed!',
                            'Coordinates has been DELETED',
                            'success'
                        )
                    }
                });
            } else {
                Swal.fire({
                    icon: 'question',
                    title: 'Coordinates not pinned',
                    text: 'ROUTES can only be REMOVED if they are PINNED on the MAP.',
                    confirmButtonText: 'OK'
                });
            }
        }



        function removeETA(latitude, longitude) {
            var etaClass = `eta ${latitude}-${longitude}`;
            var etaElements = document.getElementsByClassName(etaClass);

            // Loop through all elements with the specified class and remove each one
            for (var i = etaElements.length - 1; i >= 0; i--) {
                etaElements[i].remove();
            }

            if (etaElements.length > 0) {
                console.log("ETA Removed!"); // Logging confirmation of removal
            } else {
                console.log("ETA Element not found!"); // Logging if the element is not found
            }
        }


    </script>


</body>

</html>