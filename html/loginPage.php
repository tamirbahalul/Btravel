
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Btravel</title>
    <link rel="stylesheet" type="text/css" href="../css/styleLogin.css">
    <link rel="icon" href="../img/istockphoto-840458514-612x612.png">
</head>

<body>
    <div id="navbar">
        <div id="maindiv">
            <div id="header">
                <h1>Login To Your Account</h1>
            </div>
            <div id="downHeaderDiv">
                <div class="login2">
                    <form name="login-form" onsubmit="return login(event)">
                        <input type="text" name="username" placeholder="User Name" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <button id="cac" type="submit" value="login1">Login</button>
                    </form>
                    <p class="error-message" id="error-message"></p>
                </div>
                <div id="login3">
                    <p>
                        Back To SignUp <a href="SignupPage.php">Click Here</a>
                        Forget Password? <a href="resetPassPage.php">Click Here</a>
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
    <script>
        async function login(e) {
            try {
                e.preventDefault();

                const username = document.querySelector('[name="username"]').value;
                const password = document.querySelector('[name="password"]').value;
                
                const response = await fetch('api/login/login.php', {
                    method: 'POST',
                    body: JSON.stringify({ username, password }),
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