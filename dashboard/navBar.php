<script src="./js/script.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">
<link rel="stylesheet" href="./css/style.css" />

<nav>
    <input type="checkbox" id="check" />
    <label for="check" class="checkbtn">
        <i class="fas fa-bars fa-4xl" style = "margin-top:4vh;"></i>
    </label>
    <label class="logo"><i class="fa-solid fa-house-fire fa-xs"></i> <span style="font-style: italic;">A-FAST</span></label>
    <ul>
        <li><a href="home.php">Dashboard</a></li>
        <li><a href="statistics.php">Statistics</a></li>
        <li><a href="history.php">History</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="#" onclick="return confirmLogout();"> Sign Out </a></li>
    </ul>
</nav>
