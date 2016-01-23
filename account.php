<!DOCTYPE html>
<html lang="en">
<title>Login/Register</title>
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/jquery.leanModal.min.js"></script>
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" />
<link type="text/css" rel="stylesheet" href="css/style.css" />
<link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="js/jquery.ui.touch-punch.min.js"></script>
<script>
$(document).ready(function(){
        //for registration
        $(document).on('submit', 'form#registration_form', function() {            
        $.ajax({
          method: $(this).attr('method'),
          url: $(this).attr('action'),
          data: $(this).serialize()
        })
          .done(function( msg ) {
            // console.log("YES");
            // console.log(msg);
            // console.log(JSON.parse(msg));
            data = JSON.parse(msg);
            if(data.status=='true')
            {
                $("#user_register").hide();
                $("event_pg").show();
                $(".modal_close").click();
            }

        });    
        return false;
    });
        //for event registration
        $(document).on('click', '.event-reg', function() {
                console.log('event clicked');
        var status = 0;
        if( $(this).hasClass('reg'))
        {
                status = 1;
        }
        dat={zeit_event:$(this).attr('href').substr(1),stat:status};
        console.log(dat);
        $.ajax({
          method: 'POST',
          url: 'event_reg.php',
          data: dat
        })
          .done(function( msg ) {
            console.log("YES");
            console.log(msg);
            console.log(JSON.parse(msg));
            data = JSON.parse(msg);
            if(data.status=='true')
            {
                if(data.reg==1)
                {
                        $(this).html('Registered');
                }
                else
                 {
                        $(this).html('Register');
                 }
            }

        });    
        return false;
    });


});
</script>

<body>
    <div id="div4" style="display:none;width:50px;background:transparent;height:50px;position:absolute;top:0px;right:0px">
      <img src="#" style="width:100%;border-radius:50%;height:auto" id="pPic" />
    </div>
    <script>
      
      $("#div4").draggable ({
        axis : "y"
      });

      
    </script>
<!--facebook login Integration -->
<script>

function testAPI(response){
    
    // console.log(response.authResponse.accessToken);
    // console.log(JSON.stringify(response));
    
    //getting user details
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
            $(".social_login").hide();
            $(".user_register").show();
            $(".user_register #name").val(resp.name);
            if(resp.email)
                $(".user_register #email").val(resp.email);

        });
    });
    //getting user profile pic
    FB.api('/me/picture?type=square', function(response) {
        // console.log(JSON.stringify(response));
        document.getElementById('pPic').src=response.data.url;
        $( "#pPic" ).load( response.data.url, function() {
        $('#div4').show();
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

  

</script>
<div id="status"></div>
<!-- facebook login integration script closed-->
<script>
function validateForm(){
        $(function() {
            // validate and process form here
              
                $('.error').removeClass('error');

                var field = $("input#email");
                var status=true;
                if (field.val() == "") {
                        field.addClass('error');
                        field.focus();
                        status = false;
                      }
                field = $("input#college");
                if (field.val() == "") {
                        field.addClass('error');
                        field.focus();
                        status = false;
                      }
                field = $("input#location");
                if (field.val() == "") {
                        field.addClass('error');
                        field.focus();
                        status = false;
                      }
                field = $("input#tos");
                if(!field.is(':checked'))
                {
                        field.addClass('error');
                        status=false;
                }
                //validation passed
                if(status==true)
                        $('form#registration_form').submit();
          });
}
</script>
<div class="container">
        <a id="modal_trigger" href="#modal" class="btn">Event</a>
        <a href="#robowar" class="btn event-reg">Register for Robowar</a>

        <div id="modal" class="popupContainer" style="display:none;">
                <header class="popupHeader">
                        <span class="header_title">Login</span>
                        <span class="modal_close"><i class="fa fa-times"></i></span>
                </header>
                
                <section class="popupBody">
                        <!-- Social Login -->
                        <div class="social_login">
                                <div class="">
                                        <a href="#" onclick="fbLogin()" class="social_box fb">
                                                <span class="icon"><i class="fa fa-facebook"></i></span>
                                                <span class="icon_title">Connect with Facebook</span>
                                                
                                        </a>

                                        <!-- <a href="#" class="social_box google">
                                                <span class="icon"><i class="fa fa-google-plus"></i></span>
                                                <span class="icon_title">Connect with Google</span>
                                        </a> -->
                                </div>
                        </div>
                        
                        <!-- Register Form -->
                        <div class="user_register">
                                <form id="registration_form" action="register.php" method="POST">
                                        <label>Full Name</label>
                                        <input type="text" disabled="disabled" name="name" id="name"/>
                                        <br />

                                        <label>Email Address</label>
                                        <input type="email" name="email" id="email" />
                                        <br />

                                        <label>College</label>
                                        <input type="text" name="college" id="college" />
                                        <br />

                                        <label>Location (City)</label>
                                        <input type="text" name="location" id="location" />
                                        <br />

                                        <div class="checkbox">
                                                <input id="tos" name="tos" type="checkbox" />
                                                <label for="tos">I agree to the <a href="#">ToS</a></label>
                                        </div>

                                        <div class="action_btns">
                                                <div class="one_half last"><a href="#" onclick="validateForm()" class="btn btn_red">Register</a></div>
                                        </div>
                                </form>
                        </div>

                        <!-- To display when student registers for an event -->
                        <div class="event_pg">
                                
                        </div>
                </section>
        </div>
</div>

<script type="text/javascript">
        $("#modal_trigger").leanModal({top : 50, overlay : 0.6, closeButton: ".modal_close" });
</script>

</body>
</html>
