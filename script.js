var map = L.map("map");
map.setView([6.073838, 125.115167], 150);

const links = document.querySelectorAll(".navbar > nav > ul > li");
const cards = document.querySelectorAll(".card");

//images for custom icons
const fireIcon = L.icon({
  iconUrl: "fire.png",
  iconSize: [60, 60],
});
const fireDepartment = L.icon({
  iconUrl: "firedepartment.png",
  iconSize: [40, 50],
});

// Add OpenStreetMap tile layer

L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  attribution: "© OpenStreetMap contributors",
}).addTo(map);

/*
var marker = L.marker([6.0694, 125.1262], { icon: fireIcon }).addTo(map);

var marker = L.marker([6.07449, 125.1146], { icon: fireIcon }).addTo(map);

var marker = L.marker([6.0678, 125.1195], { icon: fireIcon }).addTo(map);

var marker = L.marker([6.073838, 125.115167], { icon: fireDepartment }).addTo(
  map
);
*/

function changeLocation1() {
  lat = 6.0678;
  long = 125.1195;
  route1 = L.Routing.control({
    waypoints: [L.latLng(6.073838, 125.115167), L.latLng(lat, long)],
  }).addTo(map);
  map.setView([lat, long], 150);
  circle = L.circle([lat, long], {
    color: "red",
    fillColor: "#f03",
    fillOpacity: 0.5,
    radius: 50, // Adjust the radius as needed
  }).addTo(map);
}

function remove1() {
  map.removeControl(route1);
  map.removeControl(circle);
  map.setView([6.073838, 125.115167], 100);
  marker = L.marker([6.073838, 125.115167], { icon: fireDepartment }).addTo(
    map
  );
}

function changeLocation2() {
  lat = 6.07449;
  long = 125.1146;
  route2 = L.Routing.control({
    waypoints: [L.latLng(6.073838, 125.115167), L.latLng(lat, long)],
  }).addTo(map);
  map.setView([lat, long], 150);
  circle = L.circle([lat, long], {
    color: "red",
    fillColor: "#f03",
    fillOpacity: 0.5,
    radius: 50, // Adjust the radius as needed
  }).addTo(map);
}

function remove2() {
  map.removeControl(route2);
  map.removeControl(circle);
  map.setView([6.073838, 125.115167], 100);
}

function changeLocation3() {
  lat = 6.0694;
  long = 125.1262;
  route3 = L.Routing.control({
    waypoints: [L.latLng(6.073838, 125.115167), L.latLng(lat, long)],
  }).addTo(map);
  map.setView([lat, long], 150);
  circle = L.circle([lat, long], {
    color: "red",
    fillColor: "#f03",
    fillOpacity: 0.5,
    radius: 50, // Adjust the radius as needed
  }).addTo(map);
}

function remove3() {
  map.removeControl(route3);
  map.removeControl(circle);
  map.setView([6.073838, 125.115167], 100);
}

[...links].map((link, index) => {
  link.addEventListener("click", () => onLinkClick(link, index), false);
});
