// Get references to the form and form fields
const form = document.querySelector('#signup-form');
const emailField = document.querySelector('#email');
const passwordField = document.querySelector('#password');
const repeatPasswordField = document.querySelector('#repeat-password');
const googleUsername = document.querySelector("#google-username");
const googleEmail = document.querySelector("#google-email");
const googlePassword = document.querySelector("#google-password");
const socialForm = document.querySelector("#google-form");

// Regular expressions for email and password validation
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;



function handleCallbackResponse(response) {
    let decoded = jwt_decode(response.credential);
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
      const status = document.getElementById('status');
      if (status) {
        status.innerHTML = 'Please log ' +
        'into this webpage.';
      }
    }
  }


