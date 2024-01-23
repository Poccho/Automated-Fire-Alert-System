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
  .addEventListener("click", function (ev) {
    if (map.hasLayer(initialTileLayer)) {
      map.addLayer(alternateTileLayer);
      map.removeLayer(initialTileLayer);
    } else {
      map.addLayer(initialTileLayer);
      map.removeLayer(alternateTileLayer);
    }
  });

var coordinatesCard = document.getElementById("coordinates-card");
var coordinatesInfo = document.getElementById("coordinates-info");

// Event listener for clicking on the marker to display coordinates in a card
marker.on("click", function (e) {
  coordinatesInfo.textContent = `Latitude: ${e.latlng.lat}, Longitude: ${e.latlng.lng}`;
  coordinatesCard.style.display = "block";
});

// Close card when clicked outside the card area
map.on("click", function (e) {
  coordinatesCard.style.display = "none";
});

// TRIAL FUNCTION FOR SWITCHING TILE MAPS
document.getElementById("switch-layers").addEventListener("click", function (ev) {
  switchTileLayer();
});
