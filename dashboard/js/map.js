// Create custom icons
const fireDepartmentIcon = createIcon("./misc/fire-station.png", [50, 50]);

// Initialize the map with a default view (will be updated after retrieving user's location)
// Global variables to store user's location
let userLatitude;
let userLongitude;

// Initialize the map with a default view (will be updated after retrieving user's location)
const map = L.map("map", {
  doubleClickZoom: false // Disable double-click zoom
});

map.zoomControl.setPosition('bottomleft');

// Add Fullscreen control to top right
var fullscreenControl = L.control.fullscreen({
  position: 'bottomleft'
});
map.addControl(fullscreenControl);

// Use AJAX to fetch user location and set map view accordingly
$.ajax({
    url: 'db/user_location.php',
    type: 'GET',
    dataType: 'json',
    success: function(data) {
        if (data.success) {
            // Set the user's location
            userLatitude = data.latitude;
            userLongitude = data.longitude;
            // Set the map view to the user's location
            map.setView([userLatitude, userLongitude], 18);
        } else {
            console.error('Failed to retrieve user location');
            // If failed to retrieve user location, set a default view
            map.setView([6.073838, 125.115167], 18);
        }
    },
    error: function(xhr, status, error) {
        console.error('Error occurred while retrieving user location:', error);
        // If failed to retrieve user location due to an error, set a default view
        map.setView([6.073838, 125.115167], 18);
    }
});

var userLocationControl = L.Control.extend({
  options: {
    position: 'topleft'
  },

  onAdd: function(map) {
    var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');

    container.innerHTML = `
    <a href="#" title="Set View to User Location" id="user-location-control" role="button">
      <span style="display: flex; justify-content: center; align-items: center; width: 100%; height: 100%;">
        <i class="fa-solid fa-house"></i>
      </span>
    </a>`;
  
    container.onclick = function() {
      // Check if user's location is available
      if (userLatitude !== undefined && userLongitude !== undefined) {
        // Set the map view to the user's location
        map.setView([userLatitude, userLongitude], 18);
      } else {
        console.error('User location not available');
        // If user location is not available, log an error message
      }
    };

    return container;
  }
});


var marker;
// Custom Leaflet control for search functionality// Custom Leaflet control for search functionality
// Custom Leaflet control for search functionality
var SearchControl = L.Control.extend({
  options: {
    position: 'topleft'
  },

  onAdd: function(map) {
    var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');

    container.innerHTML = `
      <div id="search-container" style="position: absolute;
        top: 10px;
        left: 10px;
        z-index: 1000;
        background-color: white;
        padding: 10px;
        border-radius: 5px;">
        <input type="text" id="search-input" placeholder="Search for a location...">
        <button id="search-button" style="height:3.5vh; width:2vw;"><i class="fa-solid fa-magnifying-glass"></i></button>
        <button id="clear-button" style="height:3.5vh; width:2vw;"><i class="fa-solid fa-trash"></i></button>
        <button id="toggle-button" style="margin-left: 5px; height:3.5vh; width:2vw;
        height:3.5vh; width:2vw;"><i class="fa-solid fa-ban"></i></button>
      </div>`;

    var toggleButton = container.querySelector('#toggle-button');
    toggleButton.addEventListener('click', function() {
      var searchInput = document.getElementById('search-input');
      var searchButton = document.getElementById('search-button');
      var clearButton = document.getElementById('clear-button');
      var toggleIcon = toggleButton.querySelector('i');

      if (searchInput.style.display === 'none') {
        searchInput.style.display = 'block';
        searchButton.style.display = 'inline-block';
        clearButton.style.display = 'inline-block';
        toggleIcon.classList.remove('fa-magnifying-glass');
        toggleIcon.classList.add('fa-ban');
      } else {
        searchInput.style.display = 'none';
        searchButton.style.display = 'none';
        clearButton.style.display = 'none';
        toggleIcon.classList.remove('fa-ban');
        toggleIcon.classList.add('fa-magnifying-glass');
      }
    });

    // Event listener for search button click
    container.querySelector('#search-button').addEventListener('click', async function() {
      var searchText = document.getElementById('search-input').value;
      if (searchText.trim() !== '') {
        try {
          const response = await fetch(`https://us1.locationiq.com/v1/search.php?key=pk.6623f3349e3d5aa796d50fab434cd425&q=${encodeURIComponent(searchText)}&format=json`);
          if (!response.ok) {
            throw new Error('Failed to fetch location.');
          }
          const data = await response.json();
          
          if (data && data.length > 0) {
            const { lat, lon } = data[0];
            const newLatLng = L.latLng(parseFloat(lat), parseFloat(lon));
    
            if (marker) {
              map.removeLayer(marker);
            }
    
            marker = L.marker(newLatLng).addTo(map);
            
            map.flyTo(newLatLng, 18, {
              animate: true,
              duration: 0.5, // Adjust the duration here (in seconds)
              easeLinearity: 0.5 // Adjust the ease (0.0 to 1.0)
            });
          } else {
            swal('Location not found!', '', 'error');
          }
        } catch (error) {
          console.error('Error fetching location:', error);
          swal('Error', 'Error fetching location. Please try again later.', 'error');
        }
      } else {
        swal('Warning', 'Please enter a location!', 'warning');
      }
    });
    
    // Event listener for clear button click
    container.querySelector('#clear-button').addEventListener('click', function() {
      document.getElementById('search-input').value = ''; // Clear input
      if (marker) {
        map.removeLayer(marker); // Remove marker
        marker = null; // Reset marker variable
      }
    });

    return container;
  }
});

