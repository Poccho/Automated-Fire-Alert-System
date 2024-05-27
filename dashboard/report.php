<?php
session_start();

// Redirect if user_id is not set in session
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch barangay_code from session if it exists
$barangay_code = isset($_SESSION['barangay_code']) ? $_SESSION['barangay_code'] : null;

// Define an associative array mapping barangay codes to names
$barangay_names = array(
    1 => "Apopong",
    2 => "Baluan",
    3 => "Batomelong",
    4 => "Buayan",
    5 => "Bula",
    6 => "Calumpang",
    7 => "City Heights",
    8 => "Conel",
    9 => "Dadiangas East",
    10 => "Dadiangas North",
    11 => "Dadiangas South",
    12 => "Dadiangas West",
    13 => "Fatima",
    14 => "Katangawan",
    15 => "Labangal",
    16 => "Lagao",
    17 => "Ligaya",
    18 => "Mabuhay",
    19 => "Olympog",
    20 => "San Isidro",
    21 => "San Jose",
    22 => "Siguel",
    23 => "Sinawal",
    24 => "Tambler",
    25 => "Tinagacan",
    26 => "Upper Labay"
);

// Convert barangay code to name
$barangay_name = isset($barangay_names[$barangay_code]) ? $barangay_names[$barangay_code] : "Unknown";

// Log the barangay name in the console
echo "<script>console.log('Barangay Name:', '" . $barangay_name . "');</script>";

// Retrieve latitude, longitude, and alert time from URL parameters
$latitude = isset($_GET['latitude']) ? $_GET['latitude'] : '';
$longitude = isset($_GET['longitude']) ? $_GET['longitude'] : '';
$alert_time = isset($_GET['alert_time']) ? $_GET['alert_time'] : '';
$building = isset($_GET['building']) ? $_GET['building'] : '';

// JavaScript code to log latitude, longitude, and alert time to console
echo '<script>';
echo 'console.log("Latitude:", ' . json_encode($latitude) . ');';
echo 'console.log("Longitude:", ' . json_encode($longitude) . ');';
echo 'console.log("Alert Time:", ' . json_encode($alert_time) . ');';
echo 'console.log("Building:", ' . json_encode($building) . ');';
echo '</script>';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSA FIRE INCIDENT REPORT</title>
    <link rel="stylesheet" href="./css/style.css" />
    <style>
        .reportForm{
            max-width: 100%;
            max-height: 87%;
            overflow-y: scroll;
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 3500px;
            background-color: #fff;
            padding: 20px;
            position: relative;
        }

        .title {
            font-size: 28px;
            color: orangered;
            font-weight: 600;
            letter-spacing: -1px;
            position: relative;
            display: flex;
            align-items: center;
            padding-left: 30px;
        }

        .title.green {
            color: green;
        }

        .title::before,
        .title::after {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            border-radius: 50%;
            left: 0px;
            background-color: red;
        }

        .title::before {
            width: 18px;
            height: 18px;
            background-color: orangered;
        }

        .title::after {
            width: 18px;
            height: 18px;
            animation: pulse 1s linear infinite;
        }

        .title.green::before,
        .title.green::after {
            background-color: green;
            /* Change background color to green */
        }

        .title.green::after {
            animation: pulse 1s linear infinite;
            /* Remove the animation for the green title */
        }


        .message,
        .signin {
            color: rgba(88, 87, 87, 0.822);
            font-size: 14px;
        }

        .signin {
            text-align: center;
        }

        .signin a {
            color: orangered;
        }

        .signin a:hover {
            text-decoration: underline orangered;
        }

        .flex {
            display: flex;
            width: 100%;
            gap: 6px;
            margin-top: 5px;
        }

        .form label {
            position: relative;
        }

        .form label .input {
            width: 650px;
            padding: 10px 10px 20px 10px;
            outline: 0;
            border: 1px solid rgba(105, 105, 105, 0.397);
            border-radius: 10px;
        }

        .form label .input+span {
            position: absolute;
            left: 10px;
            top: 15px;
            color: grey;
            font-size: 0.9em;
            cursor: text;
            transition: 0.3s ease;
        }

        .form label .input:placeholder-shown+span {
            top: 15px;
            font-size: 0.9em;
        }

        .form label .input:focus+span,
        .form label .input:valid+span {
            top: 30px;
            font-size: 0.7em;
            font-weight: 600;
        }

        .form label .input:valid+span {
            color: green;
        }

        .submit {
            border: none;
            outline: none;
            background-color: orange;
            padding: 10px;
            border-radius: 10px;
            color: #fff;
            font-size: 16px;
            transform: .3s ease;
        }

        .submit:hover {
            background-color: green;
        }

        .cancel {
            border: none;
            outline: none;
            background-color: red;
            padding: 10px;
            border-radius: 10px;
            color: #fff;
            font-size: 16px;
            transform: .3s ease;
        }

        .cancel:hover {
            background-color: rgb(56, 90, 194);
        }

        @keyframes pulse {
            from {
                transform: scale(0.9);
                opacity: 1;
            }

            to {
                transform: scale(1.8);
                opacity: 0;
            }
        }
    </style>
