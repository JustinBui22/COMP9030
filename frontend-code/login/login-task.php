<?php
if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);
    
    require_once "inc/dbconn.inc.php";

    $sql = "SELECT * FROM account WHERE username=?;";
    $statement = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($statement, $sql)) {
        mysqli_stmt_bind_param($statement, 's', $username);
        mysqli_stmt_execute($statement);
        
        $result = mysqli_stmt_get_result($statement);
        if ($row = mysqli_fetch_assoc($result)) {
            if ($password == $row['password']) {
                $update_sql = "UPDATE account SET is_logged_in = true WHERE username = ?";
                $update_stmt = mysqli_stmt_init($conn);

                if (mysqli_stmt_prepare($update_stmt, $update_sql)) {
                    mysqli_stmt_bind_param($update_stmt, 's', $username);
                    mysqli_stmt_execute($update_stmt);
                    
                    $userData = [
                        'username' => $row['username'],
                        'role' => $row['role']
                    ];
                    header('Content-Type: application/json'); 
                    echo json_encode($userData);
                    exit();
                } else {
                    echo json_encode(['error' => 'Database error occurred.']);
                    exit();
                }
            } else {
                echo json_encode(['error' => 'Incorrect password.']);
                exit();
            }
        } else {
            echo json_encode(['error' => 'Username does not exist.']);
            exit();
        }
    } else {
        echo json_encode(['error' => 'Database error occurred.']);
        exit();
    }

    mysqli_stmt_close($statement);
    mysqli_close($conn);
}
?>
