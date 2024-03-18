document.addEventListener("DOMContentLoaded", function() {
  var typingTimer; // Timer identifier
  var doneTypingInterval = 1000; // Time in milliseconds (1 second)
  var initialLocation = ""; // Variable to store initial location value
  var map = L.map('previewMap');
  var tileLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    maxNativeZoom: 18,
    maxZoom: 100 // Set max native zoom level of the tile layer
  }).addTo(map);

  map.zoomControl.remove();

  function updateMap(location) {
    if (location.trim() !== '') {
      console.log('Updating map with location:', location);
      var coordinates = location.split(',').map(function(coord) {
        return parseFloat(coord.trim());
      });

      if (coordinates.length === 2 && !isNaN(coordinates[0]) && !isNaN(coordinates[1])) {
        var lat = coordinates[0];
        var lng = coordinates[1];

        if (typeof marker !== 'undefined') {
          map.removeLayer(marker);
        }

        marker = L.marker([lat, lng]).addTo(map);

        map.setView([lat, lng], 19);

        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
          .then(response => response.json())
          .then(data => {
            if (data && data.address) {
              // Filter out undefined parts of the address
              var addressParts = [data.address.road, data.address.suburb, data.address.city, data.address.state, data.address.postcode, data.address.country];
              var filteredAddressParts = addressParts.filter(part => part !== undefined);
              var address = filteredAddressParts.join(', ');

              console.log("Address:", address);
              var addressInput = document.getElementById('address');
              if (addressInput) {
                addressInput.value = address;
              }
            } else {
              console.error("Address not found.");
            }
          })
          .catch(error => {
            console.error("Error fetching address:", error);
          });

        // Update latitude and longitude inputs
        var latitudeInput = document.getElementById('latitude');
        var longitudeInput = document.getElementById('longitude');
        if (latitudeInput && longitudeInput) {
          latitudeInput.value = lat;
          longitudeInput.value = lng;
        }
      } else {
        console.error('Invalid location format for location:', location);
      }
    } else {
      console.log("Location is empty");
      // Clear previous marker and reset inputs
      if (typeof marker !== 'undefined') {
        map.removeLayer(marker);
      }
      var latitudeInput = document.getElementById('latitude');
      var longitudeInput = document.getElementById('longitude');
      var addressInput = document.getElementById('address');
      if (latitudeInput && longitudeInput && addressInput) {
        latitudeInput.value = '';
        longitudeInput.value = '';
        addressInput.value = '';
      }
    }
  }

  function checkInputValue() {
    var location = document.getElementById('location').value;
    if (location.trim() !== '') {
      console.log("Checking input value for location:", location);
      updateMap(location);
    }
  }

  function checkLatitudeLongitude() {
    var latitude = parseFloat(document.getElementById('latitude').value);
    var longitude = parseFloat(document.getElementById('longitude').value);
    if (!isNaN(latitude) && !isNaN(longitude)) {
      var location = latitude + ', ' + longitude;
      console.log("Checking latitude and longitude for location:", location);
      updateMap(location);
    }
  }

  var locationInput = document.getElementById('location');
  var latitudeInput = document.getElementById('latitude');
  var longitudeInput = document.getElementById('longitude');

  // Check input value every second
  setInterval(checkInputValue, 1000);

  initialLocation = locationInput.value;

  if (initialLocation.trim() !== '') {
    console.log("Initial location found:", initialLocation);
    updateMap(initialLocation);
  }

  // Add event listeners to latitude and longitude inputs
  latitudeInput.addEventListener('input', checkLatitudeLongitude);
  longitudeInput.addEventListener('input', checkLatitudeLongitude);
});
