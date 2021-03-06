<?php
// require_once './subtask/fb-path.inc.php';

?>
<body>
<script>

function testAPI(response){
  console.log(response.authResponse.accessToken);
  console.log(JSON.stringify(response));
  FB.api('/me?fields=id,email,name,gender', function(response) {
    console.log(JSON.stringify(response));
    document.getElementById('status').innerHTML=JSON.stringify(response);
});
  FB.api('/me/picture?type=small', function(response) {
    console.log(JSON.stringify(response));
    document.getElementById('pPic').src=response.data.url;
});
}

// This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      console.log('Logged into app and connected');
      // Logged into your app and Facebook.
      testAPI(response);
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }

  window.fbAsyncInit = function() {
    FB.init({
      appId      : '991993504180041',
      xfbml      : true,
      version    : 'v2.5'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

  function fbLogin(){
    FB.login(function(response) {
        statusChangeCallback(response);
        // handle the response
    }, {
      scope: 'email', 
      return_scopes: true
    });
  }
  

</script>
<button onclick="fbLogin()">Log In Via Facebook</button>
<div id="status"></div>
<img src="#" id="pPic" />
</body>