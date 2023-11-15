//images for custom icons
const fireIcon = L.icon({
  iconUrl: "fire.png",
  iconSize: [60, 60],
});
const fireDepartment = L.icon({
  iconUrl: "fire-station.png",
  iconSize: [50, 50],
});

// SETS THE STARTING VIEW WHEN THE MAP LOADS

var map = L.map("map");
map.setView([6.073838, 125.115167], 150);

var marker = L.marker([6.073838, 125.115167], { icon: fireDepartment }).addTo(
  map
);

// Add satelite tile layer

<<<<<<< HEAD
var initialTileLayer = L.tileLayer(
  "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
  {
    attribution: "© OpenStreetMap contributors",
  }
).addTo(map);

// Another tile layer (you can replace this with a different tile layer)
var alternateTileLayer = L.tileLayer(
=======
var tilelayer1 = L.tileLayer(
  "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
  {
    attribution:
      '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  }
);
var tilelayer2 = L.tileLayer(
>>>>>>> 99eeac4f9d46dba7d55cb057c7da5e228d55bc13
  "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
  {
    attribution: "© OpenStreetMap contributors",
  }
);

<<<<<<< HEAD
// Function to switch between tile layers
function switchTileLayer() {
  if (map.hasLayer(initialTileLayer)) {
    map.removeLayer(initialTileLayer);
    map.addLayer(alternateTileLayer);
  } else {
    map.removeLayer(alternateTileLayer);
    map.addLayer(initialTileLayer);
  }
}
=======
tilelayer1.addTo(map);

// TRIAL FUNCTION FOR SWITCHING TILE MAPS

document
  .getElementById("switch-layers")
  .addEventHandler("click", function (ev) {
    if (map.hasLayer(tilelayer1)) {
      map.addLayer(tilelayer2);
      map.removeLayer(tilelayer1);
    } else {
      map.addLayer(tilelayer1);
      map.removeLayer(tilelayer2);
    }
  });

>>>>>>> 99eeac4f9d46dba7d55cb057c7da5e228d55bc13
/*
L.tileLayer(
  "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
  {
    attribution: "© OpenStreetMap contributors",
  }
).addTo(map);

/*    SAMPLE MARKERS
var marker = L.marker([6.0694, 125.1262], { icon: fireIcon }).addTo(map);

var marker = L.marker([6.07449, 125.1146], { icon: fireIcon }).addTo(map);

var marker = L.marker([6.0678, 125.1195], { icon: fireIcon }).addTo(map);

var marker = L.marker([6.073838, 125.115167], { icon: fireDepartment }).addTo(
  map
);
*/

function changeLocation1() {
  lat = 6.0678;
  long = 125.1195;
  route1 = L.Routing.control({
    waypoints: [L.latLng(6.073838, 125.115167), L.latLng(lat, long)],
  }).addTo(map);
  map.setView([lat, long], 20);
  circle = L.circle([lat, long], {
    color: "red",
    fillColor: "#f03",
    fillOpacity: 0.5,
    radius: 50, // Adjust the radius as needed
  }).addTo(map);
}

function remove1() {
  map.removeControl(route1);
  map.removeControl(circle);
  map.setView([6.073838, 125.115167], 100);
  marker = L.marker([6.073838, 125.115167], { icon: fireDepartment }).addTo(
    map
  );
}

function changeLocation2() {
  lat = 6.07449;
  long = 125.1146;
  route2 = L.Routing.control({
    waypoints: [L.latLng(6.073838, 125.115167), L.latLng(lat, long)],
  }).addTo(map);
  map.setView([lat, long], 150);
  circle = L.circle([lat, long], {
    color: "red",
    fillColor: "#f03",
    fillOpacity: 0.5,
    radius: 50, // Adjust the radius as needed
  }).addTo(map);
}

function remove2() {
  map.removeControl(route2);
  map.removeControl(circle);
  map.setView([6.073838, 125.115167], 100);
}

function changeLocation3() {
  lat = 6.0694;
  long = 125.1262;
  route3 = L.Routing.control({
    waypoints: [L.latLng(6.073838, 125.115167), L.latLng(lat, long)],
  }).addTo(map);
  map.setView([lat, long], 150);
  circle = L.circle([lat, long], {
    color: "red",
    fillColor: "#f03",
    fillOpacity: 0.5,
    radius: 50, // Adjust the radius as needed
  }).addTo(map);
}

function remove3() {
  map.removeControl(route3);
  map.removeControl(circle);
  map.setView([6.073838, 125.115167], 100);
}

[...links].map((link, index) => {
  link.addEventListener("click", () => onLinkClick(link, index), false);
});
