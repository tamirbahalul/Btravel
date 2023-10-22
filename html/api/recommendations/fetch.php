<?php 
    include "../../Config.php";

    if ($db->connect_errno) {
        die("Failed to connect to MySQL: " . $mysqli->connect_error);
    }

    
    $query = "SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id ORDER BY c.rating DESC, c.creation_date DESC LIMIT 3";
    $results = mysqli_query($db, $query);
    $data = array();
    while ($row = mysqli_fetch_array($results)) {
        array_push($data, $row);
    }
    echo json_encode($data);
?>