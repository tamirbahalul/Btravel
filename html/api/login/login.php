<?php session_start();

    // start regular user login   
    include "../../Config.php";

    $inputJSON = file_get_contents('php://input');
    $inputData = json_decode($inputJSON, true);
    $username = mysqli_real_escape_string($db, $inputData['username']);
    $password = mysqli_real_escape_string($db, $inputData['password']);
    
    $error_message = '';
    if(!empty($username) && !empty($password)) {
        $hashedPassword = sha1($password);
        $query = "SELECT * FROM users WHERE username = '$username' and password = '$hashedPassword';";
        $result = mysqli_query($db,$query);

        if(mysqli_num_rows($result) == 1) {
            $row = $result->fetch_assoc();
            $_SESSION['login_user'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['is_admin'] = $row['is_admin'];
            header("Location: ../../HomePage.php");
            exit();
        } else {
            $error_message = "Your Login Name or Password is invalid";
            http_response_code(404);
        }
    } else {
        $error_message = "Your Login Name or Password is empty";
        http_response_code(404);
    }

    $data = array(
        'error' => $error_message
    );
    echo json_encode($data);
?>