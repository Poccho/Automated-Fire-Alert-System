<?php
include("dashboard\db\login.php");
?>
<html>

<head>
  <link rel="stylesheet" href="./css/login.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" />
  <title>AFAS Log In</title>
</head>

<body>
  <div class="container" id="container">
    <div class="form-container log-in-container">
      <form action="#" method="POST">
        <h1>Login</h1>
        <div></div>
        <input id="username" name="username" type="text" placeholder="username" required />
        <input id="password" name="password" type="password" placeholder="password" required />
        <button class="button">Log In</button>
      </form>
    </div>
    <div class="overlay-container">
      <div class="overlay">
        <div class="overlay-panel overlay-right">
          <img src="./dashboard/misc/logo.png" alt="Company Logo" />
          <h1>Automated Fire Alarm System</h1>
          <p>
            A System Disgned to Aid Our Local Firefighters in their battle
            against fire.
          </p>
        </div>
      </div>
    </div>
  </div>
</body>

</html>