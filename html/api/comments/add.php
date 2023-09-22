<?php 
    include "../../Config.php";

    if ($db->connect_errno) {
        die("Failed to connect to MySQL: " . $mysqli->connect_error);
    }
    
    $inputJSON = file_get_contents('php://input');
    $inputData = json_decode($inputJSON, true);
    $place_id = $inputData['place_id'];
    $place_name = $inputData['place_name'];
    $place_address = $inputData['place_address'];
    $text = $inputData['text'];
    $rating = $inputData['rating'];
    $user_id = $inputData['user_id'];

    $insert_pw_attempt = "INSERT INTO comments(place_id, place_name, place_address, user_id, text, rating) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $insert_pw_attempt);
    mysqli_stmt_bind_param($stmt, "ssssss", $place_id, $place_name, $place_address, $user_id, $text, $rating);
    mysqli_stmt_execute($stmt);
?>