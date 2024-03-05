function hideDivOnLoad() {
    var divToHide = document.getElementById("divToHide");
    divToHide.style.display = "none";
  }

  // Add an event listener for when the page finishes loading
  document.addEventListener("DOMContentLoaded", function(event) {
    // Call the function to hide the div
    hideDivOnLoad();
  });