</head>

<body>
<?php include "navBar.php"; ?>

<div class="reportForm">
    <form class="form" action="db/uploadReport.php" method="post">
        <section name="section1">
            <p class="title">SECTION I - INCIDENT</p>
            <p class="message">Make sure all the details provided are correct and accurate</p>
            <div class="flex">
                <label>
                    <select id="barangay" name="barangay" class="input" required>
                        <option value=""></option>
                        <option value="Apopong" <?php echo $barangay_name == "Apopong" ? "selected" : ""; ?>>Apopong
                        </option>
                        <option value="Baluan" <?php echo $barangay_name == "Baluan" ? "selected" : ""; ?>>Baluan</option>
                        <option value="Batomelong" <?php echo $barangay_name == "Batomelong" ? "selected" : ""; ?>>
                            Batomelong</option>
                        <option value="Buayan" <?php echo $barangay_name == "Buayan" ? "selected" : ""; ?>>Buayan</option>
                        <option value="Bula" <?php echo $barangay_name == "Bula" ? "selected" : ""; ?>>Bula</option>
                        <option value="Calumpang" <?php echo $barangay_name == "Calumpang" ? "selected" : ""; ?>>Calumpang
                        </option>
                        <option value="City Heights" <?php echo $barangay_name == "City Heights" ? "selected" : ""; ?>>
                            City Heights</option>
                        <option value="Conel" <?php echo $barangay_name == "Conel" ? "selected" : ""; ?>>Conel</option>
                        <option value="Dadiangas East" <?php echo $barangay_name == "Dadiangas East" ? "selected" : ""; ?>>Dadiangas East</option>
                        <option value="Dadiangas North" <?php echo $barangay_name == "Dadiangas North" ? "selected" : ""; ?>>Dadiangas North</option>
                        <option value="Dadiangas South" <?php echo $barangay_name == "Dadiangas South" ? "selected" : ""; ?>>Dadiangas South</option>
                        <option value="Dadiangas West" <?php echo $barangay_name == "Dadiangas West" ? "selected" : ""; ?>>Dadiangas West</option>
                        <option value="Fatima" <?php echo $barangay_name == "Fatima" ? "selected" : ""; ?>>Fatima</option>
                        <option value="Katangawan" <?php echo $barangay_name == "Katangawan" ? "selected" : ""; ?>>
                            Katangawan</option>
                        <option value="Labangal" <?php echo $barangay_name == "Labangal" ? "selected" : ""; ?>>Labangal
                        </option>
                        <option value="Lagao" <?php echo $barangay_name == "Lagao" ? "selected" : ""; ?>>Lagao</option>
                        <option value="Ligaya" <?php echo $barangay_name == "Ligaya" ? "selected" : ""; ?>>Ligaya</option>
                        <option value="Mabuhay" <?php echo $barangay_name == "Mabuhay" ? "selected" : ""; ?>>Mabuhay
                        </option>
                        <option value="Olympog" <?php echo $barangay_name == "Olympog" ? "selected" : ""; ?>>Olympog
                        </option>
                        <option value="San Isidro" <?php echo $barangay_name == "San Isidro" ? "selected" : ""; ?>>San
                            Isidro</option>
                        <option value="San Jose" <?php echo $barangay_name == "San Jose" ? "selected" : ""; ?>>San Jose
                        </option>
                        <option value="Siguel" <?php echo $barangay_name == "Siguel" ? "selected" : ""; ?>>Siguel</option>
                        <option value="Sinawal" <?php echo $barangay_name == "Sinawal" ? "selected" : ""; ?>>Sinawal
                        </option>
                        <option value="Tambler" <?php echo $barangay_name == "Tambler" ? "selected" : ""; ?>>Tambler
                        </option>
                        <option value="Tinagacan" <?php echo $barangay_name == "Tinagacan" ? "selected" : ""; ?>>Tinagacan
                        </option>
                        <option value="Upper Labay" <?php echo $barangay_name == "Upper Labay" ? "selected" : ""; ?>>Upper
                            Labay</option>
                    </select>
                    <span>BARANGAY</span>
                </label>

                <label>
                    <input id="date" name="date" placeholder="" type="text" class="input" onfocus="(this.type='date')"
                        onblur="(this.type='text')" required />
                    <span>DATE</span>
                </label>
            </div>
            <div class="flex">

                <label>
                    <input id="time" name="alarm_time" placeholder="" type="text" class="input"
                        onfocus="(this.type='time')" onblur="(this.type='text')"
                        value="<?php echo htmlspecialchars($alert_time); ?>" required />
                    <span>ALARM TIME</span>
                </label>


                <label>
                    <input name="building_address" required="" placeholder="" type="text" class="input"
                        value="<?php echo $building; ?>">
                    <span>BUILDING NAME</span>
                </label>
            </div>

            <div class="flex">

                <label>
                    <input name="coordinates" required="" placeholder="" type="text" class="input"
                        value="<?php echo $latitude . ',' . $longitude; ?>">
                    <span>COORDINAETS</span>
                </label>
                <label>
                    <input name="type_of_incident" required="" placeholder="" type="text" class="input">
                    <span>TYPE OF Incident</span>
                </label>
            </div>

            <div class="flex">
                <label>
                    <select id="occupants_were" name="occupants_were" class="input" required>
                        <option value=""></option>
                        <option value="not_evacuated">NOT EVACUATED</option>
                        <option value="evacuated">EVACUATED</option>
                        <option value="relocated">RELOCATED</option>
                        <option value="both_b_and_c">BOTH B AND C</option>
                    </select>
                    <span>OCCUPANTS WERE</span>
                </label>
                <label>
                    <input name="brief_history" required="" placeholder="" type="text" class="input">
                    <span>BRIEF HISTORY OF THE INCIDENT</span>
                </label>
            </div>

            <div class="flex">
                <label>
                    <input id="num_injuries" name="num_injuries" type="text" class="input" required>
                    <span>PERSONNEL NO. OF INJURIES:</span>
                </label>
                <label>
                    <input type="text" id="num_deaths" name="num_deaths" class="input" required>
                    <span>PERSONNEL NO. OF DEATHS:</span>
                </label>
            </div>
        </section>

        <section name="section2">
            <p class="title">SECTION II - FIRE</p>
            <p class="message">Make sure all the details provided are correct and accurate</p>
            <div class="flex">
                <label>
                    <input name="fire_origin_area" required="" placeholder="" type="text" class="input">
                    <span>AREA OF FIRE ORIGIN:</span>
                </label>
                <label>
                    <input name="ignition_equipment" required="" placeholder="" type="text" class="input">
                    <span>EQUIPMENT INVOLVED IN IGNITION:</span>
                </label>
            </div>
            <div class="flex">
                <label>
                    <input name="ignition_heat_form" required="" placeholder="" type="text" class="input">
                    <span>FORM OF HEAT OF IGNITION:</span>
                </label>
                <label>
                    <input name="ignited_material_type" required="" placeholder="" type="text" class="input">
                    <span>TYPE OF MATERIAL IGNITED:</span>
                </label>
            </div>
            <div class="flex">
                <label>
                    <input name="ignited_material_form" required="" placeholder="" type="text" class="input">
                    <span>FORM OF MATERIAL IGNITED:</span>
                </label>
                <label>
                    <input name="extinguishment_method" required="" placeholder="" type="text" class="input">
                    <span>METHOD OF EXTINGUISHMENT:</span>
                </label>
            </div>
            <div class="flex">
                <label>
                    <input name="fire_origin_level" required="" placeholder="" type="text" class="input">
                    <span>LEVEL OF FIRE ORIGIN:</span>
                </label>
            </div>
        </section>

        <section name="section3">
            <p class="title">SECTION III - STRUCTURE FIRE </p>
            <p class="message">Make sure all the details provided are correct and accurate</p>
            <div class="flex">
                <label>
                    <input name="flame_damage_extent" required="" placeholder="" type="text" class="input">
                    <span>EXTENT OF FLAME DAMAGE:</span>
                </label>
                <label>
                    <input name="smoke_damage_extent" required="" placeholder="" type="text" class="input">
                    <span>EXTENT OF SMOKE DAMAGE:</span>
                </label>
            </div>
            <div class="flex">
                <label>
                    <input name="detector_performance" required="" placeholder="" type="text" class="input">
                    <span>DETECTOR PERFORMANCE:</span>
                </label>
                <label>
                    <input name="sprinkler_performance" required="" placeholder="" type="text" class="input">
                    <span>SPRINKLER PERFORMANCE:</span>
                </label>
            </div>
            <div class="flex">
                <label>
                    <input name="most_smoke_material_type" required="" placeholder="" type="text" class="input">
                    <span>TYPE OF MATERIAL GENERATING MOST SMOKE:</span>
                </label>
                <label>
                    <input name="smoke_travel_avenue" required="" placeholder="" type="text" class="input">
                    <span>AVENUE OF SMOKE TRAVEL:</span>
                </label>
            </div>
            <div class="flex">
                <label>
                    <input name="origin_room" required="" placeholder="" type="text" class="input">
                    <span>ROOM OF ORIGIN:</span>
                </label>
                <label>
                    <input name="most_smoke_material_form" required="" placeholder="" type="text" class="input">
                    <span>FORM OF MATERIAL GENERATING MOST SMOKE:</span>
                </label>
            </div>
        </section>

        <section name="section4">
            <p class="title">SECTION IV - PREPARER OF THE REPORT</p>
            <p class="message">Make sure all the details provided are correct and accurate</p>
            <div CLASS="flex">
                <label>
                    <input name="reporters_name" required="" placeholder="" type="text" class="input">
                    <span>REPORTERS NAME</span>
                </label>
            </div>
        </section>

        <button type="submit" class="submit">Submit</button>
        <button type="button" class="cancel">Cancel</button>

    </form>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to check if all input fields inside a section are filled
            function checkSection(sectionName) {
                const section = document.getElementsByName(sectionName)[0];
                const inputs = section.querySelectorAll('.input');
                let allFilled = true;
                inputs.forEach(input => {
                    if (input.value.trim() === '') {
                        allFilled = false;
                    }
                });
                // Get the title element for the section
                const title = section.querySelector('.title');
                // If all input fields are filled, add 'valid' class to the title, else remove it
                if (allFilled) {
                    title.classList.add('green'); // Adding 'green' class to make it green
                } else {
                    title.classList.remove('green'); // Remove 'green' class if not all fields are filled
                }
            }

            // Function to check all sections regularly
            function checkAllSections() {
                document.querySelectorAll('section').forEach(section => {
                    checkSection(section.getAttribute('name'));
                });
            }

            // Call the checkAllSections function initially and then every 2 seconds
            checkAllSections(); // Initial check
            setInterval(checkAllSections, 2000); // Regular check every 2 seconds
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Intercept form submission
            document.getElementById('reportForm').addEventListener('submit', function (event) {
                // Prevent default form submission
                event.preventDefault();

                // Show confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to submit the report?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, submit it!'
                }).then((result) => {
                    // If user confirms, proceed with form submission
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });

            // Handle cancel button click
            document.querySelector('.cancel').addEventListener('click', function () {
                // Redirect or perform any other action
                window.location.href = 'index.php';
            });
        });
    </script>
</body>

</html>