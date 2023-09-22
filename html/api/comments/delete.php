<?php
    include "../../Config.php";

    if ($db->connect_errno) {
        die("Failed to connect to MySQL: " . $mysqli->connect_error);
    }
    
    $inputJSON = file_get_contents('php://input');
    $inputData = json_decode($inputJSON, true);
    $comment_id = $inputData['comment_id'];

    $insert_pw_attempt = "DELETE FROM comments WHERE id = ?";
    $stmt = mysqli_prepare($db, $insert_pw_attempt);
    mysqli_stmt_bind_param($stmt, "s", $comment_id);
    mysqli_stmt_execute($stmt);
?>