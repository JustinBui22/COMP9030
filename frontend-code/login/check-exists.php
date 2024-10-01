<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once "inc/dbconn.inc.php";

    // 獲取參數
    $field = htmlspecialchars(trim($_GET['field']));
    $value = htmlspecialchars(trim($_GET['value']));

    // 驗證參數
    if (!in_array($field, ['username', 'email'])) {
        echo json_encode(['exists' => false]);
        exit();
    }

    // 檢查帳號或 email 是否已存在
    $sql = "SELECT * FROM account WHERE $field = ?";
    $statement = mysqli_stmt_init($conn);
    
    if (mysqli_stmt_prepare($statement, $sql)) {
        mysqli_stmt_bind_param($statement, 's', $value);
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        $exists = mysqli_num_rows($result) > 0;
        
        // 回傳結果
        echo json_encode(['exists' => $exists]);
    } else {
        echo json_encode(['exists' => false]);
    }

    mysqli_stmt_close($statement);
    mysqli_close($conn);
}
?>
