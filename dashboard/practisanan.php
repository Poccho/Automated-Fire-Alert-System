<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Time Calculator</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <style>
        #map {
            height: 400px;
        }
        .eta {
            margin-top: 10px;
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div id="map"></div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <script>
        var map = L.map('map').setView([51.505, -0.09], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.Routing.control({
            waypoints: [
                L.latLng(51.5, -0.1), // Starting point
                L.latLng(51.51, -0.12) // Ending point
            ],
            routeWhileDragging: true
        }).addTo(map).on('routesfound', function(e) {
            var routes = e.routes;
            routes.forEach(function(route, index) {
                var summary = route.summary;
                var travelTime = summary.totalTime / 60; // Convert seconds to minutes
                var destination = route.waypoints[1].latLng;
                var etaDiv = document.createElement('div');
                etaDiv.className = 'eta';
                etaDiv.id = 'eta_' + destination.lat.toFixed(5) + '_' + destination.lng.toFixed(5); // Using lat and lng for the ID
                etaDiv.innerHTML = '<p>ETA: ' + travelTime.toFixed(2) + ' minutes</p>';
                document.body.appendChild(etaDiv);
            });
        });
    </script>
</body>
</html>
