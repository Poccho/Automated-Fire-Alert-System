document.addEventListener("DOMContentLoaded", function() {
    var initialLocation = ""; // Variable to store initial location value
    var map = L.map('previewMap');
    var tileLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
      maxNativeZoom: 18,
      maxZoom: 100 // Set max native zoom level of the tile layer
    }).addTo(map);
  
    map.zoomControl.remove();
    var marker;
  
    function updateMap(lat, lng) {
      if (!isNaN(lat) && !isNaN(lng)) {
        console.log('Updating map with latitude:', lat, 'and longitude:', lng);
  
        if (typeof marker !== 'undefined') {
          map.removeLayer(marker);
        }
  
        marker = L.marker([lat, lng]).addTo(map);
  
        map.setView([lat, lng], 19);
  
        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
          .then(response => response.json())
          .then(data => {
            if (data && data.display_name) {
              var address = data.display_name;
              console.log("Address:", address);
              // Show address on the map as a popup
              marker.bindPopup(address).openPopup();
            } else {
              console.error("Address not found.");
            }
          })
          .catch(error => {
            console.error("Error fetching address:", error);
          });
      } else {
        console.log("Latitude or longitude is empty");
        if (typeof marker !== 'undefined') {
          map.removeLayer(marker);
        }
      }
    }
  
    function checkLatitudeLongitude() {
      var latitude = parseFloat(document.getElementById('latitude').value);
      var longitude = parseFloat(document.getElementById('longitude').value);
      updateMap(latitude, longitude);
    }
  
    var latitudeInput = document.getElementById('latitude');
    var longitudeInput = document.getElementById('longitude');
  
    // Add event listeners to latitude and longitude inputs
    latitudeInput.addEventListener('input', checkLatitudeLongitude);
    longitudeInput.addEventListener('input', checkLatitudeLongitude);
  });
  