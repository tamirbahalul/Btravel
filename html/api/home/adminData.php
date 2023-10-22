<?php 
    include "../../Config.php";

    if ($db->connect_errno) {
        die("Failed to connect to MySQL: " . $mysqli->connect_error);
    }

    $commentsQuery = "SELECT COUNT(*) AS numOfComments FROM comments;";
    $commentsResults = mysqli_query($db, $commentsQuery);

    $usersQuery = "SELECT COUNT(*) AS numOfUsers FROM users;";
    $usersResults = mysqli_query($db, $usersQuery);

    $numOfComments = $commentsResults->fetch_assoc()['numOfComments'];
    $numOfUsers = $usersResults->fetch_assoc()['numOfUsers'];

    $data = array(
        'comments' => $numOfComments,
        'users' => $numOfUsers
    );
    
    echo json_encode($data);
?>
