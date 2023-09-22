<?php 
include "Config.php";

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
        // Step 3: Generate a Token
        $token = generateToken(); // Implement your own token generation logic
    
        //------------ Store the token in the database along with the user's email
        $insert_pw_attempt = "INSERT INTO password_reset_attempts (token, user_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($db, $insert_pw_attempt);
        mysqli_stmt_bind_param($stmt, "ss", $token, $user_id);
        mysqli_stmt_execute($stmt);

        //------------ Step 4: Email Verification
        $resetLink = "https://localhost/reset_password.php?token=" . $token;
        $emailSubject = "Password Reset";
        $emailBody = "To reset your password, click the link below:\n\n" . $resetLink;
        $headers = "From: pwreset@btravel.com";

        // Send the email
        mail($email, $emailSubject, $emailBody, $headers);

        // Display a success message to the user
        echo "Password reset link has been sent to your email address, please enter and verify.";
        // header("Refresh:5; url=https://localhost/www/project/html/resetPassPage2.php");
        exit();
    } else {
        // Email does not exist in the database
        echo "the email is not exists, please put correct email";
        header("Refresh:3; url=https://localhost/www/project/html/resetPassPage.php");
        exit();
    }
    
    
}
// Generate a token
function generateToken() {
    // Implement your own token generation logic (e.g., random string generation)
    $length = 32; // Length of the token in bytes
    
    // Generate random bytes
    $bytes = random_bytes($length);
    
    // Convert bytes to a string representation
    $token = base64_encode($bytes);
    
    // Remove any characters that are not URL-safe
    $token = str_replace(['+', '/', '='], ['-', '_', ''], $token);
    
    return $token;
}


?>