// Add the custom search control to the map
map.addControl(new SearchControl());

// Add the custom control to the map with position set to 'bottomleft'
map.addControl(new userLocationControl({ position: 'bottomleft' }));

// Add initial marker with fire department icon
// Define an array of marker data with their coordinates and tooltip text
const markerData = [
  { coordinates: [6.073838, 125.115167], text: "Barangay Fatima Volunteer Fire Brigade" },
  { coordinates: [6.0722924,125.1410603], text: "BFP CALUMPANG FIRE SUBSTATION" },
  { coordinates: [6.0796018,125.1468585], text: "Bureau of Fire Protection Sarangani" },
  { coordinates: [6.1145925,125.1706236], text: "General Santos City Fire Office" },
  { coordinates: [6.1313112,125.130319], text: "Barangay Apopong Disaster Risk Response Team" }
  // Add more marker data as needed
];

// Loop through the marker data and create markers
markerData.forEach(data => {
  const marker = L.marker(data.coordinates, {
    icon: fireDepartmentIcon,
  }).addTo(map);

  // Add tooltip to the marker with custom text
  marker.bindTooltip(data.text, {
    permanent: true, // Tooltip will be permanently shown
    direction: 'top' // Adjust direction according to your preference
  });

  // Event handler for marker click
  marker.on('click', function (e) {
    // Set the view of the map to the coordinates of the clicked marker
    map.setView(marker.getLatLng());
  });

  // Event handler for double-click on marker
  marker.on('dblclick', function (e) {
    // Zoom to the maximum available zoom level
    map.setZoom(19);
  });
});


// Prevent marker addition on map click
map.on("click", function (event) {
  event.originalEvent.preventDefault();
});

// Add initial tile layer
const initialTileLayer = addTileLayer(
  "https://maps.geoapify.com/v1/tile/carto/{z}/{x}/{y}.png?&apiKey=2462cadf96ca47d9b4946aa91addde5d",
  19, // Maximum native zoom level
  100 // Maximum zoom level
);

// Create alternate tile layer
const alternateTileLayer = addTileLayer(
  "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
  19, // Maximum native zoom level
  100 // Maximum zoom level
);

// Variable to keep track of the current active layer
let currentTileLayer = initialTileLayer;

// Function to switch between tile layers
function switchTileLayer(button) {
  if (currentTileLayer === initialTileLayer) {
    map.removeLayer(initialTileLayer);
    map.addLayer(alternateTileLayer);
    currentTileLayer = alternateTileLayer; // Update the current active layer
    button.textContent = "Switch to  DIGITAL  MAP"; // Update button text
  } else {
    map.removeLayer(alternateTileLayer);
    map.addLayer(initialTileLayer);
    currentTileLayer = initialTileLayer; // Update the current active layer
    button.textContent = "Switch to SATELLITE MAP"; // Update button text
  }
}

// Function to create custom icon
function createIcon(iconUrl, iconSize) {
  return L.icon({
    iconUrl: iconUrl,
    iconSize: iconSize,
  });
}

// Function to add tile layer with custom max zoom levels
function addTileLayer(tileUrl, maxNativeZoom, maxZoom) {
  return L.tileLayer(tileUrl, {
    attribution: "© OpenStreetMap contributors",
    maxNativeZoom: maxNativeZoom,
    maxZoom: maxZoom
  }).addTo(map);
}
