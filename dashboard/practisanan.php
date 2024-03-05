<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Animated Div</title>
<style>
    /* CSS for the animated div */
    .animated-div {
        opacity: 0; /* Start with zero opacity */
        animation: fadeIn 1s ease forwards; /* Define animation */
    }

    /* Define animation keyframes */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
</style>
</head>
<body>

<button onclick="appear()">Appear</button>
<div id="myDiv" class="animated-div" style="display: none;">
    This is the animated div!
</div>

<script>
    // Function to make the div appear with animation
    function appear() {
        var div = document.getElementById("myDiv");
        div.style.display = "block"; // Show the div
    }
</script>

</body>
</html>
