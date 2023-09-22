<?php session_start();

    // start regular user login   
    include "Config.php";

    if(!empty($_POST['username']) && !empty($_POST['password'])) {

        $username = mysqli_real_escape_string($db,$_POST['username']);//avoid SQL Injection attack
        $password = sha1(mysqli_real_escape_string($db,$_POST['password'])); 


        $query = "SELECT * FROM users WHERE username = '$username' and password = '$password'";
        $result = mysqli_query($db,$query);

        if(mysqli_num_rows($result) == 1) {
            $row = $result->fetch_assoc();
            $_SESSION['login_user'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['is_admin'] = $row['is_admin'];
            header("Location: HomePage.php");
            exit();
         }else {
            echo "Your Login Name or Password is invalid";
            header('Refresh:3; url=https://localhost:443/www/project/html/SignupPage.php');
            exit();
         }

        }

?>