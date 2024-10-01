<?php
if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);
    
    require_once "inc/dbconn.inc.php";

    // 查詢帳號的資訊，包括角色
    $sql = "SELECT * FROM account WHERE username=?;";
    $statement = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($statement, $sql)) {
        mysqli_stmt_bind_param($statement, 's', $username);
        mysqli_stmt_execute($statement);
        
        $result = mysqli_stmt_get_result($statement);
        if ($row = mysqli_fetch_assoc($result)) {
            // 驗證密碼
            if ($password == $row['password']) {
                // 更新登入狀態
                $update_sql = "UPDATE account SET is_logged_in = true WHERE username = ?";
                $update_stmt = mysqli_stmt_init($conn);

                if (mysqli_stmt_prepare($update_stmt, $update_sql)) {
                    mysqli_stmt_bind_param($update_stmt, 's', $username);
                    mysqli_stmt_execute($update_stmt);
                    
                    // 登入成功，返回使用者資訊
                    $userData = [
                        'username' => $row['username'],
                        'role' => $row['role']
                    ];
                    header('Content-Type: application/json'); // 設定返回類型為 JSON
                    echo json_encode($userData);
                    exit();
                } else {
                    // 資料庫錯誤處理
                    echo json_encode(['error' => 'Database error occurred.']);
                    exit();
                }
            } else {
                // 密碼錯誤處理
                echo json_encode(['error' => 'Incorrect password.']);
                exit();
            }
        } else {
            // 使用者不存在處理
            echo json_encode(['error' => 'Username does not exist.']);
            exit();
        }
    } else {
        // SQL 準備錯誤處理
        echo json_encode(['error' => 'Database error occurred.']);
        exit();
    }

    mysqli_stmt_close($statement);
    mysqli_close($conn);
}
?>
