<style>
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
        margin-bottom: 20px;
    }

    h2 {
        color: #555;
        font-size: 20px;
        margin-top: 30px;
        margin-bottom: 15px;
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
        margin-bottom: 20px;
    }

    .section-title {
        color: black;
        font-size: 18px;
        margin-bottom: 10px;
    }
</style>
<h1>Fire Incident Report</h1>

<div class="section">
    <h2 class="section-title">Section I - Incident</h2>
    <h2 class="section-title">___________________________________________________________________________</h2>
    <div class="flex">
    <p><strong>Barangay:</strong> ' . $data['barangay'] . '</p>
    <p><strong>Date:</strong> ' . $data['date'] . '</p>
    </div>
    <div class="flex">
    <p><strong>Alarm Time:</strong> ' . $data['alarm_time'] . '</p>
    <p><strong>Building Address:</strong> ' . $data['building_address'] . '</p>
    </div>
    <div class="flex">
    <p><strong>Coordinates:</strong> ' . $data['coordinates'] . '</p>
    <p><strong>Type of Incident:</strong> ' . $data['type_of_incident'] . '</p>
    </div>
    <div class="flex">
    <p><strong>Occupants Were:</strong> ' . $data['occupants_were'] . '</p>
    <p><strong>Brief History of the Incident:</strong> ' . $data['brief_history'] . '</p>
    </div>
    <div class="flex">
    <p><strong>Number of Injuries:</strong> ' . $data['num_injuries'] . '</p>
    <p><strong>Number of Deaths:</strong> ' . $data['num_deaths'] . '</p>
    </div>
</div>

<div class="section">
    <h2 class="section-title">Section II - Fire</h2>
    <div class="flex">
    <p><strong>Area of Fire Origin:</strong> ' . $data['fire_origin_area'] . '</p>
    <p><strong>Equipment Involved in Ignition:</strong> ' . $data['ignition_equipment'] . '</p>
    </div>
    <div class="flex">
    <p><strong>Form of Heat of Ignition:</strong> ' . $data['ignition_heat_form'] . '</p>
    <p><strong>Type of Material Ignited:</strong> ' . $data['ignited_material_type'] . '</p>
    </div>
    <div class="flex">
    <p><strong>Form of Material Ignited:</strong> ' . $data['ignited_material_form'] . '</p>
    <p><strong>Method of Extinguishment:</strong> ' . $data['extinguishment_method'] . '</p>
    </div>
    <div class="flex">
    <p><strong>Level of Fire Origin:</strong> ' . $data['fire_origin_level'] . '</p>
    </div>
</div>

<div class="section">
    <h2 class="section-title">Section III - Structure Fire</h2>
    <div class="flex">
    <p><strong>Extent of Flame Damage:</strong> ' . $data['flame_damage_extent'] . '</p>
    <p><strong>Extent of Smoke Damage:</strong> ' . $data['smoke_damage_extent'] . '</p>
    </div>
    <div class="flex">
    <p><strong>Detector Performance:</strong> ' . $data['detector_performance'] . '</p>
    <p><strong>Sprinkler Performance:</strong> ' . $data['sprinkler_performance'] . '</p>
    </div>
    <div class="flex">
    <p><strong>Material Type Generating Most Smoke:</strong> ' . $data['most_smoke_material_type'] . '</p>
    <p><strong>Avenue of Smoke Travel:</strong> ' . $data['smoke_travel_avenue'] . '</p>
    </div>
    <div class="flex">
    <p><strong>Room of Origin:</strong> ' . $data['origin_room'] . '</p>
    <p><strong>Material Form Generating Most Smoke:</strong> ' . $data['most_smoke_material_form'] . '</p>
    </div>
</div>

<div class="section">
    <h2 class="section-title">Prepared By:</h2>
    <div class="flex">
    <p><strong>Reporter\'s Name:</strong> ' . $data['reporters_name'] . '</p>
    <p><strong>Report Date:</strong> ' . $data['date'] . '</p>
    </div>
</div>