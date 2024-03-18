<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: ../index.php");
  exit();
}
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
          <form id="contactForm">
            <div class="input-box">
              <input required="" type="text" id="name" placeholder="Enter your name" />
            </div>
            <div class="input-box">
              <input required="" type="email" id="email" placeholder="Enter your email" />
            </div>
            <div class="input-box message-box">
              <textarea id="message" rows="4" placeholder="Type your message"></textarea>
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
    document.getElementById('contactForm').addEventListener('submit', function(event) {
      event.preventDefault();
      var name = document.getElementById('name').value;
      var email = document.getElementById('email').value;
      var message = document.getElementById('message').value;

      // Send the data to your PHP script using AJAX
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'db/send_mail.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
          if (xhr.status == 200) {
            // Successful request
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: 'Email has been sent successfully!'
            });
          } else {
            // Request failed
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'There was an error sending the email. Please try again later.'
            });
          }
        }
      };
      xhr.send('name=' + encodeURIComponent(name) + '&email=' + encodeURIComponent(email) + '&message=' + encodeURIComponent(message));
    });
  </script>
</body>

</html>
