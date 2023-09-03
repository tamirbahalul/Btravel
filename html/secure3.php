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
    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE email = '$email'");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Retrieve the result
    $stmt->bind_result($count);
    $stmt->fetch();

    // Check the count of rows returned
    if ($count > 0) {
        // Email exists in the database
        // Step 3: Generate a Token
        $token = generateToken(); // Implement your own token generation logic
    
        //------------ Store the token in the database along with the user's email
    
        //------------ Step 4: Email Verification
        $resetLink = "https://example.com/reset_password.php?token=" . $token;
        $emailSubject = "Password Reset";
        $emailBody = "To reset your password, click the link below:\n\n" . $resetLink;
        $headers = "From: example@example.com";

        // Send the email
        mail($email, $emailSubject, $emailBody, $headers);

        // Display a success message to the user
        echo "Password reset link has been sent to your email address, please enter and verify.";
        header("Refresh:5; url=https://localhost/www/project/html/resetPassPage2.php");
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