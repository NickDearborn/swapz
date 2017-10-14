<?php
namespace swapz;
use Exception, PDO;

//Includes ---
require_once('includes/appsettings.php');
require_once('includes/func.selfurl.php');
require_once('includes/func.db.php');

//Check for a session, if there isn't one start one ** User doesn't have to be logged in ** that can be checked in other paces (ie. contact a user)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//defines ----
define('RESULTS_PER_PAGE', 8); //Default Results per page, used if not user defined

//Get and track current and previous pages....
$previous_page = selfurl();
$_SESSION['previous_page'] = $previous_page; //Track current page............... Incase of login...........

//Connect to DB
$dbh = connect_db();

//Query DB
if (isset( $_GET['q']) && !empty( $_GET['q'])) { //check if a query has been sent
  //run query with user input
  try {
      $statement = $dbh->prepare('
        SELECT
          product.productName, product.productTags, image.imageReference, product.productID, company.companyID, company.companyName
        FROM
          product, image, company, user
        WHERE
          company.companyID = user.company_companyID AND user.userID = product.user_userID AND product.productID = image.product_productID AND product.productState = 1
        AND
          MATCH(product.productTags) AGAINST(:sinput)
        ORDER BY product.productID DESC
      ');
      $statement->execute(array(
        "sinput" => '%' . $_GET['q'] . '%'
      ));
  } catch (PDOException $e) {
      echo "Error: " . $e;
  }
} else {
  //run query without user input
  try {
      $statement = $dbh->prepare('
        SELECT
          product.productName, product.productTags, image.imageReference, product.productID, company.companyID, company.companyName
        FROM 
            product, image, company, user
        WHERE
          company.companyID = user.company_companyID AND user.userID = product.user_userID AND product.productID = image.product_productID AND product.productState = 1
        ORDER BY
          product.productID DESC
        ');
      $statement->execute();
  } catch (PDOException $e) {
      echo "Error: " . $e;
  }
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

if(isset($_GET['q'])) {
    $q = $_GET['q'];
} else {
    $q = null;
}

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
if(isset($_GET['q']) && !empty($_GET['q'])) {
  $search = $_GET['q'];
} else {
  $search = '';
}

/******** END OF PAGING DATA *****************/
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

    <!-- The real deal -->
    <script src="js/plugins/tag-it.js" type="text/javascript" charset="utf-8"></script>

    <meta name="google-signin-client_id" content="677862454962-g1hu9k0265dj3j3gtge0kjtlinl7rotu.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script>
        function onGoogleSignIn(googleUser) {
            // Useful data for your client-side scripts:
            var profile = googleUser.getBasicProfile();
            console.log("ID: " + profile.getId()); // Don't send this directly to your server!
            console.log('Full Name: ' + profile.getName());
            console.log('Given Name: ' + profile.getGivenName());
            console.log('Family Name: ' + profile.getFamilyName());
            console.log("Image URL: " + profile.getImageUrl());
            console.log("Email: " + profile.getEmail());

            // The ID token you need to pass to your backend:
            var id_token = googleUser.getAuthResponse().id_token;
            console.log("ID Token: " + id_token);

            //dismisses modal
            $(".login-form").submit();
            //$("#loginModal").modal('hide');
        }
    

    </script>


    <script>
        $(function(){
            var sampleTags = ['c++', 'java', 'php', 'coldfusion', 'javascript', 'asp', 'ruby', 'python', 'c', 'scala', 'groovy', 'haskell', 'perl', 'erlang', 'apl', 'cobol', 'go', 'lua'];

            //-------------------------------
            // Minimal
            //-------------------------------
            $('#myTags').tagit();

            //-------------------------------
            // Single field
            //-------------------------------
            $('#singleFieldTags').tagit({
                availableTags: sampleTags,
                // This will make Tag-it submit a single form value, as a comma-delimited field.
                singleField: true,
                singleFieldNode: $('#mySingleField')
            });

            // singleFieldTags2 is an INPUT element, rather than a UL as in the other 
            // examples, so it automatically defaults to singleField.
            $('#singleFieldTags2').tagit({
                availableTags: sampleTags
            });

            //-------------------------------
            // Preloading data in markup
            //-------------------------------
            $('#myULTags').tagit({
                availableTags: sampleTags, // this param is of course optional. it's for autocomplete.
                // configure the name of the input field (will be submitted with form), default: item[tags]
                itemName: 'item',
                fieldName: 'tags'
            });

            //-------------------------------
            // Tag events
            //-------------------------------
            var eventTags = $('#eventTags');

            var addEvent = function(text) {
                $('#events_container').append(text + '<br>');
            };

            eventTags.tagit({
                availableTags: sampleTags,
                beforeTagAdded: function(evt, ui) {
                    if (!ui.duringInitialization) {
                        addEvent('beforeTagAdded: ' + eventTags.tagit('tagLabel', ui.tag));
                    }
                },
                afterTagAdded: function(evt, ui) {
                    if (!ui.duringInitialization) {
                        addEvent('afterTagAdded: ' + eventTags.tagit('tagLabel', ui.tag));
                    }
                },
                beforeTagRemoved: function(evt, ui) {
                    addEvent('beforeTagRemoved: ' + eventTags.tagit('tagLabel', ui.tag));
                },
                afterTagRemoved: function(evt, ui) {
                    addEvent('afterTagRemoved: ' + eventTags.tagit('tagLabel', ui.tag));
                },
                onTagClicked: function(evt, ui) {
                    addEvent('onTagClicked: ' + eventTags.tagit('tagLabel', ui.tag));
                },
                onTagExists: function(evt, ui) {
                    addEvent('onTagExists: ' + eventTags.tagit('tagLabel', ui.existingTag));
                }
            });

            //-------------------------------
            // Read-only
            //-------------------------------
            $('#readOnlyTags').tagit({
                readOnly: true
            });

            //-------------------------------
            // Tag-it methods
            //-------------------------------
            $('#methodTags').tagit({
                availableTags: sampleTags
            });

            //-------------------------------
            // Allow spaces without quotes.
            //-------------------------------
            $('#allowSpacesTags').tagit({
                availableTags: sampleTags,
                allowSpaces: true
            });

            //-------------------------------
            // Remove confirmation
            //-------------------------------
            $('#removeConfirmationTags').tagit({
                availableTags: sampleTags,
                removeConfirmation: true
            });
            
        });
    </script>
    <!-- end of tagit stuff -->

</head>

  <!--Body-->
  <body>

    <!-- Header -->
    <?PHP require_once('includes/header.php'); ?>
    
    <!--Page Content-->
    <div class="page-content">
    	
      <!--Breadcrumbs-->
      <ol class="breadcrumb">
        <li><a href="index.php">Home</a></li>
        <li>Search</li>
      </ol>
      <!--Breadcrumbs Close-->
      
      <!--Catalog Grid-->
      <section class="catalog-grid">
      	<div class="container">
          <h2 class="with-sorting">Search Page</h2>
          <div class="sorting">
            <a href="#">Sort by name</a>
            <a href="#">Sort by price</a>
          </div>
     
          <div class="row">
              
            <?PHP
             
            for($i=0;$i<$per_page;$i++) {
                $counter = ($offset+$i)-1; //since the offset works on a base of 1 we need to -1 to get to the base of 0 for the array from the database **Confusing :(

                if(!isset($results[$counter][0]) && empty($results[$counter][0])) { //Little hack to break the loop if the data runs out... **Better way???****
                  break;
                }

                $imgM = explode(".", $results[$counter][2]);
                $imgM = $imgM[0] . "-MID." . $imgM[1]; //Has to be a better way...............
                
                echo '
                  <!--Tile-->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="tile">
                    <div class="badges">
                	<!-- <span class="sale"></span> -->
                    </div>
                    <div class="price-label">New</div>
                    <a href="details.php?pid=' . $results[$counter][3] . '"><img src="uploads/mid/' . $imgM . '" alt="1"/></a>
                    <div class="footer">
                      <a href="details.php?pid=' . $results[$counter][3] . '">' . $results[$counter][0] . '</a>
                     <span><b>by ' . $results[$counter][5]  . '</b>
                    </span>
                     <div class="tools">
                  	<div class="rate">
                    	<span class="active"></span>
                      <span class="active"></span>
                      <span class="active"></span>
                      <span></span>
                      <span></span>
                    </div>
                    <!--Add To Cart Button-->
                    <a class="add-cart-btn" href="contact.php?company=' . $results[$counter][4] . '"><span>Contact</span><i class="icon-mail"></i></a>                    
                     </div>
                    </div>
                    </div>
                    </div>
                ';
            }
            ?>
              
          </div>
          <!--Pagination-->
          <ul class="pagination">              
            <?PHP
                echo '<li class="prev-page"><a class="icon-arrow-left" href="?q=' . $q . '&p=' . $prev_page . '"></a></li>';
            
                for($i=1; $i<$total_pages+1; $i++) {
                    if($i==$page) {
                        echo '<li class="active"><a href="?p=' . $i . '">' . $i . '</a></li>';
                    } else {
                        echo '<li><a href="?p=' . $i . '">' . $i . '</a></li>';
                    }
                }
                
                echo '<li class="next-page"><a class="icon-arrow-right" href="?q=' . $q . '&p=' . $next_page . '"></a></li>';
            ?>              
          </ul>
        </div>
      </section><!--Catalog Grid Close-->
      
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
        <script src="mailer/mailer.js"></script>
	<script src="js/scripts.js"></script>
    
  </body><!--Body Close-->
</html>
