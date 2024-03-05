document.addEventListener("DOMContentLoaded", function() {
  var typingTimer; // Timer identifier
  var doneTypingInterval = 1000; // Time in milliseconds (5 seconds)

  // Initialize the Leaflet map
  var map = L.map('previewMap'); // No default view set initially
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

  // Function to update the map marker based on input location
  function updateMap(location) {
    // Parse the location string to extract latitude and longitude
    var coordinates = location.split(',').map(function(coord) {
      return parseFloat(coord.trim());
    });

    // Check if coordinates are valid
    if (coordinates.length === 2 && !isNaN(coordinates[0]) && !isNaN(coordinates[1])) {
      var lat = coordinates[0];
      var lng = coordinates[1];

      // Remove existing marker if any
      if (typeof marker !== 'undefined') {
        map.removeLayer(marker);
      }

      // Create a new marker at the specified coordinates and add it to the map
      marker = L.marker([lat, lng]).addTo(map);

      // Set the map view to the marker's location
      map.setView([lat, lng], 20); // Zoom level 12
    } else {
      // Invalid coordinates
      console.error('Invalid location format');
    }
  }

  // Event listener for location input field
  document.getElementById('location').addEventListener('input', function() {
    clearTimeout(typingTimer);
    var location = this.value;
    typingTimer = setTimeout(function() {
      // Update the map marker based on the input location after 5 seconds of inactivity
      updateMap(location);
    }, doneTypingInterval);
  });
});