<?php
include ("db/connection.php");
include ("db/add_user.php");
include ("db/session.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/addUser.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include "navBar.php"; ?>

    <section>
        <form id="userForm" class="form" method="POST">
            <p class="form-title">Add a User</p>
            <div class="pair">
                <div class="input">
                    <div class="input-container">
                        <input name="email" id="email" type="email" placeholder="Enter email" required>
                    </div>
                    <div class="input-container">
                        <input name="password" id="password" type="password" placeholder="Enter password" required>
                    </div>
                    <div class="input-container">
                        <input name="password2" id="password2" type="password" placeholder="Re-Enter password" required>
                    </div>
                    <div class="input-container">
                        <input name="username" id="username" type="text" placeholder="User Name" required>
                    </div>
                    <div class="input-container">
                        <input name="station_location" id="stationLocationInput" type="text"
                            placeholder="Station Location" required>
                    </div>
                    <div class="input-container">
                        <select name="barangay_code" id="barangay_code" required>
                            <option value="" selected disabled>Select Barangay</option>
                            <option value="1">Apopong</option>
                            <option value="2">Baluan</option>
                            <option value="3">Batomelong</option>
                            <option value="4">Buayan</option>
                            <option value="5">Bula</option>
                            <option value="6">Calumpang</option>
                            <option value="7">City Heights</option>
                            <option value="8">Conel</option>
                            <option value="9">Dadiangas East</option>
                            <option value="10">Dadiangas North</option>
                            <option value="11">Dadiangas South</option>
                            <option value="12">Dadiangas West</option>
                            <option value="13">Fatima</option>
                            <option value="14">Katangawan</option>
                            <option value="15">Labangal</option>
                            <option value="16">Lagao</option>
                            <option value="17">Ligaya</option>
                            <option value="18">Mabuhay</option>
                            <option value="19">Olympog</option>
                            <option value="20">San Isidro</option>
                            <option value="21">San Jose</option>
                            <option value="22">Siguel</option>
                            <option value="23">Sinawal</option>
                            <option value="24">Tambler</option>
                            <option value="25">Tinagacan</option>
                            <option value="26">Upper Labay</option>
                        </select>
                    </div>

                </div>
                <!-- Map container -->
                <div id="map" class="map-container"></div>
            </div>
            <button type="submit" class="submit">Add User</button>
        </form>
    </section>

    <script src="js/map.js"></script>
</body>

</html>