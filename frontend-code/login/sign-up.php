<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles/style.css" />
    <title>Sign Up</title>
    <style>
        .error {
            color: red;
            font-size: 12px;
            display: block;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sign Up</h1>
        <form method="POST" action="sign-up-task.php" class="form" id="signUpForm">
            <div class="input-container">
                <input type="text" id="username" placeholder="Username" name="username" required />
                <span id="usernameError" class="error"></span>
            </div>
            <div class="input-container">
                <input type="password" id="password" placeholder="Password" name="password" required />
                <span id="passwordError" class="error"></span>
            </div>
            <div class="input-container">
                <input type="email" id="email" placeholder="Email" name="email" required />
                <span id="emailError" class="error"></span>
            </div>
            <div class="input-container">
                <select id="role" aria-label="Role selection" name="role" required>
                    <option value="">Select your role</option>
                    <option value="therapist">Therapist</option>
                    <option value="patient">Patient</option>
                </select>
                <span id="roleError" class="error"></span>
            </div>
            <div class="checkbox">
                <input type="checkbox" id="terms" name="terms" required />
                <label for="terms">I agree to the terms & conditions</label>
            </div>
            <button type="submit" id="button" disabled>Sign up</button>
            <p>
                Already have an account?
                <a href="login.php" class="signin-button">Sign in</a>
            </p>
        </form>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const usernameInput = document.getElementById("username");
            const emailInput = document.getElementById("email");
            const passwordInput = document.getElementById("password");
            const roleInput = document.getElementById("role");
            const termsCheckbox = document.getElementById("terms");
            const button = document.getElementById("button");

            const checkFormValidity = () => {
                button.disabled = !(
                    usernameInput.value && 
                    passwordInput.value && 
                    emailInput.value && 
                    roleInput.value && 
                    termsCheckbox.checked
                );
            };

            usernameInput.addEventListener("input", checkFormValidity);
            emailInput.addEventListener("input", checkFormValidity);
            passwordInput.addEventListener("input", checkFormValidity);
            roleInput.addEventListener("change", checkFormValidity);
            termsCheckbox.addEventListener("change", checkFormValidity);

            emailInput.addEventListener("input", function () {
                const emailError = document.getElementById("emailError");
                const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
                if (emailInput.value && !emailPattern.test(emailInput.value)) {
                    emailError.textContent = "Invalid email format.";
                } else {
                    emailError.textContent = "";
                }
            });

            usernameInput.addEventListener("blur", function () {
                checkExists("username", usernameInput.value, "usernameError");
            });

            emailInput.addEventListener("blur", function () {
                checkExists("email", emailInput.value, "emailError");
            });

            const checkExists = (field, value, errorId) => {
                const errorMessage = document.getElementById(errorId);
                if (value) {
                    fetch(`check-exists.php?field=${field}&value=${encodeURIComponent(value)}`)
                        .then(response => response.json())
                        .then(data => {
                            errorMessage.textContent = data.exists ? `${field.charAt(0).toUpperCase() + field.slice(1)} is already taken.` : "";
                            checkFormValidity(); 
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                } else {
                    errorMessage.textContent = "";
                }
            };
        });
    </script>
</body>
</html>
