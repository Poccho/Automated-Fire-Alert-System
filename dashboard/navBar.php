<script src="./js/script.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">

<nav>
    <input type="checkbox" id="check" />
    <label for="check" class="checkbtn">
        <i class="fas fa-bars fa-4xl" style = "margin-top:4vh;"></i>
    </label>
    <label class="logo"><i class="fa-solid fa-house-fire fa-xs"></i> AFAS</label>
    <ul>
        <li><a href="home.php">Dashboard</a></li>
        <li><a href="statistics.php">Statistics</a></li>
        <li><a href="history.php">History</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="#" onclick="return confirmLogout();"> Sign Out </a></li>
    </ul>
</nav>

<script>
    $(document).ready(function () {
        // Get the current page URL
        var currentPageUrl = window.location.href;

        // Get all the navigation links
        var navLinks = $("nav ul li a");

        // Loop through each navigation link
        navLinks.each(function () {
            // Get the href attribute of the link
            var linkHref = $(this).attr("href");

            // Switch based on the link's href
            switch (linkHref) {
                case "home.php":
                    if (currentPageUrl.includes(linkHref) || (currentPageUrl.endsWith(".php") && linkHref === "home.php")) {
                        $(this).closest("li").addClass("active");
                    }
                    break;
                case "statistics.php":
                    if (currentPageUrl.includes(linkHref)) {
                        $(this).closest("li").addClass("active");
                    }
                    break;
                case "history.php":
                    if (currentPageUrl.includes(linkHref)) {
                        $(this).closest("li").addClass("active");
                    }
                    break;
                case "contact.php":
                    if (currentPageUrl.includes(linkHref)) {
                        $(this).closest("li").addClass("active");
                    }
                    break;
                // Add cases for other links as needed

                default:
                    break;
            }
        });
    });



</script>