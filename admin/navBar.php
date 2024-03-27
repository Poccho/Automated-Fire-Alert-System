<script src="./js/script.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" />

<nav>
    <input type="checkbox" id="check" />
    <label for="check" class="checkbtn">
        <i class="fas fa-bars fa-4xl" style="margin-top:4vh;"></i>
    </label>
    <label class="logo"><i class="fa-solid fa-house-fire fa-xs"></i> <span
            style="font-style: italic;">A-FAST</span></label>
    <ul>
        <li><a href="index.php"> Add Users </a></li>
        <li><a href="userList.php"> Users </a></li>
        <li><a href="#" onclick="return confirmLogout();"> Sign Out </a></li>
    </ul>
</nav>
