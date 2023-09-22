<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-signin-client_id">
    <title>Btravel</title>
    <link rel="stylesheet" type="text/css" href="../css/styleSingUp.css">
    <link rel="icon" href="../img/istockphoto-840458514-612x612.png">
    <script src="https://accounts.google.com/gsi/client"></script>
   <script src="https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.min.js"></script>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>

    
</head>

<body>
    <div id="navbar">
        <div id="maindiv">
            <div id="header">
                <h1 style="margin-bottom: 0;">Create Account</h1>
                <div id="faceGoogDiv">
                    <form action="secure2.php" method="post" id="social">
                    <div id="google-div"></div>
                    <input type="hidden" id="google-username" name="username-google" value="">
                    <input type="hidden" id="google-email" name="email-google" value="">
                    <input type="hidden" id="google-password" name="password-google" value="">
                    </form>
                </div>
                <h3>-OR-</h3>
            </div>
            <div id="downHeaderDiv">
                <div class="login2">
                    <form method="POST" action="secure2.php">
                        <input type="text" id="name" name="username" placeholder="User Name">
                        <input type="email" id="eamil" name="email" placeholder="Email Address">
                        <input type="password" id="password" name="password" placeholder="Password">
                        <button id="cac" type="submit" value="createUser">Create Account</button>
                    </form>
                </div>
                <div id="login3">
                    <p>
                        Already have an account?<a href="loginPage.php">login</a>
                    </p>
                </div>
            </div>   
        </div>
        <div id="logo">
            <h1 class="wtext">Btravel</h1>
            <p class="wtext">find your best attraction in israel</p>
            <img id="flagimg" src="../img/israel.jpg">
        </div>    
    </div>
    <script src="../js/script.js"></script>
</body>

</html>