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

if (!isset($_SESSION['uid']) && empty($_SESSION['uid'])) { //check for login
    header('Location: register.php');  //ship em to the login page
}

//Connect to DB
$dbh = connect_db();

//Query DB
try {
      $statement = $dbh->prepare('
                SELECT 
                    company.companyName, company.companyPhone, company.companyAddressLine1, company.companyAddressLine2, company.companyCity, company.companyProvince, company.companyCountry, company.companyPostal, companyWebsite 
                FROM 
                    company, user 
                WHERE 
                    company.companyID = user.company_companyID AND user.userID = :uid
          ');
      $statement->execute(array(
        "uid" => $_SESSION['uid']
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
        <li>Account: Personal info</li>
      </ol><!--Breadcrumbs Close-->
      
      <!--Account Personal Info-->
      <section>
      	<div class="container">
        	<div class="row space-top">
          
          	<!--Items List-->
          	<div class="col-sm-8 space-bottom">            	
                <h3>Company information</h3>
                <p>This information will be seen by other distribution attempting to contact you.  <br /><br /><font style="color: red">* signifies fields that are required to be filled in.</font></p>
                <div class="row">
                    <form class="col-md-12 personal-info" method="post" action="process_com_update.php" enctype="multipart/form-data" autocomplete="on">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="api_first_name">Company Name *</label>
                            <input type="text" class="form-control" name="companyName" id="api_first_name" placeholder="Company Name"  value="<?PHP echo $results[0]; ?>" required>
                        </div>    
                        <div class="form-group col-sm-6">
                            <label for="api_first_name">Website</label>
                            <input type="text" class="form-control" name="companyWebsite" id="api_first_name" placeholder="Website"  value="<?PHP echo $results[8]; ?>" required>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="api_last_name">Phone</label>
                            <input type="text" class="form-control" name="companyPhone" id="api_last_name" placeholder="Phone Number"  value="<?PHP echo format_usphone($results[1]); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="api_first_name">Address *</label>
                            <input type="text" class="form-control" name="companyAddressLine1" id="api_first_name" placeholder="Address Line 1" value="<?PHP echo $results[2]; ?>" required>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="api_last_name">Address 2</label>
                            <input type="text" class="form-control" name="companyAddressLine2" id="api_last_name" placeholder="Address Line 2" value="<?PHP echo $results[3]; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="api_first_name">City *</label>
                            <input type="text" class="form-control" name="companyCity" id="api_first_name" placeholder="City" value="<?PHP echo $results[4]; ?>" required>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="api_last_name">Province/State *</label>
                            <input type="text" class="form-control" name="companyProvince" id="api_last_name" placeholder="Province/State" value="<?PHP echo $results[5]; ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="api_first_name">Country *</label>
                            <input type="text" class="form-control" name="companyCountry" id="api_first_name" placeholder="Country" value="<?PHP echo $results[6]; ?>" required>
                        </div>
                            <div class="form-group col-sm-6">
                            <label for="api_last_name">Postal *</label>
                            <input type="text" class="form-control" name="companyPostal" id="api_last_name" placeholder="Postal Code" value="<?PHP echo $results[7]; ?>" required>
                        </div>
                    </div>
                  <div class="form-group">
                    <div class="checkbox custom">
                      <label>
                        <input type="checkbox">Sign up for our newsletter!
                      </label>
                    </div>
                    <div class="checkbox custom">
                      <label>
                        <input type="checkbox">Receive special offers from our us.
                      </label>
                    </div>
                  </div>
                  <input type="submit" class="btn btn-success" value="Save changes">
                </form>
              </div>
            </div>
            
            <!--Sidebar-->
            <div class="col-lg-3 col-lg-offset-1 col-sm-4">
            	<h3>Your order</h3>
              <div class="checkout">
                <table>
                  <tr><th>Product</th></tr>
                  <tr>
                    <td class="name border">Nikon D4X<span>x1</span></td>
                    <td class="price border">2715,00 $</td>
                  </tr>
                  <tr>
                    <td class="th">Cart subtotal</td>
                    <td class="price">2715,00 $</td>
                  </tr>
                  <tr>
                    <td class="th border">Shipping</td>
                    <td class="align-r border">Free shipping</td>
                  </tr>
                  <tr>
                    <td class="th">Order total</td>
                    <td class="price">2715,00 $</td>
                  </tr>
                </table>
                <a class="btn btn-success btn-block space-top" href="checkout.html">Place order</a>
              </div>
            </div>
          </div>
        </div>
      </section><!--Account Personal Info Close-->
      
      <!--Catalog Grid-->
      <section class="catalog-grid">
      	<div class="container">
        	<h2 class="primary-color">Recently viewed</h2>
          <div class="row">
            <!--Tile-->
          	<div class="col-lg-3 col-md-4 col-sm-6">
            	<div class="tile">
              	<div class="price-label">715,00 $</div>
              	<a href="#"><img src="img/catalog/1.png" alt="1"/></a>
                <div class="footer">
                	<a href="#">The Buccaneer</a>
                  <span>by Pirate3d</span>
                  <div class="tools">
                  	<div class="rate">
                    	<span class="active"></span>
                      <span class="active"></span>
                      <span class="active"></span>
                      <span class="active"></span>
                      <span></span>
                    </div>
                    <!--Add To Cart Button-->
                    <a class="add-cart-btn" href="#"><span>To cart</span><i class="icon-shopping-cart"></i></a>
                    <!--Share Button-->
                    <div class="share-btn">
                    	<div class="hover-state">
                      	<a class="fa fa-facebook-square" href="#"></a>
                        <a class="fa fa-twitter-square" href="#"></a>
                        <a class="fa fa-google-plus-square" href="#"></a>
                      </div>
                      <i class="fa fa-share"></i>
                    </div>
                    <!--Add To Wishlist Button-->
                    <a class="wishlist-btn" href="#">
                    	<div class="hover-state">Wishlist</div>
                    	<i class="fa fa-plus"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          	<!--Tile-->
          	<div class="col-lg-3 col-md-4 col-sm-6">
            	<div class="tile">
              	<div class="price-label">715,00 $</div>
              	<a href="#"><img src="img/catalog/1.png" alt="1"/></a>
                <div class="footer">
                	<a href="#">The Buccaneer</a>
                  <span>by Pirate3d</span>
                  <div class="tools">
                  	<div class="rate">
                    	<span class="active"></span>
                      <span class="active"></span>
                      <span class="active"></span>
                      <span></span>
                      <span></span>
                    </div>
                    <!--Add To Cart Button-->
                    <a class="add-cart-btn" href="#"><span>To cart</span><i class="icon-shopping-cart"></i></a>
                    <!--Share Button-->
                    <div class="share-btn">
                    	<div class="hover-state">
                      	<a class="fa fa-facebook-square" href="#"></a>
                        <a class="fa fa-twitter-square" href="#"></a>
                        <a class="fa fa-google-plus-square" href="#"></a>
                      </div>
                      <i class="fa fa-share"></i>
                    </div>
                    <!--Add To Wishlist Button-->
                    <a class="wishlist-btn" href="#">
                    	<div class="hover-state">Wishlist</div>
                    	<i class="fa fa-plus"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
            <!--Tile-->
          	<div class="col-lg-3 col-md-4 col-sm-6">
            	<div class="tile">
              	<div class="price-label">715,00 $</div>
              	<a href="#"><img src="img/catalog/1.png" alt="1"/></a>
                <div class="footer">
                	<a href="#">The Buccaneer</a>
                  <span>by Pirate3d</span>
                  <div class="tools">
                  	<div class="rate">
                    	<span class="active"></span>
                      <span class="active"></span>
                      <span></span>
                      <span></span>
                      <span></span>
                    </div>
                    <!--Add To Cart Button-->
                    <a class="add-cart-btn" href="#"><span>To cart</span><i class="icon-shopping-cart"></i></a>
                    <!--Share Button-->
                    <div class="share-btn">
                    	<div class="hover-state">
                      	<a class="fa fa-facebook-square" href="#"></a>
                        <a class="fa fa-twitter-square" href="#"></a>
                        <a class="fa fa-google-plus-square" href="#"></a>
                      </div>
                      <i class="fa fa-share"></i>
                    </div>
                    <!--Add To Wishlist Button-->
                    <a class="wishlist-btn" href="#">
                    	<div class="hover-state">Wishlist</div>
                    	<i class="fa fa-plus"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
            <!--Tile-->
          	<div class="col-lg-3 col-md-4 col-sm-6">
            	<div class="tile">
              	<div class="price-label">715,00 $</div>
              	<a href="#"><img src="img/catalog/1.png" alt="1"/></a>
                <div class="footer">
                	<a href="#">The Buccaneer</a>
                  <span>by Pirate3d</span>
                  <div class="tools">
                  	<div class="rate">
                    	<span class="active"></span>
                      <span class="active"></span>
                      <span class="active"></span>
                      <span></span>
                      <span></span>
                    </div>
                    <!--Add To Cart Button-->
                    <a class="add-cart-btn" href="#"><span>To cart</span><i class="icon-shopping-cart"></i></a>
                    <!--Share Button-->
                    <div class="share-btn">
                    	<div class="hover-state">
                      	<a class="fa fa-facebook-square" href="#"></a>
                        <a class="fa fa-twitter-square" href="#"></a>
                        <a class="fa fa-google-plus-square" href="#"></a>
                      </div>
                      <i class="fa fa-share"></i>
                    </div>
                    <!--Add To Wishlist Button-->
                    <a class="wishlist-btn" href="#">
                    	<div class="hover-state">Wishlist</div>
                    	<i class="fa fa-plus"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section><!--Catalog Grid Close-->
      
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
