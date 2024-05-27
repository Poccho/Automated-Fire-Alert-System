<?php
include("db/session.php");
?>

<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8" />
  <title>AFAS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./css/contact.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" />
  <!-- SweetAlert CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.css">

</head>

<body>
  <?php
  include "navBar.php";
  ?>

  <section>
    <div class="container">
      <div class="content">
        <div class="left-side">
          <div class="address details">
            <i class="fas fa-map-marker-alt"></i>
            <div class="topic">Address</div>
            <div class="text-one">IT/Physics Dept</div>
            <div class="text-two">MSU, General Santos City</div>
          </div>
          <div class="phone details">
            <i class="fas fa-phone-alt"></i>
            <div class="topic">Phone</div>
            <div class="text-one">+6394 9541 2538</div>
            <div class="text-two">+6399 1439 5168</div>
          </div>
          <div class="email details">
            <i class="fas fa-envelope"></i>
            <div class="topic">Email</div>
            <div class="text-one">rogeranthony.bairoy@msugensan.edu.ph</div>
            <div class="text-two">germar.bunda@msugensan.edu.ph</div>
            <div class="text-two">jeffmatthew.capinig@msugensan.edu.ph</div>
          </div>
        </div>
        <div class="right-side">
          <div class="topic-text">Send us a message</div>
          <p>
            If you have any questions or inquiries about us or our system,
            please feel free to contact us.
          </p>
          <form id="contactForm" action="db/send_mail.php" method="post">
            <div class="input-box">
              <input required="" type="text" id="name" name="name" placeholder="Enter your name" />
            </div>
            <div class="input-box">
              <input required="" type="email" id="email" name="email" placeholder="Enter your email" />
            </div>
            <div class="input-box message-box">
              <textarea id="message" name="message" rows="4" placeholder="Type your message"></textarea>
            </div>
            <div class="button">
              <button type="submit">Send Now</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- SweetAlert JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>

  <script>
  document.getElementById("contactForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent the form from submitting normally
    
    // Show SweetAlert loading overlay
    Swal.fire({
      title: 'Sending Email',
      html: 'Please wait...',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });
    
    var formData = new FormData(this);
    
    fetch("db/send_mail.php", {
      method: "POST",
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      // Hide SweetAlert loading overlay once the response is received
      Swal.close();
      
      if (data.success) {
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: 'Email has been sent successfully.',
          willClose: () => {
            clearFormFields(); // Clear form fields after closing SweetAlert
          }
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Something went wrong! Please try again later.'
        });
      }
    })
    .catch(error => {
      console.error('Error:', error);
      // Hide SweetAlert loading overlay in case of an error
      Swal.close();
      
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Something went wrong! Please try again later. Error: ' + error.message // Include error message
      });
    });
  });

  function clearFormFields() {
    // Clear input fields
    document.getElementById("name").value = "";
    document.getElementById("email").value = "";
    document.getElementById("message").value = "";
  }
</script>


</body>

</html>
