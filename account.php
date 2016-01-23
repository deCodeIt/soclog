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
<script src="js/fingerprint.js"></script>

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
<script src="js/fb.js"></script>
<!-- facebook login integration script closed-->
<div class="container">
<?php
var_dump($_SESSION['regs']);
?>
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
<div class="registered-events">
<section class="registered-events">
        <ul class="registered-events">
        <li>Lorem ipsum dolor sit amet</li>
        <li>Duis aute irure dolor in reprehenderit.</li>
        <li>Excepteur sint occaecat cupidatat.</li>
        <li>Ut enim ad minim veniam.</li>
        </ul>
</section>
</div>

<script type="text/javascript">
        $("#modal_trigger").leanModal({top : 50, overlay : 0.6, closeButton: ".modal_close" });
</script>

</body>
</html>
