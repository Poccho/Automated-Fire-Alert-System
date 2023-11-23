//images for custom icons
const fireIcon = L.icon({
  iconUrl: "fire.png",
  iconSize: [60, 60],
});
const fireDepartment = L.icon({
  iconUrl: "./misc/fire-station.png",
  iconSize: [50, 50],
});

// SETS THE STARTING VIEW WHEN THE MAP LOADS

var map = L.map("map");
map.setView([6.073838, 125.115167], 150);

var marker = L.marker([6.073838, 125.115167], { icon: fireDepartment }).addTo(
  map
);

// Add satelite tile layer

var initialTileLayer = L.tileLayer(
  "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
  {
    attribution: "© OpenStreetMap contributors",
  }
).addTo(map);

// Another tile layer (you can replace this with a different tile layer)
var alternateTileLayer = L.tileLayer(
  "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
  {
    attribution: "© OpenStreetMap contributors",
  }
);

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

// FUNCTIUON FOR PINNING LOCATIONS

function pinLocation() {
  var coordinates = "<?php echo $coordinates; ?>";
  route1 = L.Routing.control({
    waypoints: [L.latLng(6.073838, 125.115167), L.latLng(coordinates)],
  }).addTo(map);
  map.setView([coordinates], 20);
  circle = L.circle([coordinates], {
    color: "red",
    fillColor: "#f03",
    fillOpacity: 0.5,
    radius: 50,
  }).addTo(map);
}

function remove() {
  map.removeControl(route1);
  map.removeControl(circle);
  map.setView([6.073838, 125.115167], 100);
  marker = L.marker([6.073838, 125.115167], { icon: fireDepartment }).addTo(
    map
  );
}

[...links].map((link, index) => {
  link.addEventListener("click", () => onLinkClick(link, index), false);
});
