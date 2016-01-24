function testAPI(response){
    
    // console.log(response.authResponse.accessToken);
    // console.log(JSON.stringify(response));
    
    //getting user details
    $('#div4').show();
    
    FB.api('/me?fields=id,email,name,gender', function(resp) {
        // console.log(JSON.stringify(resp));
        //Updating data with the server
        $.ajax({
          method: "POST",
          url: "subtask/loginUpdate.php",
          data: { accessToken:response.authResponse.accessToken, name: resp.name , prof_id: resp.id, email: resp.email, gender: resp.gender }
        })
          .done(function( msg ) {
            // console.log("DONE");
            // console.log(msg);
            data = JSON.parse(msg);
            if(data.status=='true')
            {
              //user has completed his registration before
              if(data.reg_complete==1)
              {
                console.log("Already Registered");
                $(".social_login").hide();
                $(".user_register").hide();
                $(".event_pg").show();
                $(".modal_close").click();
                updateRegisteredEvents();
              }
              else
              {
                //user is registering first time
                $(".social_login").hide();
                $(".user_register").show();
                $(".user_register #name").val(resp.name);
                if(resp.email)
                    $(".user_register #email").val(resp.email);
              }
              //update the user registeres list of events
            }

        });
    });
    //getting user profile pic
    FB.api('/me/picture?type=square', function(response) {
        // console.log(JSON.stringify(response));
        document.getElementById('pPic').src=response.data.url;
        $( "#pPic" ).load( response.data.url, function() {
        // $('#div4').show();
        });

    });
}

// This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    // console.log('statusChangeCallback');
    // console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // console.log('Logged into app and connected');
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

    FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
                // console.log('Logged in.');
                //isLoggedIn();
                testAPI(response);
        }
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
        FB.getLoginStatus(function(response) {
                if (response.status === 'connected') {
                        // console.log(JSON.stringify(response));
                        // console.log('Logged in.');
                        // testAPI(response);
                }
                else {
                        FB.login(function(response) {
                                statusChangeCallback(response);
                        // handle the response
                        }, {
                                scope: 'email', 
                                return_scopes: true
                        });
                }
        });
  }

  
