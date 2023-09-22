<?php 
    include "../../Config.php";

    if ($db->connect_errno) {
        die("Failed to connect to MySQL: " . $mysqli->connect_error);
    }

    $query = "SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id";
    if (isset($_GET['orderby'])) {
        $query = $query . " ORDER BY c.rating " . $_GET['orderby'];
    } else {
        $query = $query . " ORDER BY c.id DESC";
    }
    $results = mysqli_query($db, $query);
    $data = array();
    while ($row = mysqli_fetch_array($results)) {
        array_push($data, $row);
    }
    echo json_encode($data);
?>