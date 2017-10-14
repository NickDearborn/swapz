<?PHP
    //Check for a session, if there isn't one start one ** User doesn't have to be logged in ** that can be checked in other paces (ie. contact a user)
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['previous_page']) && empty($_SESSION['previous_page'])) {
       $_SESSION['previous_page'] = 'index.php';
    }

    if(!isset($_GET['r']) && empty($_GET['r'])) { //check if variable is set or else ship them away ** Find if there is a better way to verify requesting page..
        header('Location: ' . $_SESSION['previous_page']);
    }
    
    $r = $_GET['r'];
    
    if(isset($_GET['e']) && !empty($_GET['e'])) {
        $e = $_GET['e'];
    }
    if(isset($_GET['s']) && !empty($_GET['s'])) {
        $s = $_GET['s']; //state of request (ie. success/fail)
    }       
    if(isset($_GET['p']) && !empty($_GET['p'])) {
        $p = $_GET['p']; //state of request (ie. success/fail)
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
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
  </head>

  <body>
    
    <!--Content-->
    <div class="coming-soon">
    	<section class="container">
      	
        <!--Social Bar-->
        <div class="social-bar">
          <a href="#" target="_blank"><i class="fa fa-tumblr-square"></i></a>
          <a href="#" target="_blank"><i class="fa fa-youtube"></i></a>
          <a href="#" target="_blank"><i class="fa fa-linkedin"></i></a>
          <a href="#" target="_blank"><i class="fa fa-pinterest"></i></a>
          <a href="#" target="_blank"><i class="fa fa-facebook"></i></a>
          <a href="#" target="_blank"><i class="fa fa-twitter"></i></a>
        </div>
        
        <!--Logo-->
        <a class="logo" href="index.html"><img src="img/logo-dark.png" alt="Bushido"/></a>
        
        <!--Text-->
        <?PHP 
            switch($r) {
                case 1: {
                    echo '
                        <h1 class="text-center">Registration Successful!</h1>
                        <h3 class="text-center"><a href="register.php">Please Login Here</a></h3>
                    ';
                    break;
                }
                case 2: {
                    switch ($s) {
                        case 1: {
                           echo '
                            <h1 class="text-center">Login Successful!</h1>
                            <h3 class="text-center"><a href="' . $_SESSION['previous_page'] . '">Continue to site</a></h3>
                        ';  
                        break;
                        }
                        
                        case 2: {
                            echo '
                            <h1 class="text-center">Login Failed!</h1>
                            <h3 class="text-center"><a href="register.php">Please Try again</a></h3>
                        ';
                        break;
                        }
                    }
                    break;
                }
                case 3: {
                    echo '
                        <h1 class="text-center">Upload Successful!</h1>
                        <h3 class="text-center"><a href="product.php">Upload Another</a> / <a href="index.php">Return to Site</a> / <a href="product_edit.php?pid=' . $p . '">View My Product</a></h3>
                    ';
                    break;
                }
            }
        ?>               
      </section>      
    
    </div><!--Content Close-->
    
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
    
    <!--Javascript (jQuery) Libraries and Plugins-->
		<script src="js/libs/jquery-1.11.1.min.js"></script>
		<script src="js/libs/jquery-ui-1.10.4.custom.min.js"></script>
		<script src="js/plugins/jquery.countdown.min.js"></script>
		<script src="js/plugins/jquery.validate.min.js"></script>
		<script src="js/plugins/jquery.placeholder.js"></script>
		<script src="js/plugins/smoothscroll.js"></script>
		<script src="js/coming-soon.js"></script>
    
  </body><!--Body Close-->
</html>