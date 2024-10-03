<?php
if (isset($_POST["username"])) {
    $username = htmlspecialchars($_POST["username"]);
    
    require_once "inc/dbconn.inc.php";

    $sql = "SELECT * FROM account WHERE username=?;";
    $statement = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($statement, $sql)) {
        mysqli_stmt_bind_param($statement, 's', $username);
        mysqli_stmt_execute($statement);
        
        $result = mysqli_stmt_get_result($statement);
        if ($row = mysqli_fetch_assoc($result)) {
            $resetData = [
                'username' => $row['username'],
                'email' => $row['email']
            ];
            header('Content-Type: application/json'); 
            echo json_encode($resetData);
            exit();
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
