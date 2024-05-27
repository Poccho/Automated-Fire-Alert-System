<?php
session_start();

// Redirect if user_id is not set in session
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch barangay_code from session if it exists
$barangay_code = isset($_SESSION['barangay_code']) ? $_SESSION['barangay_code'] : null;

// Log the barangay code in the console
echo "<script>console.log('Barangay Code:', '" . $barangay_code . "');</script>";

?>

<html>

<head>
    <style>
        /* CSS SA DELETE BUTTON*/

        #delete {
            background-color: red;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            padding: 3.5px 10px;
            font-weight: bold;
            letter-spacing: 1px;
            border: none;
            height: 30px;
        }

        #delete:hover {
            background-color: red;
            animation: slidernbw 5s linear infinite;
            color: #000;
        }

        @keyframes slidernbw {
            to {
                background-position: 20vw;
            }
        }

        /* CSS SA PIN BUTTON*/

        #pin {
            background-color: rgb(86, 179, 72);
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            padding: 10px 10px;
            font-weight: bold;
            letter-spacing: 1px;
            border: none;
            height: 30px;
        }

        #pin:hover {
            background-color: rgb(30, 255, 0);
            animation: slidernbw 5s linear infinite;
            color: #000;
        }

        #remove-route {
            background-color: rgb(125, 141, 68);
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            padding: 5px 10px;
            font-weight: bold;
            letter-spacing: 1px;
            border: none;
            height: 30px;
        }

        #coordinates {
            width: 220px;
            overflow-x: auto;
        }

        #remove-route:hover {
            background-color: rgb(200, 255, 0);
            animation: slidernbw 5s linear infinite;
            color: #000;
        }

        @keyframes slidernbw {
            to {
                background-position: 20vw;
            }
        }

        .flash-red {
            animation: flash-red 0.37s infinite alternate;
        }

        .flash-green {
            background-color: lightgreen;
        }

        @keyframes flash-red {
            0% {
                background-color: white;
            }

            100% {
                background-color: red;
            }
        }

        @keyframes flash-green {
            0% {
                background-color: white;
            }

            100% {
                background-color: green;
            }
        }
    </style>
</head>

<body>
    <table id="dynamic-table">
        <thead>
            <tr>
                <th>Address</th>
                <th>Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // PHP code to fetch coordinates from the database and display them in the table
            include "connection.php";

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "
    SELECT a.latitude, a.longitude, a.label, DATE_FORMAT(a.alert_time, '%Y-%m-%d %H:%i:%s') AS alert_time, a.alert_status AS status
    FROM alert AS a
    JOIN (
        SELECT latitude, longitude, MIN(alert_time) AS min_alert_time
        FROM alert
        WHERE barangay_code = '$barangay_code'
        GROUP BY latitude, longitude
    ) AS b ON a.latitude = b.latitude AND a.longitude = b.longitude AND a.alert_time = b.min_alert_time
    ORDER BY a.alert_status DESC, a.alert_time ASC;
