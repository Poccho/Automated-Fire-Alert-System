<?php
include ("db/connection.php");
include ("db/updateUser.php");

// Check if user ID is provided in the URL
if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];
    // Fetch user details from the database based on the provided user ID
    $sql = "SELECT * FROM users WHERE user_id = $userId";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Fetch user details
        $row = $result->fetch_assoc();

        // Store user details in variables
        $email = $row['email'];
        $username = $row['username'];
        $stationLocation = $row['station_location'];
        $barangayCode = $row['barangay_code'];
    } else {
        // User not found, redirect to an error page or display a message
        // For simplicity, redirecting to home page
        header("Location: index.php");
        exit();
    }
} else {
    // User ID not provided, redirect to an error page or display a message
    // For simplicity, redirecting to home page
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/editUser.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include "navBar.php"; ?>

    <section>
        <form id="userForm" class="form" method="POST">
            <p class="form-title">Edit User</p>
            <div class="pair">
                <div class="input">
                    <div class="input-container">
                        <input name="email" id="email" type="email" placeholder="Enter email" value="<?php echo $email; ?>" required>
                    </div>
                    <div class="input-container">
                        <input name="password" id="password" type="password" placeholder="Enter password" required>
                    </div>
                    <div class="input-container">
                        <input name="password2" id="password2" type="password" placeholder="Re-Enter password" required>
                    </div>
                    <div class="input-container">
                        <input name="username" id="username" type="text" placeholder="User Name" value="<?php echo $username; ?>" required>
                    </div>
                    <div class="input-container">
                        <input name="station_location" id="stationLocationInput" type="text" placeholder="Station Location" value="<?php echo $stationLocation; ?>" required>
                    </div>
                    <div class="input-container">
                        <select name="barangay_code" id="barangay_code" required>
                            <option value="" disabled>Select Barangay</option>
                            <?php
                            // Generate options for barangay codes
                            $barangays = array(
                                1 => 'Apopong',
                                2 => 'Baluan',
                                3 => 'Batomelong',
                                4 => 'Buayan',
                                5 => 'Bula',
                                6 => 'Calumpang',
                                7 => 'City Heights',
                                8 => 'Conel',
                                9 => 'Dadiangas East',
                                10 => 'Dadiangas North',
                                11 => 'Dadiangas South',
                                12 => 'Dadiangas West',
                                13 => 'Fatima',
                                14 => 'Katangawan',
                                15 => 'Labangal',
                                16 => 'Lagao',
                                17 => 'Ligaya',
                                18 => 'Mabuhay',
                                19 => 'Olympog',
                                20 => 'San Isidro',
                                21 => 'San Jose',
                                22 => 'Siguel',
                                23 => 'Sinawal',
                                24 => 'Tambler',
                                25 => 'Tinagacan',
                                26 => 'Upper Labay'
                            );
                            foreach ($barangays as $code => $barangay) {
                                echo "<option value='$code' " . ($code == $barangayCode ? 'selected' : '') . ">$barangay</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- Map container -->
                <div id="map" class="map-container"></div>
            </div>
            <div class="buttons">
            <button type="button" id="updateButton" class="submit">Update User</button>
            <button type="button" id="cancelButton" class="cancel">Back</button>
            </div>
        </form>
    </section>

    <script src="js/map.js"></script>

    <script>
         document.getElementById("updateButton").addEventListener("click", function () {
            Swal.fire({
                title: 'Confirm Update',
                text: 'Are you sure you want to update the user?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form
                    document.getElementById("userForm").submit();
                }
            });
        });

        document.getElementById("cancelButton").addEventListener("click", function () {
            Swal.fire({
                title: 'Cancel Editing',
                text: 'Are you sure you want to cancel editing? Any unsaved changes will be lost.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the previous page (or any other page)
                    window.location.href = 'userList.php';
                }
            });
        });
    </script>
</body>

</html>