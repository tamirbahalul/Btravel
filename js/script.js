// Get references to the form and form fields
const form = document.querySelector('#signup-form');
const emailField = document.querySelector('#email');
const passwordField = document.querySelector('#password');
const repeatPasswordField = document.querySelector('#repeat-password');
const googleUsername = document.querySelector("#google-username");
const googleEmail = document.querySelector("#google-email");
const googlePassword = document.querySelector("#google-password");
const socialForm = document.querySelector("#social");

// Regular expressions for email and password validation
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;



function handleCallbackResponse(response) {
    let decoded = jwt_decode(response.credential);
    console.log(decoded);
    googleUsername.value=decoded.given_name+decoded.family_name;
    googleEmail.value=decoded.email;
    googlePassword.value="from google";
    socialForm.submit();
}


/* global google */
google.accounts.id.initialize({
    client_id:
      "664906134638-vnugbovb85trfcj9ogg7kq1a77imfc2e.apps.googleusercontent.com",
    callback: handleCallbackResponse,
  });

  google.accounts.id.renderButton(document.getElementById("google-div"), {
    theme: "outline",
    size: "medium",
  });

  function statusChangeCallback(response) {  // Called with the results from FB.getLoginStatus().
    console.log('statusChangeCallback');
    console.log(response);                   // The current login status of the person.
    if (response.status === 'connected') {   // Logged into your webpage and Facebook.
      testAPI();  
    } else {                                 // Not logged into your webpage or we are unable to tell.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this webpage.';
    }
  }


  function checkLoginState() {               // Called when a person is finished with the Login Button.
    FB.getLoginStatus(function(response) {   // See the onlogin handler
      statusChangeCallback(response);
    });
  }


  window.fbAsyncInit = function() {
    FB.init({
      appId      : '618298576692234',
      cookie     : true,                     // Enable cookies to allow the server to access the session.
      xfbml      : true,                     // Parse social plugins on this webpage.
      version    : 'v17.0'           // Use this Graph API version for this call.
    });


    FB.getLoginStatus(function(response) {   // Called after the JS SDK has been initialized.
      statusChangeCallback(response);        // Returns the login status.
    });
  };
 
  function testAPI() {                      // Testing Graph API after login.  See statusChangeCallback() for when this call is made.
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Successful login for: ' + response.name);
      document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + '!';
        //location.replace("HomePage.php");
    });
  }



  FB.login(function(response) {
    if (response.authResponse) {
      console.log('Welcome!  Fetching your information.... ');
      FB.api('/me', function(response) {
        console.log('Good to see you, ' + response.name + '.');
      });
    } else {
      console.log('User cancelled login or did not fully authorize.');
    }
  },{scope:'email,user_likes'});
