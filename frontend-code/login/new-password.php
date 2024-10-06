<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="styles/style.css" />
		<title>New Password</title>
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
		<form method="POST" action="new-password-task.php" id="newPasswordForm" class="container">
			<h1>New Password</h1>
			<div class="form">
				<input
					type="password"
					id="newPassword"
					placeholder="Create new password"
					name="newPassword"
				/>
				<input
					type="password"
					id="confirmPassword"
					placeholder="Confirm your password"
					name="confirmPassword"
				/>
				<span id="passwordError" class="error-message"></span>
				<button id="button" type="submit" disabled>Change</button>
			</div>
		</form>
		<script>
			document.addEventListener("DOMContentLoaded", function () {
            	const newPassword = document.getElementById("newPassword");
				const confirmPassword = document.getElementById("confirmPassword");
				const button = document.getElementById("button");

				const checkFormValidity = () => {
					if(newPassword.value && confirmPassword.value){
						button.disabled = false
					}
				};

				newPassword.addEventListener("blur", checkFormValidity);
            	confirmPassword.addEventListener("blur", checkFormValidity);

				const resetData = JSON.parse(localStorage.getItem('resetData'));
				let username = ''
				if (resetData && resetData.username) {
					username = resetData.username;
				}
				
				const newPasswordForm = document.getElementById('newPasswordForm');
				newPasswordForm.onsubmit = function (event) {
					event.preventDefault(); 

					const formData = new FormData(newPasswordForm);
					formData.append('username', username);
					
					fetch('new-password-task.php', {
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
						document.getElementById("passwordError").textContent = '';
						if (data.error) {
							document.getElementById("passwordError").textContent = data.error;
						} else {
							window.location.href = 'password-reset-success.php'; 
						}
					})
					.catch(error => {
						alert('Error: ' + error.message);
					});
				};
			})
		</script>
	</body>
</html>
