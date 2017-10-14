<?php
//Check for a session, if there isn't one start one ** User doesn't have to be logged in ** that can be checked in other paces (ie. contact a user)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!doctype html>
<html lang="en">
<head><meta charset="utf-8">
    <title>Swapz</title>
    <!--Mobile Specific Meta Tag-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!--Favicon-->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <!--Master Slider Styles-->
    <link href="masterslider/style/masterslider.css" rel="stylesheet" media="screen">
    <!--Styles-->
    <link href="css/styles.css" rel="stylesheet" media="screen">
    <!--Modernizr-->
    <script src="js/libs/modernizr.custom.js"></script>
    <!--Adding Media Queries Support for IE8-->
    <!--[if lt IE 9]>
      <script src="js/plugins/respond.js"></script>
    <![endif]-->

    <!-- tagit stuff -->
    <link href="css/tagit/jquery.tagit.css" rel="stylesheet" type="text/css">
    <link href="css/tagit/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
  </head>

  <!--Body-->
  <body>

    <!--Header-->
    <?PHP include_once('includes/header.php'); ?>
    
    <!--Page Content-->
    <div class="page-content">
    
      <!--Breadcrumbs-->
      <ol class="breadcrumb">
        <li><a href="index.html">Home</a></li>
        <li>Login/ register</li>
      </ol><!--Breadcrumbs Close-->
      
      <!--Login / Register-->
      <section class="log-reg container">
       <h2>Login/ register</h2>
       <p class="large">Use social accounts</p>
       <div class="social-login">
        <a class="facebook" href="#"><i class="fa fa-facebook-square"></i></a>
        <a class="google" href="#"><i class="fa fa-google-plus-square"></i></a>
        <a class="twitter" href="#"><i class="fa fa-twitter-square"></i></a>
        </div>
      	<div class="row">
        	<!--Login-->
        	<div class="col-lg-5 col-md-5 col-sm-5">
            <form method="post" class="login-form" action='process_login.php'>
              <div class="form-group group">
                <label for="log-email2">Email</label>
                <input type="email" class="form-control" name="email" id="log-email2" placeholder="Enter your email" required>
                <a class="help-link" href="#">Forgot email?</a>
              </div>
              <div class="form-group group">
                <label for="log-password2">Password</label>
                <input type="text" class="form-control" name="pass" id="log-password2" placeholder="Enter your password" required>
                <a class="help-link" href="#">Forgot password?</a>
              </div>
              <div class="checkbox">
                <label><input type="checkbox" name="remember"> Remember me</label>
              </div>
              <input class="btn btn-success" type="submit" value="Login">
            </form>
          </div>
          <!--Registration-->
          <div class="col-lg-7 col-md-7 col-sm-7">
            <form method="post" class="registr-form" action='process_signup.php'>
              <div class="form-group group">
                <label for="rf-email">Email</label>
                <input type="email" class="form-control" name="email" id="rf-email" placeholder="Enter email" required>
              </div>
              <div class="form-group group">
                <label for="rf-password">Password</label>
                <input type="password" class="form-control" name="pass" id="rf-password" placeholder="Enter password" required>
              </div>
              <div class="form-group group">
                <label for="rf-password-repeat">Repeat password</label>
                <input type="password" class="form-control" name="pass-repeat" id="rf-password-repeat" placeholder="Repeat password" required>
              </div>
              <div class="form-group group">
                <label for="rf-email">Company Name</label>
                <input type="text" class="form-control" name="companyName" id="rf-email" placeholder="Enter Company Name" required>
              </div>
              <div class="checkbox">
                <label><input type="checkbox" name="remember"> I have read and agree with the terms</label>
              </div>
              <input class="btn btn-success" type="submit" value="Register">
            </form>
          </div>
        </div>
      </section><!--Login / Register Close-->
      
      <!--Brands Carousel Widget-->
      <section class="brand-carousel">
      	<div class="container">
        	<h2>Brands in our shop</h2>
          <div class="inner">
          	<a class="item" href="#"><img src="img/brands/1.png" alt="1"/></a>
            <a class="item" href="#"><img src="img/brands/1.png" alt="1"/></a>
            <a class="item" href="#"><img src="img/brands/1.png" alt="1"/></a>
            <a class="item" href="#"><img src="img/brands/1.png" alt="1"/></a>
            <a class="item" href="#"><img src="img/brands/1.png" alt="1"/></a>
            <a class="item" href="#"><img src="img/brands/1.png" alt="1"/></a>
            <a class="item" href="#"><img src="img/brands/1.png" alt="1"/></a>
          </div>
        </div>
      </section><!--Brands Carousel Close-->
      
    </div><!--Page Content Close-->
    
    <!--Sticky Buttons-->
    <div class="sticky-btns">
    	<form class="quick-contact ajax-form" method="post" name="quick-contact">
      	<h3>Contact us</h3>
        <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do.</p>
        <div class="form-group">
        	<label for="qc-name">Full name</label>
          <input class="form-control input-sm" type="text" name="name" id="qc-name" placeholder="Enter full name">
        </div>
        <div class="form-group">
        	<label for="qc-email">Email</label>
          <input class="form-control input-sm" type="email" name="email" id="qc-email" placeholder="Enter email">
        </div>
        <div class="form-group">
        	<label for="qc-message">Your message</label>
          <textarea class="form-control input-sm" name="message" id="qc-message" placeholder="Enter your message"></textarea>
        </div>
        <!-- Validation Response -->
        <div class="response-holder"></div>
        <!-- Response End -->
        <input class="btn btn-success btn-sm btn-block" type="submit" value="Send">
      </form>
    	<span id="qcf-btn"><i class="fa fa-envelope"></i></span>
      <span id="scrollTop-btn"><i class="fa fa-chevron-up"></i></span>
    </div><!--Sticky Buttons Close-->
    
    <!--Subscription Widget-->
    <section class="subscr-widget">
      <div class="container">
        <div class="row">
          <div class="col-lg-7 col-md-8 col-sm-8">
            <h2 class="light-color">Subscribe to our news</h2>
            
            <!--Mail Chimp Subscription Form-->
            <form class="subscr-form" role="form" action="//8guild.us3.list-manage.com/subscribe/post?u=168a366a98d3248fbc35c0b67&amp;id=d704057a31" target="_blank" method="post" autocomplete="off">
              <div class="form-group">
                <label class="sr-only" for="subscr-name">Enter name</label>
                <input type="text" class="form-control" name="FNAME" id="subscr-name" placeholder="Enter name" required>
                <button class="subscr-next"><i class="icon-arrow-right"></i></button>
              </div>
              <div class="form-group fff" style="display: none">
                <label class="sr-only" for="subscr-email">Enter email</label>
                <input type="email" class="form-control" name="EMAIL" id="subscr-email" placeholder="Enter email" required>
                <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                <div style="position: absolute; left: -5000px;"><input type="text" name="b_168a366a98d3248fbc35c0b67_d704057a31" tabindex="-1" value=""></div>
                <button type="submit" id="subscr-submit"><i class="icon-check"></i></button>
              </div>
            </form>
            <!--Mail Chimp Subscription Form Close-->
            <p class="p-style2">Please fill the field before continuing</p>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-4 col-lg-offset-1">
            <p class="p-style3">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
          </div>
        </div>
      </div>
    </section><!--Subscription Widget Close-->
      
  	<!--Footer-->
    <footer class="footer">
    	<div class="container">
      	<div class="row">
        	<div class="col-lg-5 col-md-5 col-sm-5">
          	<div class="info">
              <a class="logo" href="index.html"><img src="img/logo.png" alt="Bushido"/></a>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>
              <div class="social">
              	<a href="#" target="_blank"><i class="fa fa-instagram"></i></a>
              	<a href="#" target="_blank"><i class="fa fa-youtube-square"></i></a>
              	<a href="#" target="_blank"><i class="fa fa-tumblr-square"></i></a>
              	<a href="#" target="_blank"><i class="fa fa-vimeo-square"></i></a>
              	<a href="#" target="_blank"><i class="fa fa-pinterest-square"></i></a>
              	<a href="#" target="_blank"><i class="fa fa-facebook-square"></i></a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-4">
          	<h2>Latest news</h2>
            <ul class="list-unstyled">
            	<li>25 May <a href="#">Nemo enim ipsam voluptatem</a></li>
            	<li>01 May <a href="#">Neque porro quisquam est</a></li>
            	<li>16 Apr <a href="#">Lorem ipsum dolor sit amet</a></li>
            	<li>10 Jan <a href="#">Sed ut perspiciatis unde</a></li>
            </ul>
          </div>
          <div class="contacts col-lg-3 col-md-3 col-sm-3">
          	<h2>Contacts</h2>
            <p class="p-style3">
            	4120 Lenox Avenue, New York, NY,<br/>
              10035 76 Saint Nicholas Avenue<br/>
              <a href="mailto:mail@bushido.com">mail@bushido.com</a><br/>
              +48 543765234<br/>
              +48 555 234 54 34<br/>
            </p>
          </div>
        </div>
        <div class="copyright">
        	<div class="row">
          	<div class="col-lg-7 col-md-7 col-sm-7">
              <p>&copy; 2014 BUSHIDO. All Rights Reserved. Designed by <a href="http://8guild.com/" target="_blank">8Guild</a></p>
            </div>
          	<div class="col-lg-5 col-md-5 col-sm-5">
            	<div class="payment">
                <img src="img/payment/visa.png" alt="Visa"/>
                <img src="img/payment/paypal.png" alt="PayPal"/>
                <img src="img/payment/master.png" alt="Master Card"/>
                <img src="img/payment/discover.png" alt="Discover"/>
                <img src="img/payment/amazon.png" alt="Amazon"/>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer><!--Footer Close-->
    
    <!--Javascript (jQuery) Libraries and Plugins-->
    <script src="js/libs/jquery.easing.min.js"></script>
		<script src="js/plugins/bootstrap.min.js"></script>
		<script src="js/plugins/smoothscroll.js"></script>
		<script src="js/plugins/jquery.validate.min.js"></script>
		<script src="js/plugins/icheck.min.js"></script>
		<script src="js/plugins/jquery.placeholder.js"></script>
		<script src="js/plugins/jquery.stellar.min.js"></script>
		<script src="js/plugins/jquery.touchSwipe.min.js"></script>
		<script src="js/plugins/jquery.shuffle.min.js"></script>
    <script src="js/plugins/lightGallery.min.js"></script>
    <script src="js/plugins/owl.carousel.min.js"></script>
    <script src="js/plugins/masterslider.min.js"></script>
    <script src="js/plugins/jquery.nouislider.min.js"></script>
    <script src="mailer/mailer.js"></script>
		<script src="js/scripts.js"></script>
    
  </body><!--Body Close-->
</html>
