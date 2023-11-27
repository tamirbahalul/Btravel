<?php 
include "Config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
if ($db->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}
if (isset($_POST['submit'])) {

    // Check if email exists in the database and Perform necessary validation here
    $email = $_POST['email'];
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){}

    // Prepare a SQL statement to check if the email exists
    $user_id_query = "SELECT id FROM users WHERE email = '$email'";
    $user_id_result = mysqli_query($db, $user_id_query);
    $user_exists = false;
    if ($row = $user_id_result->fetch_assoc()) {
        $user_id = $row['id'];
        $user_exists = true;
    }
    
    // Check the count of rows returned
    if ($user_exists) {
        // Email exists in the database
        
        $pwd = generateRandomString();
        $hashPwd = sha1($pwd);
        //------------ Store the token in the database along with the user's email
        $insert_pass = "UPDATE users SET password = '$hashPwd' WHERE id = $user_id";
        $db->query($insert_pass);

        // Create a new PHPMailer instance
        $mail = new PHPMailer();

        // Set the SMTP server details for Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tamirbahalul1000@gmail.com';
        $mail->Password = 'uqhdpfzckowosdvi';
        $mail->SMTPSecure = 'tls'; // or 'ssl' for SSL encryption
        $mail->Port = 587; // or 465 for SSL

        // Set the sender and recipient addresses
        $mail->setFrom('tamir.bahalul@gmail.com', 'Your Name');
        $mail->addAddress($email, 'test');// $row['username']);

        // Set email content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';
        $mail->Body = 'Your new password is: '. $pwd . ' - Please change it';
        $mail->send();   
        // Display a success message to the user
        header("Location: resetPassPage2.php");
        exit();
    } else {
        // Email does not exist in the database
        echo "the email is not exists, please put correct email";
        //header("Location: resetPassPage.php");
        exit();
    }
    
    
}

// Generate a pass
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>