<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles/style.css" />
    <title>Login</title>
    <style>
      /* 顯示錯誤訊息的紅字 */
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

    <!-- 顯示彈窗錯誤訊息的 JS -->
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const urlParams = new URLSearchParams(window.location.search);
        const usernameError = urlParams.get("usernameError");
        const passwordError = urlParams.get("passwordError");
        const generalError = urlParams.get("generalError");

        // 如果有錯誤訊息，顯示到對應的輸入框後面
        if (usernameError) {
          document.getElementById("usernameError").textContent = usernameError;
        }
        if (passwordError) {
          document.getElementById("passwordError").textContent = passwordError;
        }

        // 如果是通用錯誤，顯示彈窗提示
        if (generalError) {
          alert(generalError);
        }

        // 監聽表單提交
        const loginForm = document.getElementById('loginForm');
        loginForm.onsubmit = function (event) {
          event.preventDefault(); // 防止表單默認提交

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
            // 儲存使用者資料到 localStorage
            localStorage.setItem('userData', JSON.stringify(data));
            window.location.href = '../common/index.html'; // 登入成功，重定向
          })
          .catch(error => {
            alert('Error: ' + error.message);
          });
        };
      });
    </script>
  </body>
</html>
