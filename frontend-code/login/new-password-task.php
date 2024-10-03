<?php
if (isset($_POST["username"]) && isset($_POST["newPassword"]) && isset($_POST["confirmPassword"])) {
    $username = htmlspecialchars($_POST["username"]);
    $newPassword = htmlspecialchars($_POST["newPassword"]);
    $confirmPassword = htmlspecialchars($_POST["confirmPassword"]);
    
    if(!$newPassword || !$confirmPassword){
        header('Content-Type: application/json'); 
        echo json_encode(['error' => 'Both fields are required.']);
        exit();
    }

    if($newPassword != $confirmPassword){
        header('Content-Type: application/json'); 
        echo json_encode(['error' => 'Passwords are inconsistent.']);
        exit();
    }

    require_once "inc/dbconn.inc.php";

    $sql = "UPDATE account SET password = ? WHERE username=?;";
    $statement = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($statement, $sql)) {
        mysqli_stmt_bind_param($statement, 'ss', $newPassword, $username);
        
        if (mysqli_stmt_execute($statement)) {
            echo json_encode(['success' => 'Password updated successfully.']);
            exit();
        } else {
            echo json_encode(['error' => 'Username does not exist or no changes made.']);
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
