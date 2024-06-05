document.addEventListener("DOMContentLoaded", function () {
  // Initialize the map
  var map = L.map("map").setView([6.1129234, 125.1717093], 20); // Set the initial coordinates and zoom level

  // Add a tile layer (map tiles)
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution:
      '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(map);

  // Add a marker to the map
  var marker = L.marker([6.1129234, 125.1717093]).addTo(map).openPopup();

  // Remove the default zoom control from the map
  map.zoomControl.remove();

  // Add click event listener to the map
  map.on("click", function (e) {
    // Get coordinates from the clicked location
    var lat = e.latlng.lat.toFixed(6);
    var lng = e.latlng.lng.toFixed(6);

    // Update input fields with the coordinates
    document.getElementById("coordinates").value = lat + ", " + lng;

    // Move the marker to the clicked location
    marker.setLatLng(e.latlng).update();
  });

  // Custom Leaflet control for search functionality
  var SearchControl = L.Control.extend({
    options: {
      position: "topleft",
    },

    onAdd: function (map) {
      var container = L.DomUtil.create("div", "leaflet-bar leaflet-control");

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

      var toggleButton = container.querySelector("#toggle-button");
      toggleButton.addEventListener("click", function () {
        var searchInput = document.getElementById("search-input");
        var searchButton = document.getElementById("search-button");
        var clearButton = document.getElementById("clear-button");
        var toggleIcon = toggleButton.querySelector("i");

        if (searchInput.style.display === "none") {
          searchInput.style.display = "block";
          searchButton.style.display = "inline-block";
          clearButton.style.display = "inline-block";
          toggleIcon.classList.remove("fa-magnifying-glass");
          toggleIcon.classList.add("fa-ban");
        } else {
          searchInput.style.display = "none";
          searchButton.style.display = "none";
          clearButton.style.display = "none";
          toggleIcon.classList.remove("fa-ban");
          toggleIcon.classList.add("fa-magnifying-glass");
        }
      });

      // Event listener for search button click
      container
        .querySelector("#search-button")
        .addEventListener("click", function (e) {
          e.preventDefault(); // Prevent form submission
          var searchText = document.getElementById("search-input").value;
          if (searchText.trim() !== "") {
            fetch(
              "https://nominatim.openstreetmap.org/search?format=json&q=" +
                searchText
            )
              .then((response) => response.json())
              .then((data) => {
                if (data && data.length > 0) {
                  var lat = parseFloat(data[0].lat);
                  var lon = parseFloat(data[0].lon);
                  var newLatLng = new L.LatLng(lat, lon);
                  if (marker) {
                    map.removeLayer(marker);
                  }
                  marker = L.marker(newLatLng).addTo(map); // Remove zIndexOffset to ensure the pin is not behind the button
                  map.flyTo(newLatLng, 18, {
                    animate: true,
                    duration: 0.5, // Adjust the duration here (in seconds)
                    easeLinearity: 0.5, // Adjust the ease (0.0 to 1.0)
                  });

                  // Update the input field with the coordinates
                  document.getElementById("coordinates").value =
                    lat + ", " + lon;
                } else {
                  alert("Location not found!");
                }
              })
              .catch((error) => {
                console.error("Error fetching location:", error);
                alert("Error fetching location. Please try again later.");
              });
          } else {
            alert("Please enter a location!");
          }
        });

      // Event listener for clear button click
      container
        .querySelector("#clear-button")
        .addEventListener("click", function () {
          document.getElementById("search-input").value = ""; // Clear input
          if (marker) {
            map.removeLayer(marker); // Remove marker
            marker = null; // Reset marker variable
          }
        });

      return container;
    },
  });

  // Add the custom search control to the map
  map.addControl(new SearchControl());

  // Create a container for the pin location button
  var pinLocationContainer = L.DomUtil.create(
    "div",
    "leaflet-bar leaflet-control"
  );
  pinLocationContainer.innerHTML =
    '<button id="pin-location-button" style="height:3.5vh; width:2vw;"><i class="fa-solid fa-thumbtack"></i></button>';

  // Position the pin location button in the lower right corner of the map
  pinLocationContainer.style.position = "absolute";
  pinLocationContainer.style.bottom = "10px";
  pinLocationContainer.style.right = "10px";
  pinLocationContainer.style.zIndex = "1000";

  // Add event listener for pin location button click
  pinLocationContainer
    .querySelector("#pin-location-button")
    .addEventListener("click", function () {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
          var lat = position.coords.latitude;
          var lon = position.coords.longitude;
          var newLatLng = new L.LatLng(lat, lon);
          if (marker) {
            map.removeLayer(marker);
          }
          marker = L.marker(newLatLng).addTo(map); // Remove zIndexOffset to ensure the pin is not behind the button
          map.flyTo(newLatLng, 18, {
            animate: true,
            duration: 0.5, // Adjust the duration here (in seconds)
            easeLinearity: 0.5, // Adjust the ease (0.0 to 1.0)
          });

          // Update the input field with the coordinates
          document.getElementById("coordinates").value =
            lat + ", " + lon;
        });
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    });

  // Add the pin location container to the map
  map.getContainer().appendChild(pinLocationContainer);

  var typingTimer; // Timer identifier
  var doneTypingInterval = 1000; // Time in milliseconds (5 seconds)

  // Function to update the map marker based on input location
  function updateMap(location) {
    // Parse the location string to extract latitude and longitude
    var coordinates = location.split(",").map(function (coord) {
      return parseFloat(coord.trim());
    });

    // Check if coordinates are valid
    if (
      coordinates.length === 2 &&
      !isNaN(coordinates[0]) &&
      !isNaN(coordinates[1])
    ) {
      var lat = coordinates[0];
      var lng = coordinates[1];

      // Remove existing marker if any
      if (typeof marker !== "undefined") {
        map.removeLayer(marker);
      }

      // Create a new marker at the specified coordinates and add it to the map
      marker = L.marker([lat, lng]).addTo(map);

      // Set the map view to the marker's location
      map.setView([lat, lng], 20); // Zoom level 12
    } else {
      // Invalid coordinates
      console.error("Invalid location format");
    }
  }

  // Event listener for location input field
  document
    .getElementById("coordinates")
    .addEventListener("input", function () {
      clearTimeout(typingTimer);
      var location = this.value;
      typingTimer = setTimeout(function () {
        // Update the map marker based on the input location after 5 seconds of inactivity
        updateMap(location);
      }, doneTypingInterval);
    });
});
