<?php
    include "../../Config.php";

    if ($db->connect_errno) {
        die("Failed to connect to MySQL: " . $mysqli->connect_error);
    }
    
    $inputJSON = file_get_contents('php://input');
    $inputData = json_decode($inputJSON, true);
    $comment_id = $inputData['comment_id'];
    $comment_text = $inputData['comment_text'];

    $query = "UPDATE comments SET text = ? WHERE id = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "ss", $comment_text, $comment_id);
    mysqli_stmt_execute($stmt);
?>
