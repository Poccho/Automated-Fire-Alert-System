<?php
session_start();

require_once ('../../vendor/tecnickcom/tcpdf/tcpdf.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $incident_no = $_POST['incident_no'];

    // Fetch data from the form
    $data = [
        'barangay' => $_POST['barangay'],
        'date' => $_POST['date'],
        'alarm_time' => $_POST['alarm_time'],
        'building_address' => $_POST['building_address'],
        'coordinates' => $_POST['coordinates'],
        'type_of_incident' => $_POST['type_of_incident'],
        'occupants_were' => $_POST['occupants_were'],
        'brief_history' => $_POST['brief_history'],
        'num_injuries' => $_POST['num_injuries'],
        'num_deaths' => $_POST['num_deaths'],
        'fire_origin_area' => $_POST['fire_origin_area'],
        'ignition_equipment' => $_POST['ignition_equipment'],
        'ignition_heat_form' => $_POST['ignition_heat_form'],
        'ignited_material_type' => $_POST['ignited_material_type'],
        'ignited_material_form' => $_POST['ignited_material_form'],
        'extinguishment_method' => $_POST['extinguishment_method'],
        'fire_origin_level' => $_POST['fire_origin_level'],
        'flame_damage_extent' => $_POST['flame_damage_extent'],
        'smoke_damage_extent' => $_POST['smoke_damage_extent'],
        'detector_performance' => $_POST['detector_performance'],
        'sprinkler_performance' => $_POST['sprinkler_performance'],
        'most_smoke_material_type' => $_POST['most_smoke_material_type'],
        'smoke_travel_avenue' => $_POST['smoke_travel_avenue'],
        'origin_room' => $_POST['origin_room'],
        'most_smoke_material_form' => $_POST['most_smoke_material_form'],
        'reporters_name' => $_POST['reporters_name'],
    ];

    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('ADMIN');
    $pdf->SetTitle('GSA Fire Incident Report');
    $pdf->SetSubject('Incident Report');
    $pdf->SetKeywords('TCPDF, PDF, report, incident');


    // set margins
    $pdf->SetMargins(10, PDF_MARGIN_TOP, 10);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // Add a page
    $pdf->AddPage();

    // Set content
    $html = '<style>
    .flex {
        display: flex;
        width: 100%;
        gap: 6px;
        margin-top: 5px;
    }

    body {
        font-family: Arial, sans-serif;
        color: #333;
    }

    h1 {
        color: black;
        font-size: 24px;
    }

    h2 {
        color: #555;
        font-size: 20px;
        margin-top: 30px;
    }

    p {
        margin-bottom: 10px;
    }

    strong {
        font-weight: bold;
    }

    .section {
        padding: 15px;
        border-radius: 5px;
    }

    .section-title {
        color: black;
        font-size: 18px;
    }
</style>
<h1>Fire Incident Report</h1>

<div class="section">
    <h2 class="section-title">Section I - Incident</h2>
    <h2 class="section-title">_____________________________________________________</h2>
    <p><strong>Barangay:</strong> ' . $data['barangay'] . '</p>
    <p><strong>Date:</strong> ' . $data['date'] . '</p>
    
    <p><strong>Alarm Time:</strong> ' . $data['alarm_time'] . '</p>
    <p><strong>Building Address:</strong> ' . $data['building_address'] . '</p>
    
    <p><strong>Coordinates:</strong> ' . $data['coordinates'] . '</p>
    <p><strong>Type of Incident:</strong> ' . $data['type_of_incident'] . '</p>
    
    <p><strong>Occupants Were:</strong> ' . $data['occupants_were'] . '</p>
    <p><strong>Brief History of the Incident:</strong> ' . $data['brief_history'] . '</p>
    
    <p><strong>Number of Injuries:</strong> ' . $data['num_injuries'] . '</p>
    <p><strong>Number of Deaths:</strong> ' . $data['num_deaths'] . '</p>
    
    <h2 class="section-title">_____________________________________________________</h2>

</div>

<div class="section">
    <h2 class="section-title">Section II - Fire</h2>
    <h2 class="section-title">_____________________________________________________</h2>
    <p><strong>Area of Fire Origin:</strong> ' . $data['fire_origin_area'] . '</p>
    <p><strong>Equipment Involved in Ignition:</strong> ' . $data['ignition_equipment'] . '</p>
    
    <p><strong>Form of Heat of Ignition:</strong> ' . $data['ignition_heat_form'] . '</p>
    <p><strong>Type of Material Ignited:</strong> ' . $data['ignited_material_type'] . '</p>
    
    <p><strong>Form of Material Ignited:</strong> ' . $data['ignited_material_form'] . '</p>
    <p><strong>Method of Extinguishment:</strong> ' . $data['extinguishment_method'] . '</p>
    
    <p><strong>Level of Fire Origin:</strong> ' . $data['fire_origin_level'] . '</p>
    
    <h2 class="section-title">_____________________________________________________</h2>
</div>

<div class="section">
    <h2 class="section-title">Section III - Structure Fire</h2>
    <h2 class="section-title">_____________________________________________________</h2>
    <p><strong>Extent of Flame Damage:</strong> ' . $data['flame_damage_extent'] . '</p>
    <p><strong>Extent of Smoke Damage:</strong> ' . $data['smoke_damage_extent'] . '</p>
    
    <p><strong>Detector Performance:</strong> ' . $data['detector_performance'] . '</p>
    <p><strong>Sprinkler Performance:</strong> ' . $data['sprinkler_performance'] . '</p>
    
    <p><strong>Material Type Generating Most Smoke:</strong> ' . $data['most_smoke_material_type'] . '</p>
    <p><strong>Avenue of Smoke Travel:</strong> ' . $data['smoke_travel_avenue'] . '</p>
    
    <p><strong>Room of Origin:</strong> ' . $data['origin_room'] . '</p>
    <p><strong>Material Form Generating Most Smoke:</strong> ' . $data['most_smoke_material_form'] . '</p>
    
    <h2 class="section-title">_____________________________________________________</h2>
</div>

<div class="section">
    <h2 class="section-title">Prepared By:</h2>
    <p><strong>Reporter\'s Name:</strong> ' . $data['reporters_name'] . '</p>
    <p><strong>Report Date:</strong> ' . $data['date'] . '</p>
    
</div>';



    // Output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');

    // Close and output PDF document
    $pdf->Output('incident_report_' . $incident_no . '.pdf', 'D');
}
?>