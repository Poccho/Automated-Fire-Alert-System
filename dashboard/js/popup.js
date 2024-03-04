function openPopup() {
  document.getElementById("overlay").style.display = "flex";
}

function closePopup() {
  Swal.fire({
      title: 'Are you sure?',
      text: "You will lose any unsaved changes!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, close it!'
  }).then((result) => {
      if (result.isConfirmed) {
          document.getElementById("overlay").style.display = "none";
      }
  });
}


document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('submitBtn').addEventListener('click', function(event) {
      event.preventDefault(); // Prevent the default form submission

      Swal.fire({
          title: 'Are you sure?',
          text: 'Do you want to submit this report?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, submit it!'
      }).then((result) => {
          if (result.isConfirmed) {
              // If the user confirms, submit the form directly
              document.getElementById('form').submit();
          }
      });
  });
});
