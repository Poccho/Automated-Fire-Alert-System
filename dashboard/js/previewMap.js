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
            // Construct the address without the building name
            var address = data.address.road + ', ' + data.address.suburb + ', ' + data.address.city + ', ' + data.address.state + ', ' + data.address.postcode + ', ' + data.address.country;
            
            console.log("Address:", address);
            document.getElementById('address').value = address;
          } else {
            console.error("Address not found.");
          }
        })
        .catch(error => {
          console.error("Error fetching address:", error);
        });
    } else {
      console.error('Invalid location format for location:', location);
    }
  }

  function checkInputValue() {
    var location = document.getElementById('location').value;
    if (location.trim() !== '') {
      console.log("Location:", location);
      updateMap(location);
    }
  }

  var locationInput = document.getElementById('location');

  // Check input value every second
  setInterval(checkInputValue, 1000);

  initialLocation = locationInput.value;

  if (initialLocation.trim() !== '') {
    updateMap(initialLocation);
  }
});
