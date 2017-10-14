<?php
namespace swapz;
use Exception, PDO;

//Includes ----
require_once('includes/appsettings.php');
require_once('includes/func.selfurl.php');
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

//defines ----
define('RESULTS_PER_PAGE', 5); //Default Results per page, used if not user defined

//Connect to DB
$dbh = connect_db();

//Query DB
try {
      $statement = $dbh->prepare('
        SELECT
          product.productName, product.productTags, image.imageReference, product.productID, product.productCreated, product.productUpdated
        FROM 
            product, image, user, company
        WHERE
          company.companyID = user.company_companyID AND user.userID = product.user_userID AND product.productID = image.product_productID AND company.companyID = :cid AND product.productState = 1
        ORDER BY
          product.productID DESC
        ');
      $statement->execute(array(
        "cid" => $_SESSION['cid']
      ));
} catch (PDOException $e) {
      echo "Error: " . $e;
}

try {
  $results = $statement->fetchAll();
} catch (PDOException $e) {
  echo "Error: " . $e;
}

/*************** PAGING DATA The order is important! *********************/

//check and set current page
if(isset($_GET['p']) && !empty($_GET['p'])) {
  $page = $_GET['p'];
} else {
  $page = 1;
}

//check for paging info (if not there use default)
if(isset($_GET['per_page']) && !empty($_GET['per_page'])) {
  $per_page = $_GET['per_page'];
} else {
  $per_page = RESULTS_PER_PAGE;
}

//Count the total number of pages so we do not exceed
$total_pages = count($results) / $per_page;
$total_pages = ceil($total_pages);

// if current page is greater than total pages...
if ($page > $total_pages) {
   // set current page to last page
   $page = $total_pages;
} // end if

// if current page is less than first page...
if ($page < 1) {
   // set current page to first page
   $page = 1;
} // end if

//check and set offset
$offset = ($page - 1) * $per_page + 1;

//set next and previous page variables
if($page+1 > $total_pages) {
    $next_page = $page;
} else {
    $next_page = $page + 1;
}

if($page-1==0) {
    $prev_page = $page;
} else {
    $prev_page = $page - 1;
}

//check if there is a search param so we can add it to the PAGING
if(isset($_GET['search']) && !empty($_GET['search'])) {
  $search = $_GET['search'];
} else {
  $search = '';
}

/******** END OF PAGING DATA *****************/
?>

<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
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
  </head>
  
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>

  <!--Body-->
  <body>
  
  <!-- Header -->
    <?PHP require_once('includes/header.php'); ?>
    
    <!--Page Content-->
    <div class="page-content">
    
      <!--Breadcrumbs-->
      <ol class="breadcrumb">
        <li><a href="index.html">Home</a></li>
        <li>Shopping cart</li>
      </ol><!--Breadcrumbs Close-->
      
      <!--Shopping Cart-->
      <section class="shopping-cart">
      	<div class="container">
        	<div class="row">
          
          	<!--Items List-->
          	<div class="col-lg-9 col-md-9">
            	<h2 class="title">Active Products</h2>
            	<table class="items-list">
              	<tr>
                  <th>&nbsp;</th>
                  <th>Product name</th>
                  <th>Remove</th>
                  <th>Date Added</th>
                </tr>
                
                <?PHP
             
                for($i=0;$i<$per_page;$i++) {
                    $counter = ($offset+$i)-1; //since the offset works on a base of 1 we need to -1 to get to the base of 0 for the array from the database **Confusing :(

                    if(!isset($results[$counter][0]) && empty($results[$counter][0])) { //Little hack to break the loop if the data runs out... **Better way???****
                         break;
                    }

                    $imgT = explode(".", $results[$counter][2]);
                    $imgT = $imgT[0] . "-THUMB." . $imgT[1]; //Has to be a better way...............
                    
                    
                
                    echo '
                        <!--Item-->
                        <tr class="item first">
                         <td class="thumb"><a href="product_edit.php?pid=' . $results[$counter][3] . '"><img src="uploads/thumb/' . $imgT . '" alt="Lorem ipsum"/></a></td>
                         <td class="name"><a href="product_edit.php?pid=' . $results[$counter][3] . '"><i class="icon-pencil"></i> ' . $results[$counter][0] . '&nbsp;&nbsp;</a></td>
                         <td class="price">&nbsp;&nbsp;&nbsp;&nbsp;<a href="process_rem_prod.php?pid=' . $results[$counter][3] . '"><i class="icon-delete"></i></a></td>
                         <td class="price">' . $results[$counter][4] . '</td>
                        </tr>                        
                    ';                
                }
                ?>
                
              </table>
                
              <!--Pagination-->
            <ul class="pagination">
              <li class="prev-page"><a class="icon-arrow-left" href="?p=<?PHP echo $prev_page; ?>"></a></li>
                <?PHP
                 for($i=1; $i<$total_pages+1; $i++) {
                     if($i==$page) {
                            echo '<li class="active"><a href="?p=' . $i . '">' . $i . '</a></li>';
                     } else {
                            echo '<li><a href="?p=' . $i . '">' . $i . '</a></li>';
                        }
                    }
                ?>
               <li class="next-page"><a class="icon-arrow-right" href="?p=<?PHP echo $next_page; ?>"></a></li>
            </ul>
            </div>
            
            <!--Sidebar-->
            <div class="col-lg-3 col-md-3">
            	<h3>Summary</h3>
              <form class="cart-sidebar" method="post">
              	<div class="cart-totals">
                	<table>
                  	<tr>
                    	<td># Active Products</td>
                      <td class="total align-r">5</td>
                    </tr>
                    <tr>
                    	<td># Products Sold</td>
                      <td class="align-r">65</td>
                    </tr>
                  	<tr class="devider">
                    	<td>Company comments</td>
                      <td class="total align-r">link</td>
                    </tr>
                  </table>
                </div>
                <h3>Do something?</h3>
                <div class="coupon">
                	<div class="form-group">
                    <label class="sr-only" for="coupon-code">Enter coupon code</label>
                    <input type="text" class="form-control" id="coupon-code" name="coupon-code" placeholder="Enter Something!">
                  </div>
                  <input type="submit" class="btn btn-primary btn-sm btn-block" name="apply-coupon" value="Do something!">
                </div>                
              </form>
            </div>
          </div>
        </div>
      </section><!--Shopping Cart Close-->
      
      <!--Catalog Grid-->
      <section class="catalog-grid">
      	<div class="container">
        	<h2>You may also like</h2>
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
    <?PHP require_once('includes/stickybuttons.php'); ?>        
    
    <!--Subscription Widget-->
    <?PHP require_once('includes/subwidget.php'); ?>
      
    <!--Footer-->
    <?PHP require_once('includes/footer.php'); ?>
    
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
