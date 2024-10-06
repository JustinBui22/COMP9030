<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="styles/style.css" />
		<title>Forgot Password</title>
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
			<h1>Forgot Password</h1>
			<form method="POST" action="forgot-password-task.php" class="form" id="forgotPasswordForm">
				<div class="input-container">
					<input
						type="text"
						id="username"
						placeholder="Username"
						name="username"
					/>
					<span id="usernameError" class="error"></span>
				</div>
				<button
					id="button"
					disabled 
					type="submit"
				>
					Continue
				</button>
			</form>
		</div>
		<script>
        document.addEventListener("DOMContentLoaded", function () {
			const usernameInput = document.getElementById("username");
			const button = document.getElementById("button");

            const checkFormValidity = () => {
                button.disabled = !usernameInput.value;
            };

			usernameInput.addEventListener("input", checkFormValidity);

            document.getElementById("usernameError").textContent = '';

            const forgotPasswordForm = document.getElementById('forgotPasswordForm');
            forgotPasswordForm.onsubmit = function (event) {
                event.preventDefault(); 

                const formData = new FormData(forgotPasswordForm);
                fetch('forgot-password-task.php', {
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
                 
					if (data.error) {
                        if (data.error === 'Username does not exist.') {
                            document.getElementById("usernameError").textContent = data.error;
                        } else {
                            alert(data.error); 
                        }
                    } else {
                        localStorage.setItem('resetData', JSON.stringify(data));
                        window.location.href = 'password-reset.php'; 
                    }
                })
                .catch(error => {
					console.log(error)
                    alert('Error: ' + error.message);
                });
            };
        });
    	</script>
	</body>
</html>  
