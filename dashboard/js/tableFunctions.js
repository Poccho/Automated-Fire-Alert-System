let routes = []; // Store routes for each set of coordinates
let permanentHighlight = [];

// FUNCTIUON FOR PINNING LOCATIONS
function pinLocation(latitude, longitude) {
  let existingRoute = findRoute(latitude, longitude);
  if (existingRoute) {
    // Route already exists, just set the map view and highlight the table cells
    map.setView([latitude, longitude], 20);
  } else {
    // Route doesn't exist, create a new one
    let route = L.Routing.control({
      waypoints: [
        L.latLng(6.073838, 125.115167),
        L.latLng(latitude, longitude),
      ],
    }).addTo(map);
    let circle = L.circle([latitude, longitude], {
      color: "red",
      fillColor: "#f03",
      fillOpacity: 0.5,
      radius: 50,
    }).addTo(map);

    // Store the route with corresponding coordinates
    routes.push({ latitude, longitude, route, circle });

    // Set the map view
    map.setView([latitude, longitude], 20);
  }
}

function findRoute(latitude, longitude) {
  // Find the route with given coordinates
  return routes.find(
    (route) => route.latitude === latitude && route.longitude === longitude
  );
}

function remove(latitude, longitude) {
  let existingRoute = findRoute(latitude, longitude);

  if (existingRoute) {
    // Set the map view to the coordinates before showing the confirmation alert
    map.setView([latitude, longitude], 20);

    // setTimeout to ensure the map view change occurs before the alert
    setTimeout(function () {
      // Ask for confirmation after a slight delay to ensure map view change
      Swal.fire({
        title: "Confirm Deletion",
        text: "Are you sure you want to DELETE these coordinates?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
      }).then((result) => {
        if (result.isConfirmed) {
          let xhr = new XMLHttpRequest();
          xhr.open("POST", "delete_coordinates.php", true);
          xhr.setRequestHeader(
            "Content-type",
            "application/x-www-form-urlencoded"
          );
          xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
              if (xhr.status === 200) {
                console.log("Connected to delete_coordinates.php successfully");
                let response = xhr.responseText;
                if (response === "Deletion successful") {
                  console.log("Database deletion successful");
                  deleteRoute(latitude, longitude);
                } else {
                  console.error("Database deletion failed");
                  // Handle errors or display a message
                }
              } else {
                console.error("Connection to delete_coordinates.php failed");
                // Handle connection errors or display a message
              }
            }
          };
          xhr.send("latitude=" + latitude + "&longitude=" + longitude);
        }
      });
    }, 100); // Adjust the delay time if needed
  } else {
    Swal.fire({
      icon: "warning",
      title: "Coordinates not pinned",
      text: "Skipping deletion from the database",
      confirmButtonText: "OK",
    });
  }
}

function deleteRoute(latitude, longitude) {
  console.log("Deleting route: Lat - " + latitude + ", Long - " + longitude);

  let indexToDelete = -1;
  // Find the index of the route to delete based on coordinates
  for (let i = 0; i < routes.length; i++) {
    if (routes[i].latitude === latitude && routes[i].longitude === longitude) {
      indexToDelete = i;
      break;
    }
  }

  if (indexToDelete !== -1) {
    console.log("Removing route from map");
    // Remove the route and circle from the map
    map.removeControl(routes[indexToDelete].route);
    map.removeLayer(routes[indexToDelete].circle);
    routes.splice(indexToDelete, 1);
    map.setView([6.073838, 125.115167], 100);
    L.marker([6.073838, 125.115167], { icon: fireDepartment }).addTo(map);
  } else {
    console.error("Route not found in routes array");

    // Display a SweetAlert informing the user that the route is not found
    Swal.fire({
      icon: "error",
      title: "Route Not Found",
      text: "The Coordinates can only be DELETED if it is PINNED in the map",
    });
  }
}

function highlightIfPinned(latitude, longitude) {
  let existingRoute = findRoute(latitude, longitude);
  if (existingRoute) {
    // Coordinate is pinned, find and highlight the corresponding table cell
    let table = document.getElementById("dynamic-table");
    let cells = table.getElementsByTagName("td");

    for (let i = 0; i < cells.length; i++) {
      let coords = cells[i].innerText; // Assuming coordinates are in the cells
      if (coords.includes(latitude) && coords.includes(longitude)) {
        cells[i].classList.add("highlighted-cell");
      }
    }
  }
}

function removeRoute(latitude, longitude) {
  let existingRoute = findRoute(latitude, longitude);

  if (existingRoute) {
    // Set the map view to the coordinates before showing the confirmation alert
    map.setView([latitude, longitude], 20);

    // setTimeout to ensure the map view change occurs before the alert
    setTimeout(function () {
      // Ask for confirmation after a slight delay to ensure map view change
      let confirmRemove = confirm(
        "Are you sure you want to REMOVE this route?"
      );
      if (confirmRemove) {
        // Remove the route from the map only
        deleteRoute(latitude, longitude);
      }
    }, 100); // Adjust the delay time as needed
  } else {
    console.warn("Route not found, unable to remove from the map");
  }
}

window.onload = function () {
  // Call your function here
  highlightIfPinned();
};
