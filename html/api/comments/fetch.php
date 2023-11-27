<?php 
    include "../../Config.php";

    if ($db->connect_errno) {
        die("Failed to connect to MySQL: " . $mysqli->connect_error);
    }

    $condition = "";
    if (isset($_GET['location'])) {
        $location = $_GET['location'];
        $condition = " WHERE c.place_name LIKE '%$location%' OR c.place_address LIKE '%$location%'";
    }
    
    $orderby = "";
    if (isset($_GET['orderby'])) {
        $orderby = " ORDER BY c.rating " . $_GET['orderby'];
    } else {
        $orderby = " ORDER BY c.id DESC";
    }

    $offset = $_GET['offset'];
    $limit = $_GET['limit'];
    $paging = " LIMIT $limit OFFSET $offset";

    $countQuery = "SELECT COUNT(*) as total FROM comments c" . $condition;
    $countResult = $db->query($countQuery);
    $countRow = $countResult->fetch_assoc();
    $count = $countRow['total'];

    $query = "SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id" . $condition . $orderby . $paging;
    error_log($query);
    $results = mysqli_query($db, $query);
    $rows = array();
    while ($row = mysqli_fetch_array($results)) {
        array_push($rows, $row);
    }

    $data = array(
        'rows' => $rows,
        'count' => $count
    );
    echo json_encode($data);
?>