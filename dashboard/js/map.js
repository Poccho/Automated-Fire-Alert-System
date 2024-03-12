// Create custom icons
const fireDepartmentIcon = createIcon("./misc/fire-station.png", [50, 50]);

// Initialize map with starting view
const map = L.map("map").setView([6.073838, 125.115167], 20);

// Add initial marker with fire department icon
const marker = L.marker([6.073838, 125.115167], {
  icon: fireDepartmentIcon,
}).addTo(map);

// Prevent marker addition on map click
map.on("click", function (event) {
  event.originalEvent.preventDefault();
});

// Add initial tile layer
const initialTileLayer = addTileLayer(
  "https://maps.geoapify.com/v1/tile/carto/{z}/{x}/{y}.png?&apiKey=2462cadf96ca47d9b4946aa91addde5d"
);

// Create alternate tile layer
const alternateTileLayer = addTileLayer(
  "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}"
);


// Variable to keep track of the current active layer
let currentTileLayer = initialTileLayer;

// Function to switch between tile layers
function switchTileLayer() {
  if (currentTileLayer === initialTileLayer) {
    map.removeLayer(initialTileLayer);
    map.addLayer(alternateTileLayer);
    currentTileLayer = alternateTileLayer; // Update the current active layer
  } else {
    map.removeLayer(alternateTileLayer);
    map.addLayer(initialTileLayer);
    currentTileLayer = initialTileLayer; // Update the current active layer
  }
}


// Display coordinates on marker click


// Function to create custom icon
function createIcon(iconUrl, iconSize) {
  return L.icon({
    iconUrl: iconUrl,
    iconSize: iconSize,
  });
}

// Function to add tile layer
function addTileLayer(tileUrl) {
  return L.tileLayer(tileUrl, {
    attribution: "© OpenStreetMap contributors",
  }).addTo(map);
}

// Function to display coordinates in card
function displayCoordinates(latlng) {
  const coordinatesInfo = document.getElementById("coordinates-info");
  coordinatesInfo.textContent = `Latitude: ${latlng.lat}, Longitude: ${latlng.lng}`;
  showCoordinatesCard();
}

// Add Fullscreen control to map
map.addControl(new L.Control.Fullscreen());