<?php session_start();
   
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
    
    // Function to hash the user password
    function hashPassword($password) {
        $hashedPassword = sha1($password);
        return $hashedPassword;
    }
    
    // Function to create a new user
    function createUser($username, $email, $password) {
        global $db;
        if (empty($username) || empty($password)) {
            return "Username or password cannot be empty.";
        }
        
        // Sanitize the username and password
        $username = sanitizeInput($username);
        $email = sanitizeInput($email);
        $password = sanitizeInput($password);
        if (!(preg_match("/[a-zA-Z]/", $username))) {
            // Name doesn't contain at least one letter
            return "Username must 1 letter.";
        }

        // Check if the email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email address";
        }

        if (strlen($password) < 8 || !preg_match("/[a-zA-Z]/", $password)) {
            // Password is not valid
            return "password need to be with 1 letter and 8 chracters";
        }
    
        // Hash the password
        $hashedPassword = hashPassword($password);
    
        // Insert the user into the database
        try{
            $sql = "INSERT INTO users(email,password,username) VALUES ('$email','$hashedPassword','$username')";
            if ($db->query($sql)) {
                $query = "SELECT * FROM users WHERE username = '$username'";
                $result = mysqli_query($db,$query);
                $row = $result->fetch_assoc();
                // User created successfully
                $_SESSION['login_user'] = $username;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['is_admin'] = false;
                header("Location: ../../HomePage.php");
                exit();
            }else{
                return "not success.";
            }
        }catch(Exception $e){
            return "user is duplicate, you have already account here!";
        }
        return "";  
    }

    function login($username, $password, $email, $method){
        global $db;
        
        if (strcmp($method, "manual") == 0) {
            $query = "SELECT * FROM users WHERE username = '$username' and password = '$password'";
            $result = mysqli_query($db,$query);
    
            if(mysqli_num_rows($result) == 1) {
                $row = $result->fetch_assoc();
                $_SESSION['login_user'] = $row['username'];
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['is_admin'] = $row['is_admin'];
                header("Location: ../../HomePage.php");
                exit();
            } else {
                return "Your Login Name or Password is invalid";
            }
        } else {
            $username = mysqli_real_escape_string($db,$username);//avoid SQL Injection attack
            $password = sha1(mysqli_real_escape_string($db,$password)); 
            $query = "SELECT * FROM users WHERE username = '$username' and email = '$email'";
            $result = mysqli_query($db,$query);
    
            if(mysqli_num_rows($result) == 1) {
                $row = $result->fetch_assoc();
                $_SESSION['login_user'] = $row['username'];
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['is_admin'] = $row['is_admin'];
                header("Location: ../../HomePage.php");
            } else {
                http_response_code(401);
                return "Your Login Name or Password is invalid";
            }
        }
        return "";
    }
    
    error_log('endpoint called');
   $inputJSON = file_get_contents('php://input');
   if ($inputJSON == null) {
    error_log("missing body");
    http_response_code(400);
    echo "Invalid body data";
    exit();
   }

   $inputData = json_decode($inputJSON, true);
   if ($inputData == null) {
    error_log("Invalid body data: " . $inputJSON);
    http_response_code(400);
    echo "Invalid body data";
    exit();
   }

   $username = $inputData['username'];
   $password = $inputData['password'];
   $email = $inputData['email'];
   $method = $inputData['method'];
   
   $error_message = '';
   $is_google = strcmp($method, "google") == 0;
   $sql = "SELECT * FROM users WHERE username = '$username' AND email = '$email'";
   $res = $db->query($sql);
    if (mysqli_num_rows($res) == 0) {
        $error_message = createUser($username, $email, $password);
    } else if ($is_google) {
        $error_message = login($username, $passowrd, $email, $method);
    } else {
        http_response_code(401);
        $error_message = "User already exists";
   }

   if (strcmp($error_message, "") == 0) {
    header("Location: ../../HomePage.php");
    exit();
   } else {
    http_response_code(401);
   }

    $data = array(
        'error' => $error_message
    );
    echo json_encode($data);
?>