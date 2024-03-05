

function confirmLogout() {
  return Swal.fire({
    title: "Log Out?",
    text: "Please confirm if you want to log out.",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Yes, log out",
    confirmButtonColor: "#f44336", // Red color for the confirmation button
    cancelButtonText: "No, cancel",
    reverseButtons: true,
  }).then((result) => {
    if (result.isConfirmed) {
      // If the user confirms, perform logout action here
      // For example:
      window.location.href = "../logout.php";
    }
  });
}
