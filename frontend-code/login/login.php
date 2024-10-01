<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles/style.css" />
    <title>Login</title>
    <style>
        .error-message {
            color: red;
            font-size: 12px;
            display: block;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form method="POST" action="login-task.php" class="form" id="loginForm">
            <div class="input-container">
                <input type="text" id="username" placeholder="Username" name="username" />
                <span id="usernameError" class="error-message"></span>
            </div>
            <div class="input-container">
                <input type="password" id="password" placeholder="Password" name="password" />
                <span id="passwordError" class="error-message"></span>
            </div>
            <div class="form-actions">
                <div class="checkbox">
                    <input type="checkbox" id="rememberMe" />
                    <label for="rememberMe">Remember me</label>
                </div>
                <a class="forgot-password-button" href="forgot-password.php">Forgot Password?</a>
            </div>
            <button type="submit" id="loginButton">Login</button>
            <p>
                Don't have an account?
                <a href="sign-up.php" class="signup-button">Sign up</a>
            </p>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("usernameError").textContent = '';
            document.getElementById("passwordError").textContent = '';

            const loginForm = document.getElementById('loginForm');
            loginForm.onsubmit = function (event) {
                event.preventDefault(); 

                const formData = new FormData(loginForm);
                fetch('login-task.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById("usernameError").textContent = '';
                    document.getElementById("passwordError").textContent = '';

                    if (data.error) {
                        if (data.error === 'Incorrect password.') {
                            document.getElementById("passwordError").textContent = data.error;
                        } else if (data.error === 'Username does not exist.') {
                            document.getElementById("usernameError").textContent = data.error;
                        } else {
                            alert(data.error); 
                        }
                    } else {
                        localStorage.setItem('userData', JSON.stringify(data));
                        window.location.href = '../common/index.html'; 
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            };
        });
    </script>
</body>
</html>
