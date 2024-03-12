function openPopup() {
  var div = document.getElementById("overlay");
  div.style.display = "flex"; // Show the div
}

function closePopup() {
  Swal.fire({
    title: "Are you sure?",
    text: "You will lose any unsaved changes!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, close it!",
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById("overlay").style.display = "none";
    }
  });
}

function closePopupDetails() {
      document.getElementById("overlay").style.display = "none";
}

document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("submitBtn")
    .addEventListener("click", function (event) {
      event.preventDefault(); // Prevent the default form submission

      Swal.fire({
        title: "Are you sure?",
        text: "Do you want to submit this report?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, submit it!",
      }).then((result) => {
        if (result.isConfirmed) {
          // If the user confirms, submit the form directly
          document.getElementById("form").submit();
        }
      });
    });
});

document.addEventListener("DOMContentLoaded", function() {
  var locationInput = document.getElementById('location');
  
  locationInput.addEventListener('input', function(event) {
    var inputValue = event.target.value;
    var sanitizedValue = inputValue.replace(/[^0-9.,]/g, ''); // Keep only numbers, commas, and periods
    event.target.value = sanitizedValue;
  });
});