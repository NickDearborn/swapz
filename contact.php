<?php
namespace swapz;
use Exception, PDO;

//Includes ----
require_once('includes/appsettings.php');
require_once('includes/func.selfurl.php');
require_once('includes/func.format.php');
require_once('includes/func.db.php');

//Check for a session, if there isn't one start one ** User doesn't have to be logged in ** that can be checked in other paces (ie. contact a user)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//Get and track current and previous pages....
$previous_page = selfurl();
$_SESSION['previous_page'] = $previous_page; //Track current page............... Incase of login...........

//Connect to DB
$dbh = connect_db();

  try {
      $statement = $dbh->prepare('
          SELECT 
            company.companyName, company.companyPhone, company.companyAddressLine1, company.companyAddressLine2, company.companyCity, company.companyProvince, company.companyCountry, company.companyPostal, company.companyWebsite, credentials.credentialsEmail 
          FROM 
            company, user, credentials 
          WHERE 
            company.companyID = user.company_companyID AND user.userID = credentials.credentialsID AND company.companyID = :cid
          ');
      $statement->execute(array(
        "cid" => $_GET['company']
      ));
      $results = $statement->fetch();
  } catch (PDOException $e) {
      echo "Error: " . $e;
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

  <body>
    <!-- Header -->
    <?PHP require_once('includes/header.php'); ?>
    
    <!--Page Content-->
    <div class="page-content">
    
      <!--Breadcrumbs-->
      <ol class="breadcrumb">
        <li><a href="index.html">Home</a></li>
        <li>Contacts</li>
      </ol><!--Breadcrumbs Close-->
      
      <div class="container"><h2>Contacts</h2></div>
      
      <!--Google Map-->
      <section class="map container">
      	<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d11365119.183662498!2d26.671722792891288!3d45.93754926598604!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1403521777633" width="800" height="400" style="border:0"></iframe>
      </section><!--Google Map Close-->
      
      <!--Contacts-->
      <section class="container">
      	<div class="row">
          <!--Contact Info-->
        	<div class="col-lg-5 col-lg-offset-1 col-md-5 col-sm-5">
            <h3>Contact info</h3>
            <div class="cont-info-widget">
            <ul>
              <li><?PHP echo $results[0]; ?></li>
              <li><i class="fa fa-building"></i><?PHP echo $results[2]; ?>, <?PHP echo $results[4]; ?>,<br/><?PHP echo $results[7]; ?></li>
              <li><a href="mailto:<?PHP echo $results[9]; ?>"><i class="fa fa-envelope"></i><?PHP echo $results[9]; ?></a></li>
              <li><a href="http://<?PHP echo $results[8]; ?>"><i class="fa fa-support"></i>website</a></li>
              <li><i class="fa fa-phone"></i><?PHP echo format_usphone($results[1]); ?></li>
              <li><i class="fa fa-mobile"></i>+48 555 234 54 34</li>
            </ul>
            </div>
          </div>
        	<div class="col-lg-5 col-md-7 col-sm-7">
          	<h3>Drop us a line</h3>
          	<form class="contact-form" method="post">
            	<div class="form-group">
              	<label class="sr-only" for="cf-name">Name</label>
              	<input type="text" class="form-control" name="cf-name" id="cf-name" placeholder="Enter your name" required>
              </div>
            	<div class="form-group">
              	<label class="sr-only" for="cf-email">Email</label>
              	<input type="email" class="form-control" name="cf-email" id="cf-email" placeholder="Enter email" required>
              </div>
            	<div class="form-group">
              	<label class="sr-only" for="cf-message">Message</label>
                <textarea class="form-control" name="cf-message" id="cf-message" rows="5" placeholder="Your message" required></textarea>
              </div>
              <input class="btn btn-primary" type="submit" value="Send message">
            </form>
          </div>
        </div>
      </section><!--Contacts Close-->
      
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
    
    <!-- Sticky Buttons -->
    <?PHP require_once('includes/stickybuttons.php'); ?>
    
    <!--Subscription Widget-->
    <?PHP require_once('includes/subwidget.php'); ?>
    
    <!--Footer-->
    <?PHP require_once('includes/footer.php'); ?>
    
    <!--Javascript (jQuery) Libraries and Plugins-->
		<script src="js/libs/jquery-1.11.1.min.js"></script>
		<script src="js/libs/jquery-ui-1.10.4.custom.min.js"></script>
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
