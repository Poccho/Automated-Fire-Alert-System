<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <title>AFAS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./css/style.css" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css"
    />
  </head>
  <body>
    <nav>
      <input type="checkbox" id="check" />
      <label for="check" class="checkbtn">
        <i class="fas fa-bars"></i>
      </label>
      <label class="logo"
        ><i class="fa-solid fa-house-fire fa-xs"></i> AFAS</label
      >
      <ul>
        <li><a href="home.php">Dashboard</a></li>
        <li><a href="statistics.php">Statistics</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a class="active" href="about.php">About</a></li>
        <li><a href=logout.php> Sign Out </a></li>
      </ul>
    </nav>
    <section></section>
  </body>
</html>
