<?php

// Redirect if user_id is not set in session
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Include database connection
include "connection.php";

// Define an associative array mapping barangay names to numbers
$barangay_mapping = array(
    "Apopong" => 1,
    "Baluan" => 2,
    "Batomelong" => 3,
    "Buayan" => 4,
    "Bula" => 5,
    "Calumpang" => 6,
    "City Heights" => 7,
    "Conel" => 8,
    "Dadiangas East" => 9,
    "Dadiangas North" => 10,
    "Dadiangas South" => 11,
    "Dadiangas West" => 12,
    "Fatima" => 13,
    "Katangawan" => 14,
    "Labangal" => 15,
    "Lagao" => 16,
    "Ligaya" => 17,
    "Mabuhay" => 18,
    "Olympog" => 19,
    "San Isidro" => 20,
    "San Jose" => 21,
    "Siguel" => 22,
    "Sinawal" => 23,
    "Tambler" => 24,
    "Tinagacan" => 25,
    "Upper Labay" => 26
);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $barangay_name = $_POST['barangay'];
    // Convert barangay name to corresponding number
    $barangay_number = isset($barangay_mapping[$barangay_name]) ? $barangay_mapping[$barangay_name] : null;

    // Retrieve other form data
    $date = $_POST['date'];
    $alarm_time = $_POST['alarm_time'];
    $building_address = $_POST['building_address'];
    $coordinates = $_POST['coordinates'];
    // Split coordinates into longitude and latitude
// Split coordinates into longitude and latitude
list($latitude, $longitude) = explode(',', $coordinates);



    // SECTION I - INCIDENT
    $type_of_incident = $_POST['type_of_incident'];
    $occupants_were = $_POST['occupants_were'];
    $brief_history = $_POST['brief_history'];
    $num_injuries = $_POST['num_injuries'];
    $num_deaths = $_POST['num_deaths'];

    // SECTION II - FIRE
    $fire_origin_area = $_POST['fire_origin_area'];
    $ignition_equipment = $_POST['ignition_equipment'];
    $ignition_heat_form = $_POST['ignition_heat_form'];
    $ignited_material_type = $_POST['ignited_material_type'];
    $ignited_material_form = $_POST['ignited_material_form'];
    $extinguishment_method = $_POST['extinguishment_method'];
    $fire_origin_level = $_POST['fire_origin_level'];

    // SECTION III - STRUCTURE FIRE
    $flame_damage_extent = $_POST['flame_damage_extent'];
    $smoke_damage_extent = $_POST['smoke_damage_extent'];
    $detector_performance = $_POST['detector_performance'];
    $sprinkler_performance = $_POST['sprinkler_performance'];
    $most_smoke_material_type = $_POST['most_smoke_material_type'];
    $smoke_travel_avenue = $_POST['smoke_travel_avenue'];
    $origin_room = $_POST['origin_room'];
    $most_smoke_material_form = $_POST['most_smoke_material_form'];

    // SECTION IV - PREPARER OF THE REPORT
    $reporters_name = $_POST['reporters_name'];

    // SQL query to insert data into the report table
    $sql_report = "INSERT INTO report (barangay_no, date, alarm_time, building_address, type_of_incident)
                    VALUES ('$barangay_number', '$date', '$alarm_time', '$building_address', '$type_of_incident')";

    // Execute the report query
    if ($conn->query($sql_report) === TRUE) {
        // Get the last inserted ID
        $incident_no = $conn->insert_id;

        // SQL queries to insert data into respective tables
        $sql_section1 = "INSERT INTO section1 (incident_no, coordinates, occupants_were, brief_history, num_injuries, num_deaths)
                        VALUES ('$incident_no', '$coordinates','$occupants_were', '$brief_history', '$num_injuries', '$num_deaths')";

        $sql_section2 = "INSERT INTO section2 (incident_no, fire_origin_area, ignition_equipment, ignition_heat_form, ignited_material_type, ignited_material_form, extinguishment_method, fire_origin_level)
                        VALUES ('$incident_no', '$fire_origin_area', '$ignition_equipment', '$ignition_heat_form', '$ignited_material_type', '$ignited_material_form', '$extinguishment_method', '$fire_origin_level')";

        $sql_section3 = "INSERT INTO section3 (incident_no, flame_damage_extent, smoke_damage_extent, detector_performance, sprinkler_performance, most_smoke_material_type, smoke_travel_avenue, origin_room, most_smoke_material_form)
                        VALUES ('$incident_no', '$flame_damage_extent', '$smoke_damage_extent', '$detector_performance', '$sprinkler_performance', '$most_smoke_material_type', '$smoke_travel_avenue', '$origin_room', '$most_smoke_material_form')";

        $sql_section4 = "INSERT INTO section4 (incident_no, reporters_name, date)
                        VALUES ('$incident_no', '$reporters_name', CURDATE())";


        // Execute the section queries
        if ($conn->query($sql_section1) === TRUE && $conn->query($sql_section2) === TRUE && $conn->query($sql_section3) === TRUE && $conn->query($sql_section4) === TRUE) {


            // Delete row from alert table based on coordinates
            $sql_delete_alert = "DELETE FROM alert WHERE longitude = '$longitude' AND latitude = '$latitude'";
            $conn->query($sql_delete_alert);

            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                    window.onload = function() {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Record inserted successfully. Do you want to add another report?',
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'report.php';
                            } else {
                                window.close();
                            }
                        });
                    }
                  </script>";
            // Close database connection
            $conn->close();
            // Terminate script execution
            die();
        }  else {
            $errorMessages = [];
            if ($conn->query($sql_section1) !== TRUE) {
                $errorMessages[] = "Error in section 1: " . $conn->error;
            }
            if ($conn->query($sql_section2) !== TRUE) {
                $errorMessages[] = "Error in section 2: " . $conn->error;
            }
            if ($conn->query($sql_section3) !== TRUE) {
                $errorMessages[] = "Error in section 3: " . $conn->error;
            }
            if ($conn->query($sql_section4) !== TRUE) {
                $errorMessages[] = "Error in section 4: " . $conn->error;
            }
    
            $errorMessagesString = implode("<br>", $errorMessages);
            echo "<script>swal.fire({
                title: 'Error!',
                html: '{$errorMessagesString}',
                icon: 'error',
                confirmButtonText: 'OK'
            });</script>";
        }
    } else {
        $errorMessage = "Error in report section: " . $conn->error;
        echo "<script>swal.fire({
            title: 'Error!',
            html: '{$errorMessage}',
            icon: 'error',
            confirmButtonText: 'OK'
        });</script>";
    }

    // Close database connection
    $conn->close();
}
?>