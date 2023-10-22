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
                    <form action="login-google.php" method="post" id="google-form">
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
                    <form id="manual-form">
                        <input type="text" id="manual-username" name="username" placeholder="User Name" required>
                        <input type="email" id="manual-email" name="email" placeholder="Email Address" required>
                        <input type="password" id="manual-password" name="password" placeholder="Password" required>
                        <button id="cac" value="createUser" type="submit">Create Account</button>
                    </form>
                </div>
                <div id="login3">
                    <p>
                        Already have an account?<a href="loginPage.php">login</a>
                    </p>
                </div>
                <p class="error-message" id="error-message"></p>
            </div>   
        </div>
        <div id="logo">
            <h1 class="wtext">Btravel</h1>
            <p class="wtext">find your best attraction in israel</p>
            <img id="flagimg" src="../img/israel.jpg">
        </div>    
    </div>
    <script src="../js/script.js"></script>
    <script>
        const manualForm = document.getElementById("manual-form");
        manualForm.addEventListener('submit', function(e) {
            e.preventDefault();
            createUser('manual');
        });

        const googleForm = document.getElementById("google-form");
        googleForm.addEventListener('submit', function(e) {
            e.preventDefault();
            createUser('google');
        });

        function onGoogleLogin(e) {
            e.preventDefault();
            createUser('google');
        }

        async function createUser(methodIn) {
            try {
                const username = document.getElementById(`${methodIn}-username`).value;
                const password = document.getElementById(`${methodIn}-password`).value;
                const email = document.getElementById(`${methodIn}-email`).value;
                const response = await fetch('api/signUp/create.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        username, password, email, method: methodIn
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    redirect: 'follow'
                });

                if (!response.ok) {
                    const jsonData = await response.json();
                    throw jsonData.error;
                }

                window.location.href = response.url;
            } catch (error) {
                const errorMessageDiv = document.getElementById("error-message");
                errorMessageDiv.innerHTML = error;
            }
        }   
    </script>
</body>

</html>