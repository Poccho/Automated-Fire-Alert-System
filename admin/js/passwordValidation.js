document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("userForm");
    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent form submission
        const password1 = document.getElementById("passwordInput").value;
        const password2 = document.getElementById("passwordInput2").value;

        if (password1 !== password2) {
            Swal.fire({
                icon: 'error',
                title: 'Passwords do not match',
                text: 'Please re-enter your password correctly'
            });
            return;
        }

        // Hash the password before submitting the form
        const hashedPassword = hashPassword(password1);

        // Update the password field with the hashed password
        document.getElementById("passwordInput").value = hashedPassword;

        // Now submit the form
        this.submit();
    });

    function hashPassword(password) {
        // You can use a suitable hashing algorithm here, like SHA-256
        // For demonstration purposes, let's use a simple hash
        let hashedPassword = "";
        for (let i = 0; i < password.length; i++) {
            hashedPassword += password.charCodeAt(i) + 3; // A simple shift cipher
        }
        return hashedPassword;
    }
});
