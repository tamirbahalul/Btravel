<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Btravel</title>
    <link rel="stylesheet" type="text/css" href="../css/styleResPass2.css">
    <link rel="icon" href="../img/istockphoto-840458514-612x612.png">
</head>

<body>
    <div id="navbar">
        <div id="maindiv">
            <div id="header">
                <h1>Reset Password</h1>
            </div>
            <div id="downHeaderDiv">
                <div class="login2">
                    <form method="POST" action="api/login/resetPassword.php">
                        <input required name="username" type="text" placeholder="Username">
                        <input required name="currentPassword" type="password" placeholder="Current Password">
                        <input required name="newPassword" type="password" placeholder="New Password">
                        <input required name="newPasswordConfirm" type="password" placeholder="Confirm Password">
                        <button id="cac" type="submit" name="submit">Done</button>
                    </form>
                </div>
            </div>   
        </div>
        <div id="logo">
            <h1 class="wtext">Btravel</h1>
            <p class="wtext">find your best attraction in israel</p>
            <img id="flagimg" src="../img/israel.jpg">
        </div>    
    </div>
    


</body>

</html>