<?php session_start();
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "btravel";
    //connect to db and start of signup.
    $conn = mysqli_connect($host, $username, $password, $database);
 

// Function to sanitize user input
function sanitizeInput($input) {
    global $conn;
    $input = trim($input);
    $input = mysqli_real_escape_string($conn, $input);
    return $input;
}

// Function to hash the user password
function hashPassword($password) {
    $hashedPassword = sha1($password);
    return $hashedPassword;
}

// Function to create a new user
function createUser($username,$email,$password) {
    global $conn;
    if (empty($username) || empty($password)) {
        echo "Username or password cannot be empty.";
        header("Refresh:5; url=https://localhost/www/project/html/SignupPage.php");
        exit();
    }
    
        // Sanitize the username and password
        $username = sanitizeInput($username);
        $email = sanitizeInput($email);
        $password = sanitizeInput($password);
        if (!(preg_match("/[a-zA-Z]/", $username))) {
            // Name doesn't contain at least one letter
            echo "Username must 1 letter.";
            header("Refresh:5; url=https://localhost/www/project/html/SignupPage.php");
            exit();
        }

        // Check if the email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {}

        if (strlen($password) < 8 || !preg_match("/[a-zA-Z]/", $password)) {
            // Password is not valid
            echo "password need to be with 1 letter and 8 chracters";
            header("Refresh:5; url=https://localhost/www/project/html/SignupPage.php");
            exit();
        }
    
        // Hash the password
        $hashedPassword = hashPassword($password);
    
        // Insert the user into the database
        try{
            $sql = "INSERT INTO users(email,password,username) VALUES ('$email','$hashedPassword','$username')";
            if ($conn->query($sql)) {
                // User created successfully
                $_SESSION['login_user'] =$username;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['is_admin'] = $result['is_admin'];
                header("Location: HomePage.php");
                exit();
            }else{
                echo "not succses.";
            }
        }catch(Exception $e){
            echo "user is duplicate, you have already account here!";
            header("Refresh:3; url=https://localhost/www/project/html/SignupPage.php");
            exit();
        }
        
    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['username'], $_POST['email'],$_POST['password'])){
        // Retrieve the form data
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Call the createUser function
        createUser($username, $email, $password); 
    } else if(isset($_POST['username-google'], $_POST['email-google'],$_POST['password-google'])) {
        $username = $_POST['username-google'];
        $email = $_POST['email-google'];
        $password = sha1($_POST['password-google']);

        $sql = "SELECT * FROM users WHERE username =  '$username' AND email = '$email'";
        $res = $conn->query($sql);
        if(mysqli_num_rows($res) == 0) {
            createUser($username, $email, $password);
        } else {
            login1();
        }
    }
    
}

function login1(){
    global $conn;

    if(!empty($_POST['username']) && !empty($_POST['password'])) {

        $username = mysqli_real_escape_string($conn,$_POST['username']);//avoid SQL Injection attack
        $password = sha1(mysqli_real_escape_string($conn,$_POST['password'])); 


        $query = "SELECT * FROM users WHERE username = '$username' and password = '$password'";
        $result = mysqli_query($conn,$query);

        if(mysqli_num_rows($result) == 1) {
            $row = $result->fetch_assoc();
            $_SESSION['login_user'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['is_admin'] = $row['is_admin'];
            header("Location: HomePage.php");
            exit();
         }else {
            echo "Your Login Name or Password is invalid";
            header('Refresh:5; url=https://localhost:443/www/project/html/loginPage.php');
            exit();
         }

    } else if(isset($_POST['username-google'],$_POST['password-google'], $_POST['email-google'])) {
        $username = mysqli_real_escape_string($conn,$_POST['username-google']);//avoid SQL Injection attack
        $password = sha1(mysqli_real_escape_string($conn,$_POST['password-google'])); 
        $email = $_POST['email-google'];

        $query = "SELECT * FROM users WHERE username = '$username' and email = '$email'";
        $result = mysqli_query($conn,$query);

        if(mysqli_num_rows($result) == 1) {
            $row = $result->fetch_assoc();
            $_SESSION['login_user'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['is_admin'] = $row['is_admin'];
            header("Location: HomePage.php");
            exit();
         }else {
            echo "Your Login Name or Password is invalid";
            header('Refresh:5; url=https://localhost:443/www/project/html/SignupPage.php');
            exit();
         }
    } else { 
        echo "missing parameters";
        header('Refresh:5; url=https://localhost:443/www/project/html/SignupPage.php');
        exit();
    }

}

function logout()
{
    include "logout.php";
}

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}