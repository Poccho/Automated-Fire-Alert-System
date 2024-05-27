<?php
session_start();

// Redirect if user_id is not set in session
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch incident_no from URL parameter
$incident_no = isset($_GET['incident_no']) ? $_GET['incident_no'] : null;

// Fetch barangay_code from session if it exists
$barangay_code = isset($_SESSION['barangay_code']) ? $_SESSION['barangay_code'] : null;
// to prevent the ui from being fcked up, i dont know why
echo '<p style="position: absolute; z-index: -9999; top: -9999px; left: -9999px;">' . htmlspecialchars($incident_no) . '</p>';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSA FIRE INCIDENT REPORT</title>
    <style>
        .reportForm {
            max-width: 100%;
            max-height: 87%;
            overflow-y: scroll;
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
            /* Set width to 100% */
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
        <form id="reportForm" class="form" action="db/generatePDF.php" method="post">

            <?php
            include "db/connection.php";

            // Define SQL query to select data from multiple tables
            $sql = "SELECT 
                    r.*, 
                    s1.*, 
                    s2.*, 
                    s3.*, 
                    s4.*,
                    b.barangay_name as barangay
                    FROM 
                        report AS r
                    LEFT JOIN 
                        section1 AS s1 ON r.incident_no = s1.incident_no
                    LEFT JOIN 
                        section2 AS s2 ON r.incident_no = s2.incident_no
                    LEFT JOIN 
                        section3 AS s3 ON r.incident_no = s3.incident_no
                    LEFT JOIN 
                        section4 AS s4 ON r.incident_no = s4.incident_no
                    LEFT JOIN 
                        barangay AS b ON r.barangay_no = b.barangay_code
                    WHERE 
                        r.incident_no = '$incident_no';";

            // Execute the SQL query
            $result = $conn->query($sql);

            // Check if the query was successful
            if ($result) {
                // Check if there are any rows returned
                if ($result->num_rows > 0) {
                    // Fetch data from each row
                    while ($row = $result->fetch_assoc()) {
                        // Access data from each table using column names
                        echo '
            <section name="section1">
                <p class="title">SECTION I - INCIDENT</p>
                <p class="message">Make sure all the details provided are correct and accurate</p>
                <div class="flex">
                <input id="incident_no" name="incident_no" class="input" value="' . $incident_no . '" required style="display: none;" />

                    <label>
                        <input id="barangay" name="barangay" class="input" value="' . $row['barangay'] . '" required />
                        <span>BARANGAY</span>
                    </label>
                    <label>
                        <input id="date" name="date" placeholder="" type="text" class="input" onfocus="(this.type=\'date\')" onblur="(this.type=\'text\')" value="' . $row['date'] . '" required />
                        <span>DATE</span>
                    </label>
                </div>
                <div class="flex">
                    <label>
                        <input id="time" name="alarm_time" placeholder="" type="text" class="input" onfocus="(this.type=\'time\')" onblur="(this.type=\'text\')" value="' . $row['alarm_time'] . '" required />
                        <span>ALARM TIME</span>
                    </label>
                    <label>
                        <input name="building_address" value="' . $row['building_address'] . '" required="" placeholder="" type="text" class="input">
                        <span>BUILDING NAME</span>
                    </label>
                </div>
                <div class="flex">
                    <label>
                        <input name="coordinates" value="' . $row['coordinates'] . '" required="" placeholder="" type="text" class="input">
                        <span>COORDINATES</span>
                    </label>
                    <label>
                        <input name="type_of_incident" value="' . $row['type_of_incident'] . '" required="" placeholder="" type="text" class="input">
                        <span>TYPE OF INCIDENT</span>
                    </label>
                </div>
                <div class="flex">
                    <label>
                        <input id="occupants_were" name="occupants_were" class="input" value="' . $row['occupants_were'] . '" required>
                        <span>OCCUPANTS WERE</span>
                    </label>
                    <label>
                        <input name="brief_history" value="' . $row['brief_history'] . '" required="" placeholder="" type="text" class="input">
                        <span>BRIEF HISTORY OF THE INCIDENT</span>
                    </label>
                </div>
                <div class="flex">
                    <label>
                        <input id="num_injuries" name="num_injuries" type="text" class="input" value="' . $row['num_injuries'] . '" required>
                        <span>PERSONNEL NO. OF INJURIES:</span>
                    </label>
                    <label>
                        <input type="text" id="num_deaths" name="num_deaths" class="input" value="' . $row['num_deaths'] . '" required>
                        <span>PERSONNEL NO. OF DEATHS:</span>
                    </label>
                </div>
            </section>

            <section name="section2">
                <p class="title">SECTION II - FIRE</p>
                <p class="message">Make sure all the details provided are correct and accurate</p>
                <div class="flex">
                    <label>
                        <input name="fire_origin_area" value="' . $row['fire_origin_area'] . '" required="" placeholder="" type="text" class="input">
                        <span>AREA OF FIRE ORIGIN:</span>
                    </label>
                    <label>
                        <input name="ignition_equipment" value="' . $row['ignition_equipment'] . '" required="" placeholder="" type="text" class="input">
                        <span>EQUIPMENT INVOLVED IN IGNITION:</span>
                    </label>
                </div>
                <div class="flex">
                    <label>
                        <input name="ignition_heat_form" value="' . $row['ignition_heat_form'] . '" required="" placeholder="" type="text" class="input">
                        <span>FORM OF HEAT OF IGNITION:</span>
                    </label>
                    <label>
                        <input name="ignited_material_type" value="' . $row['ignited_material_type'] . '" required="" placeholder="" type="text" class="input">
                        <span>TYPE OF MATERIAL IGNITED:</span>
                    </label>
                </div>
                <div class="flex">
                    <label>
                        <input name="ignited_material_form" value="' . $row['ignited_material_form'] . '" required="" placeholder="" type="text" class="input">
                        <span>FORM OF MATERIAL IGNITED:</span>
                    </label>
                    <label>
                        <input name="extinguishment_method" value="' . $row['extinguishment_method'] . '" required="" placeholder="" type="text" class="input">
                        <span>METHOD OF EXTINGUISHMENT:</span>
                    </label>
                </div>
                <div class="flex">
                    <label>
                        <input name="fire_origin_level" value="' . $row['fire_origin_level'] . '" required="" placeholder="" type="text" class="input">
                        <span>LEVEL OF FIRE ORIGIN:</span>
                    </label>
                </div>
            </section>

            <section name="section3">
                <p class="title">SECTION III - STRUCTURE FIRE </p>
                <p class="message">Make sure all the details provided are correct and accurate</p>
                <div class="flex">
                    <label>
                        <input name="flame_damage_extent" value="' . $row['flame_damage_extent'] . '" required="" placeholder="" type="text" class="input">
                        <span>EXTENT OF FLAME DAMAGE:</span>
                    </label>
                                <label>
                                    <input name="smoke_damage_extent" value="' . $row['smoke_damage_extent'] . '" required="" placeholder="" type="text" class="input">
                                    <span>EXTENT OF SMOKE DAMAGE:</span>
                                </label>
                            </div>
                            <div class="flex">
                                <label>
                                    <input name="detector_performance" value="' . $row['detector_performance'] . '" required="" placeholder="" type="text" class="input">
                                    <span>DETECTOR PERFORMANCE:</span>
                                </label>
                                <label>
                                    <input name="sprinkler_performance" value="' . $row['sprinkler_performance'] . '" required="" placeholder="" type="text" class="input">
                                    <span>SPRINKLER PERFORMANCE:</span>
                                </label>
                            </div>
                            <div class="flex">
                                <label>
                                    <input name="most_smoke_material_type" value="' . $row['most_smoke_material_type'] . '" required="" placeholder="" type="text" class="input">
                                    <span>TYPE OF MATERIAL GENERATING MOST SMOKE:</span>
                                </label>
                                <label>
                                    <input name="smoke_travel_avenue" value="' . $row['smoke_travel_avenue'] . '" required="" placeholder="" type="text" class="input">
                                    <span>AVENUE OF SMOKE TRAVEL:</span>
                                </label>
                            </div>
                            <div class="flex">
                                <label>
                                    <input name="origin_room" value="' . $row['origin_room'] . '" required="" placeholder="" type="text" class="input">
                                    <span>ROOM OF ORIGIN:</span>
                                </label>
                                <label>
                                    <input name="most_smoke_material_form" value="' . $row['most_smoke_material_form'] . '" required="" placeholder="" type="text" class="input">
                                    <span>FORM OF MATERIAL GENERATING MOST SMOKE:</span>
                                </label>
                            </div>
                        </section>
            
                        <section name="section4">
                            <p class="title">SECTION IV - PREPARER OF THE REPORT</p>
                            <p class="message">Make sure all the details provided are correct and accurate</p>
                            <div CLASS="flex">
                                <label>
                                    <input name="reporters_name" value="' . $row['reporters_name'] . '" required="" placeholder="" type="text" class="input">
                                    <span>REPORTERS NAME</span>
                                </label>
                                <label>
                                    <input name="date" value="' . $row['date'] . '" required="" placeholder="" type="text" class="input">
                                    <span>REPORT DATE</span>
                                </label>
                            </div>
                        </section>';
                    }
                }
            } else {
                echo "Error executing query: " . $conn->error;
            }

            // Close the database connection
            $conn->close();
            ?>




            <button type="" class="submit">Download</button>
            <button type="button" class="cancel" onclick="closeCurrentTab()">Close</button>

        </form>
    </div>

    <script>
        function closeCurrentTab() {
            window.close();
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Get all input fields
            const inputFields = document.querySelectorAll('input');

            // Add event listeners to prevent interaction
            inputFields.forEach(function (input) {
                input.addEventListener('mousedown', function (event) {
                    event.preventDefault(); // Prevent default behavior
                });

                input.addEventListener('keydown', function (event) {
                    event.preventDefault(); // Prevent default behavior
                });

                input.addEventListener('touchstart', function (event) {
                    event.preventDefault(); // Prevent default behavior
                });
            });
        });



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
    <script>
        // Handle cancel button click
        document.querySelector('.cancel').addEventListener('click', function () {
            // Redirect or perform any other action
            window.location.href = 'index.php';
        });
    </script>
</body>

</html>