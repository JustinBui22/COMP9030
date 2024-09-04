document.addEventListener('DOMContentLoaded', () => {
	const loginUsernameInput = document.getElementById('loginUsername');
	const loginPasswordInput = document.getElementById('loginPassword');
	const loginButton = document.getElementById('loginButton');
	const loginError = document.getElementById('loginError');

	const signUpUsernameInput = document.getElementById('signUpUsername');
	const signUpPasswordInput = document.getElementById('signUpPassword');
	const signUpEmailInput = document.getElementById('signUpEmail');
	const termsCheckbox = document.getElementById('terms');
	const signupButton = document.getElementById('signupButton');
	const signUpUsernameError = document.getElementById('signUpUsernameError');
	const signUpEmailError = document.getElementById('signUpEmailError');

	const forgotPasswordUsernameInput = document.getElementById(
		'forgotPasswordUsername'
	);
	const forgotPasswordContinueButton = document.getElementById(
		'forgotPasswordContinueButton'
	);
	const forgotPasswordUsernameError = document.getElementById(
		'forgotPasswordUsernameError'
	);

	const newPasswordInput = document.getElementById('newPassword');
	const confirmPasswordInput = document.getElementById('confirmPassword');
	const newPasswordChangeButton = document.getElementById(
		'newPasswordchangeButton'
	);
	const newPasswordError = document.getElementById('newPasswordError');

	const checkPasswordsMatch = () => {
		if (newPasswordInput.value && confirmPasswordInput.value) {
			if (newPasswordInput.value === confirmPasswordInput.value) {
				newPasswordChangeButton.disabled = false;
				newPasswordError.textContent = '';
			} else {
				newPasswordChangeButton.disabled = true;
				newPasswordError.textContent = 'Passwords do not match';
			}
		} else {
			newPasswordChangeButton.disabled = true;
			newPasswordError.textContent = '';
		}
	};

	const isValidEmail = (email) => {
		const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		return emailPattern.test(email);
	};

	const checkFormValidity = () => {
		if (
			!signUpUsernameInput ||
			!signUpPasswordInput ||
			!signUpEmailInput ||
			!termsCheckbox ||
			!signupButton ||
			!signUpUsernameError ||
			!signUpEmailError
		)
			return;

		const isUsernameFilled = signUpUsernameInput.value.trim() !== '';
		const isPasswordFilled = signUpPasswordInput.value.trim() !== '';
		const isEmailFilled = signUpEmailInput.value.trim() !== '';
		const isTermsChecked = termsCheckbox.checked;
		const isEmailValid = isValidEmail(signUpEmailInput.value);
		const isUsernameValid = !localStorage.getItem(
			signUpUsernameInput.value.trim()
		);

		if (isUsernameFilled && !isUsernameValid) {
			signUpUsernameError.textContent = 'username already exists';
			signUpUsernameError.classList.add('show');
		} else {
			signUpUsernameError.textContent = '';
			signUpUsernameError.classList.remove('show');
		}

		if (isEmailFilled && !isEmailValid) {
			signUpEmailError.textContent = 'Invalid email format';
			signUpEmailError.classList.add('show');
		} else {
			signUpEmailError.textContent = '';
			signUpEmailError.classList.remove('show');
		}

		signupButton.disabled = !(
			isUsernameFilled &&
			isPasswordFilled &&
			isEmailFilled &&
			isEmailValid &&
			isTermsChecked &&
			isUsernameValid
		);
	};

	const checkUsernameExists = () => {
		if (
			!forgotPasswordUsernameInput ||
			!forgotPasswordContinueButton ||
			!forgotPasswordUsernameError
		)
			return;

		const username = forgotPasswordUsernameInput.value.trim();
		const usernameExists = localStorage.getItem(username);

		if (username && !usernameExists) {
			forgotPasswordUsernameError.textContent =
				'The username does not exist.';
			forgotPasswordUsernameError.classList.add('show');
		} else {
			forgotPasswordUsernameError.textContent = '';
			forgotPasswordUsernameError.classList.remove('show');
		}

		forgotPasswordContinueButton.disabled = !usernameExists;
	};

	const handleLogin = () => {
		const username = loginUsernameInput.value.trim();
		const password = loginPasswordInput.value.trim();
		const userData = JSON.parse(localStorage.getItem(username));

		if (userData && userData.password === password) {
			window.location.href =
				'../therapist-module/dashboard/dashboard.html';
		} else {
			loginError.textContent = 'Invalid username or password';
			loginError.classList.add('show');
		}
	};

	const handleSignUp = () => {
		if (
			!signUpUsernameInput ||
			!signUpPasswordInput ||
			!signUpEmailInput ||
			!signUpUsernameError
		)
			return;

		const username = signUpUsernameInput.value.trim();
		const password = signUpPasswordInput.value.trim();
		const email = signUpEmailInput.value.trim();

		if (localStorage.getItem(username)) {
			signUpUsernameError.textContent = 'username already exists';
			signUpUsernameError.classList.add('show');
			return;
		}

		localStorage.setItem(username, JSON.stringify({ password, email }));

		window.location.href = 'sign-up-success.html';
	};

	const handleForgotPasswordContinue = () => {
		if (
			!forgotPasswordUsernameInput ||
			!forgotPasswordContinueButton ||
			!forgotPasswordUsernameError
		)
			return;

		const username = forgotPasswordUsernameInput.value.trim();
		const userData = JSON.parse(localStorage.getItem(username));

		if (userData && userData.email) {
			localStorage.setItem(
				'resetData',
				JSON.stringify({ username, email: userData.email })
			);

			window.location.href = 'password-reset.html';
		}
	};

	const handleChangePassword = () => {
		const resetData = JSON.parse(localStorage.getItem('resetData'));
		if (resetData && resetData.username) {
			const username = resetData.username;
			const userData = JSON.parse(localStorage.getItem(username));

			if (userData) {
				const newPassword = newPasswordInput.value;
				userData.password = newPassword;
				localStorage.setItem(username, JSON.stringify(userData));
				window.location.href = 'password-reset-success.html';
			}
		}
	};

	const resetData = JSON.parse(localStorage.getItem('resetData'));

	if (resetData) {
		const emailElement = document.querySelector('.reset-email-placeholder');
		if (emailElement) {
			emailElement.textContent = resetData.email;
		}

		// localStorage.removeItem('resetData');
	}

	if (loginButton) {
		loginButton.addEventListener('click', handleLogin);
	}
	if (signUpUsernameInput)
		signUpUsernameInput.addEventListener('input', checkFormValidity);
	if (signUpPasswordInput)
		signUpPasswordInput.addEventListener('input', checkFormValidity);
	if (signUpEmailInput)
		signUpEmailInput.addEventListener('input', checkFormValidity);
	if (termsCheckbox)
		termsCheckbox.addEventListener('change', checkFormValidity);
	if (signupButton) signupButton.addEventListener('click', handleSignUp);

	if (forgotPasswordUsernameInput)
		forgotPasswordUsernameInput.addEventListener(
			'input',
			checkUsernameExists
		);
	if (forgotPasswordContinueButton)
		forgotPasswordContinueButton.addEventListener(
			'click',
			handleForgotPasswordContinue
		);

	if (newPasswordInput && confirmPasswordInput && newPasswordChangeButton) {
		newPasswordInput.addEventListener('input', checkPasswordsMatch);
		confirmPasswordInput.addEventListener('input', checkPasswordsMatch);
		newPasswordChangeButton.addEventListener('click', handleChangePassword);
	}
});