";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $building = $row["label"];
                    $latitude = $row["latitude"];
                    $longitude = $row["longitude"];
                    $alert_time = $row["alert_time"];

                    // Check if "alert_status" key exists in the row
                    if (isset($row["status"])) {
                        $status = $row["status"] == 1 ? "Active" : "Inactive";
                        $statusClass = $row["status"] == 1 ? "flash-red" : "flash-green"; // Determine the status class
                        $buttonDisabled = $row["status"] == 0 ? "disabled" : ""; // Determine if buttons should be disabled
                        $buttonColor = $row["status"] == 0 ? "grey" : ""; // Determine button color
                        // Determine delete button status
                        $deleteButtonDisabled = $row["status"] == 1 ? "disabled" : ""; // Disable delete button if other buttons are active
                        $deleteButtonColor = $row["status"] == 1 ? "grey" : ""; // Set delete button color to grey if other buttons are active
                    } else {
                        // Default values if key is undefined
                        $status = "Unknown";
                        $statusClass = "flash-green"; // Default class
                        $buttonDisabled = ""; // Default
                        $buttonColor = ""; // Default
                        $deleteButtonDisabled = ""; // Default
                        $deleteButtonColor = ""; // Default
                    }

                    // Generate HTML for table row with button color determined by status
                    echo '<tr id="' . $latitude . ',' . $longitude . '" class="' . $statusClass . '" data-status="' . $row["status"] . '">
                    <td>' . $building . '</td>
                    <td style="width: 130px;">' . $alert_time . '</td>
                    <td >
                        <select id="status_' . $latitude . '_' . $longitude . '" onchange="changeStatus(' . $latitude . ', ' . $longitude . ', this.value)">
                            <option value="1"' . ($status == "Active" ? ' selected' : '') . '>Active</option>
                            <option value="0"' . ($status == "Inactive" ? ' selected' : '') . '>Inactive</option>
                        </select>
                    </td>                            
                    <td>
                        <button id="pin" style="background-color: ' . $buttonColor . ';" onclick="pinLocation(' . $latitude . ', ' . $longitude . ', \'' . $building . '\')" ' . $buttonDisabled . '><i class="fa-solid fa-map-pin fa-lg"></i></button>
                        <button id="remove-route" style="background-color: ' . $buttonColor . ';" onclick="removeRoute(' . $latitude . ', ' . $longitude . ')" ' . $buttonDisabled . '><i class="fa-solid fa-eraser fa-lg"></i></button>';
                    // Generate the link for deleting with latitude, longitude, and alert time as URL parameters
                    echo '<a href="report.php?latitude=' . urlencode($latitude) . '&longitude=' . urlencode($longitude) . '&alert_time=' . urlencode($alert_time) . '&building=' . urlencode($building) . '" target="_blank" id="delete" style="background-color: ' . $deleteButtonColor . ';" ' . $deleteButtonDisabled . '><i class="fa-regular fa-trash-can fa-lg"></i></a>';
                    echo '</td>
                </tr>';
                }
            } else {
                echo "<tr><td colspan='4' style='text-align: center;'>No coordinates found</td></tr>";
            }

            $conn->close();
            ?>

        </tbody>
    </table>



    <script>
        function changeStatus(latitude, longitude, status) {
            // Make an AJAX call to update the status in the database
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "db/update_status.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log("Status updated successfully");
                        // Toggle flashing class based on the updated status
                        let row = document.getElementById(latitude + ',' + longitude);
                        if (status == 1) {
                            row.classList.add('flash-red');
                            row.classList.remove('flash-green');
                        } else {
                            row.classList.add('flash-green');
                            row.classList.remove('flash-red');
                            // Remove the route if the status is inactive
                            statusRemoveRoute(latitude, longitude);
                        }
                        // Update button colors
                        let buttons = row.querySelectorAll("button");
                        updateButtonColors(status, buttons);
                    } else {
                        console.error("Failed to update status");
                        // Show error message using SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Update Status',
                            text: 'An error occurred while updating the status. Please try again later.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            };
            xhr.send("latitude=" + latitude + "&longitude=" + longitude + "&status=" + status);

            // Prevent default form submission or page refresh
            event.preventDefault(); // Add this line
        }

        function statusRemoveRoute(latitude, longitude) {
            let existingRoute = findRoute(latitude, longitude);

            if (existingRoute) {
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
                    'Fire Out!',
                    'Fire is now considered as Inactive!',
                    'success'
                );
            } else {
                // Even if the route is not found in the routes array, still remove the ETA
                console.log("Route not found, removing ETA and setting map view to user's station location.");

                // Remove ETA
                removeETA(latitude, longitude);

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
                    'Fire Out!',
                    'Fire is now considered as Inactive!',
                    'success'
                );
            }
        }

    </script>


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