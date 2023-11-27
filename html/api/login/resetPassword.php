<?php 
include "../../Config.php";

if ($db->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

function sanitizeInput($input) {
    global $db;
    $input = trim($input);
    $input = mysqli_real_escape_string($db, $input);
    return $input;
}

if (isset($_POST['submit'])) {

    $username = sanitizeInput($_POST['username']);
    $currentPassword = sanitizeInput($_POST['currentPassword']);
    $newPassword = sanitizeInput($_POST['newPassword']);
    $newPasswordConfirm = sanitizeInput($_POST['newPasswordConfirm']);

    error_log("username = $username");
    error_log("currentPassword = $currentPassword");
    error_log("newPassword = $newPassword");
    error_log("newPasswordConfirm = $newPasswordConfirm");

    if (strcmp($newPassword, $newPasswordConfirm) != 0) {
        echo "New password was not confirmed correctly";
        exit();
    }

    if (strlen($newPassword) < 8 || !preg_match("/[a-zA-Z]/", $newPassword)) {
        echo "password need to be with 1 letter and 8 chracters";
        exit();
    }

    $hashCurrPwd = sha1($currentPassword);
    $user_id_query = "SELECT id FROM users WHERE username = '$username' AND password = '$hashCurrPwd'";
    $user_id_result = mysqli_query($db, $user_id_query);
    $user_exists = false;
    if ($row = $user_id_result->fetch_assoc()) {
        $user_id = $row['id'];
        $user_exists = true;
    }
    
    if ($user_exists) {        
        $hashPwd = sha1($newPassword);
        $insert_pass = "UPDATE users SET password = '$hashPwd' WHERE id = $user_id";
        $db->query($insert_pass);
        header("Location: ../../loginPage.php");
        exit();
    } else {
        echo "username or password are not correct";
        exit();
    }
    
    
}

?>