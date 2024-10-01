<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once "inc/dbconn.inc.php";

    // 獲取並清理輸入
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $email = htmlspecialchars(trim($_POST['email']));
    $role = htmlspecialchars(trim($_POST['role']));
    $terms = isset($_POST['terms']) ? true : false;

    // 檢查欄位是否完整
    if (empty($username) || empty($password) || empty($email) || empty($role) || !$terms) {
        header("Location: sign-up.php?error=Please fill all fields correctly.");
        exit();
    }

    // 檢查 email 格式
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: sign-up.php?error=Invalid email format.");
        exit();
    }

    // 檢查 username 和 email 是否已存在
    // 使用 check-exists.php 進行檢查
    $usernameExists = json_decode(file_get_contents("http://localhost/www/COMP9030/frontend-code/login/check-exists.php?field=username&value=" . urlencode($username)), true);
    $emailExists = json_decode(file_get_contents("http://localhost/www/COMP9030/frontend-code/login/check-exists.php?field=email&value=" . urlencode($email)), true);
    
    if ($usernameExists['exists']) {
        header("Location: sign-up.php?error=Username already exists.");
        exit();
    }
    if ($emailExists['exists']) {
        header("Location: sign-up.php?error=Email already exists.");
        exit();
    }

    // 將用戶資訊插入資料庫
    $sql = "INSERT INTO account (username, password, email, role, is_logged_in) VALUES (?, ?, ?, ?, false)";
    $statement = mysqli_stmt_init($conn);
    
    if (mysqli_stmt_prepare($statement, $sql)) {
        mysqli_stmt_bind_param($statement, 'ssss', $username, $password, $email, $role);
        mysqli_stmt_execute($statement);
        header("Location: login.php?success=Account created successfully. Please log in.");
    } else {
        header("Location: sign-up.php?error=Database error occurred.");
    }

    mysqli_stmt_close($statement);
    mysqli_close($conn);
}
?>
