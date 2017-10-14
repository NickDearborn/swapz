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

//Connect to DB
$dbh = connect_db();

if (isset($_GET['pid']) && !empty($_GET['pid'])) { //check if a query has been sent
  //run query with user input
  try {
      $statement = $dbh->prepare('
        SELECT 
            product.productName, product.productDesc, product.productTags, image.imageReference, product.productID, company.companyName, credentials.credentialsEmail, company.companyID 
        FROM 
            product, image, company, user, credentials 
        WHERE 
            company.companyID = user.company_companyID AND user.userID = product.user_userID AND product.productID = image.product_productID AND user.userID = credentials.credentialsID AND product.productID=:pid              
      ');
      $statement->execute(array(
        "pid" => $_GET['pid']
      ));
      $results = $statement->fetch();
  } catch (PDOException $e) {
      echo "Error: " . $e;
  }
} else {
  //IF PID isn't set send the user back to previous page
  //header("Location: " . $_SESSION['previous_page']);
  header("Location: index.php");
}

$img = explode(".", $results[3]);
$imgL = $img[0] . "-LARGE." . $img[1]; //Has to be a better way...............

$cid = $results[7]; //Store CID so I can use it to query other results from the same company

if (isset( $_GET['pid']) && !empty( $_GET['pid'])) { //Query to get other products listed from this Company
  //run query with user input
  try {
      $statement = $dbh->prepare('
      SELECT 
        image.imageReference, product.productID 
      FROM 
        product, image, company 
      WHERE 
        company.companyID = user.company_companyID AND user.userID = product.user_userID AND product.productID = image.product_productID AND company.companyID=:cid AND product.productID <> :pid ORDER BY product.productID DESC LIMIT 2
            
      ');
      $statement->execute(array(
        "cid" => $cid,
				"pid" => $_GET['pid']
      ));
			$results_more_from = $statement->fetchAll();
  } catch (PDOException $e) {
      echo "Error: " . $e;
  }
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

    <script src="js/plugins/tag-it.js" type="text/javascript" charset="utf-8"></script>

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

    <!--Header-->
    <?PHP require_once('includes/header.php'); ?>
    
    <!--Page Content-->
    <div class="page-content">
    
      <!--Breadcrumbs-->
      <ol class="breadcrumb">
        <li><a href="index.html">Home</a></li>
        <li><a href="shop-filters-left-3cols.html">Shop - filters left 3 cols</a></li>
        <li>Shop - single item v1</li>
      </ol><!--Breadcrumbs Close-->
      
      <!--Shopping Cart Message-->
      <section class="cart-message">
      	<i class="fa fa-check-square"></i>
        <p class="p-style3">"Nikon" was successfully added to your cart.</p>
        <a class="btn-outlined-invert btn-success btn-sm" href="shopping-cart.html">View cart</a>
      </section><!--Shopping Cart Message Close-->
      
      <!--Catalog Single Item-->
      <section class="catalog-single">
      	<div class="container">
          <div class="row">
          
          	<!--Product Gallery-->
            <div class="col-lg-6 col-md-6">
            	<div class="prod-gal master-slider" id="prod-gal">
              	<!--Slide1-->
                <div class="ms-slide">
                    <img src="masterslider/blank.gif" data-src="uploads/large/<?PHP echo $imgL ?>" alt="Lorem ipsum"/>
                    <img class="ms-thumb" src="img/catalog/product-gallery/th_1.jpg" alt="thumb" />
                </div>
              	<!--Slide2-->
                <div class="ms-slide">
                	<img src="masterslider/blank.gif" data-src="img/catalog/product-gallery/1.jpg" alt="Lorem ipsum"/>
                  <img class="ms-thumb" src="img/catalog/product-gallery/th_1.jpg" alt="thumb" />
                </div>
              	<!--Slide3-->
                <div class="ms-slide">
                	<img src="masterslider/blank.gif" data-src="img/catalog/product-gallery/1.jpg" alt="Lorem ipsum"/>
                  <img class="ms-thumb" src="img/catalog/product-gallery/th_1.jpg" alt="thumb" />
                </div>
              	<!--Slide4-->
                <div class="ms-slide">
                	<img src="masterslider/blank.gif" data-src="img/catalog/product-gallery/1.jpg" alt="Lorem ipsum"/>
                  <img class="ms-thumb" src="img/catalog/product-gallery/th_1.jpg" alt="thumb" />
                </div>
              	<!--Slide5-->
                <div class="ms-slide">
                	<img src="masterslider/blank.gif" data-src="img/catalog/product-gallery/1.jpg" alt="Lorem ipsum"/>
                  <img class="ms-thumb" src="img/catalog/product-gallery/th_1.jpg" alt="thumb" />
                </div>
              </div>
            </div>
            
            <!--Product Description-->
            <div class="col-lg-6 col-md-6">
              <h1><?PHP echo $results[0]; ?></h1>
              <div class="rate">
                <span class="active"></span>
                <span class="active"></span>
                <span class="active"></span>
                <span></span>
                <span></span>
              </div>
              <div class="buttons group">
                <a class="btn btn-primary btn-sm" id="addItemToCart" href="contact.php?company=<?PHP echo $results[7]; ?>"><i class="icon-mail"></i>Contact Seller</a>
              </div>
              <p class="p-style2"><h3>Description:</h3></b> <?PHP echo $results[1]; ?></p>
              <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-5">
                  <h3>Tell friends</h3>
                  <div class="social-links">
                    <a href="#"><i class="fa fa-tumblr-square"></i></a>
                    <a href="#"><i class="fa fa-pinterest-square"></i></a>
                    <a href="#"><i class="fa fa-facebook-square"></i></a>
                  </div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7">
                  <h3>Tags</h3>
                  <div class="tags">
                    <ul id="readOnlyTags" style="border: 0;">
                      <?PHP 
                        $tags = explode(',', $results[2]);
                        for($i=0;$i<count($tags);$i++) {
                            echo '<li>' . $tags[$i] . '</li>';
                        }
                      ?>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="promo-labels">
                <div data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus."><span><i class="fa fa-truck"></i>Free delivery</span></div>
                <div data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus."><i class="fa fa-space-shuttle"></i>Deliver even on Mars</div>
                <div data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus."><i class="fa fa-shield"></i>Safe Buy</div>
              </div>
            </div>
          </div>
        </div>
      </section><!--Catalog Single Item Close-->
      
      <!--Tabs Widget-->
      <section class="tabs-widget">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
          <li class="active"><a href="#specs" data-toggle="tab">Tech specs</a></li>
          <li><a href="#descr" data-toggle="tab">Description</a></li>
          <li><a href="#review" data-toggle="tab">Reviews</a></li>
        </ul>
        <div class="tab-content">
        	<!--Tab1 (Tech Specs)-->
          <div class="tab-pane fade in active" id="specs">
          	<div class="container">
            	<div class="row">
                <section class="tech-specs">
                  <div class="container">
                    <div class="row">
                      <!--Column 1-->
                      <div class="col-lg-6 col-md-6 col-sm-6">
                        <!--Item-->
                        <div class="item">
                          <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-3"><i class="icon-expand"></i><span>Fit</span></div>
                            <div class="col-lg-8 col-md-8 col-sm-9"><p class="p-style2">Adjustable nosepads and durable frame fits any face.
            Extra nosepads in two sizes.</p></div>
                          </div>
                        </div>
                        <!--Item-->
                        <div class="item">
                          <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-3"><i class="icon-tv-monitor"></i><span>Display</span></div>
                            <div class="col-lg-8 col-md-8 col-sm-9"><p class="p-style2">High resolution display is the equivalent of a 25 inch high definition screen from eight feet away.</p></div>
                          </div>
                        </div>
                        <!--Item-->
                        <div class="item">
                          <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-3"><i class="icon-camera-1"></i><span>Camera</span></div>
                            <div class="col-lg-8 col-md-8 col-sm-9"><p class="p-style2">Photos - 5 MP<br/>Videos - 720p</p></div>
                          </div>
                        </div>
                        <!--Item-->
                        <div class="item">
                          <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-3"><i class="icon-headphones"></i><span>Audio</span></div>
                            <div class="col-lg-8 col-md-8 col-sm-9"><p class="p-style2">Bone Conduction Transducer</p></div>
                          </div>
                        </div>
                        <!--Item-->
                        <div class="item">
                          <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-3"><i class="icon-share"></i><span>Connectivity</span></div>
                            <div class="col-lg-8 col-md-8 col-sm-9"><p class="p-style2">Wifi - 802.11b/g<br/>Bluetooth</p></div>
                          </div>
                        </div>
                        <!--Item-->
                        <div class="item">
                          <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4"><i class="icon-accelerator"></i><span>Storage</span></div>
                            <div class="col-lg-8 col-md-8 col-sm-8"><p class="p-style2">12 GB of usable memory, synced with Google cloud storage. 16 GB Flash total.</p></div>
                          </div>
                        </div>
                      </div>
                      <!--Column 2-->
                      <div class="col-lg-6 col-md-6 col-sm-6">
                        <!--Item-->
                        <div class="item">
                          <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4"><i class="icon-expand"></i><span>Fit</span></div>
                            <div class="col-lg-8 col-md-8 col-sm-8"><p class="p-style2">Adjustable nosepads and durable frame fits any face.  Extra nosepads in two sizes.</p></div>
                          </div>
                        </div>
                        <!--Item-->
                        <div class="item">
                          <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4"><i class="icon-tv-monitor"></i><span>Display</span></div>
                            <div class="col-lg-8 col-md-8 col-sm-8"><p class="p-style2">High resolution display is the equivalent of a 25 inch high definition screen from eight feet away.</p></div>
                          </div>
                        </div>
                        <!--Item-->
                        <div class="item">
                          <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4"><i class="icon-camera-1"></i><span>Camera</span></div>
                            <div class="col-lg-8 col-md-8 col-sm-8"><p class="p-style2">Photos - 5 MP<br/>Videos - 720p</p></div>
                          </div>
                        </div>
                        <!--Item-->
                        <div class="item">
                          <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4"><i class="icon-headphones"></i><span>Audio</span></div>
                            <div class="col-lg-8 col-md-8 col-sm-8"><p class="p-style2">Bone Conduction Transducer</p></div>
                          </div>
                        </div>
                        <!--Item-->
                        <div class="item">
                          <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4"><i class="icon-share"></i><span>Connectivity</span></div>
                            <div class="col-lg-8 col-md-8 col-sm-8"><p class="p-style2">Wifi - 802.11b/g<br/>Bluetooth</p></div>
                          </div>
                        </div>
                        <!--Item-->
                        <div class="item">
                          <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4"><i class="icon-accelerator"></i><span>Storage</span></div>
                            <div class="col-lg-8 col-md-8 col-sm-8"><p class="p-style2">12 GB of usable memory, synced with Google cloud storage. 16 GB Flash total.</p></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>
              </div>
            </div>
          </div>
          
          <!--Tab2 (Description)-->
          <div class="tab-pane fade" id="descr">
          	<div class="container">
            	<div class="row">
              	<div class="col-lg-4 col-md-5 col-sm-5">
                  <img class="center-block" src="img/posts-widget/2.jpg" alt="Description"/>
                </div>
              	<div class="col-lg-8 col-md-7 col-sm-7">
                	<p class="p-style2">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore.</p>
                  <div class="row">
                  	<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                    	<h4>Unordered list</h4>
                      <ul>
                        <li>List item</li>
                        <li><a href="#">List item link</a></li>
                        <li>List item</li>
                      </ul>
                    </div>
                  	<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                    	<h4>Ordered list</h4>
                      <ol>
                        <li>List item</li>
                        <li><a href="#">List item link</a></li>
                        <li>List item</li>
                      </ol>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!--Tab3 (Reviews)-->
          <div class="tab-pane fade" id="review">
          	<div class="container">
            	<div class="row">
              	<!--Disqus Comments Plugin-->
              	<div class="col-lg-10 col-lg-offset-1">
                   <div id="disqus_thread"></div>
                    <script type="text/javascript">
                      /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                      var disqus_shortname = '8guild'; // required: replace example with your forum shortname
              
                      /* * * DON'T EDIT BELOW THIS LINE * * */
                      (function() {
                          var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                          dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                          (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                      })();
                    </script>
                    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
                    <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section><!--Tabs Widget Close-->
      
      <!--Special Offer-->
      <section class="special-offer">
      	<div class="container">
          <h2>Special offer</h2>
          <div class="row">
          	<!--Tile-->
          	<div class="col-lg-3 col-md-3 col-sm-3">
            	<div class="tile">
                <div class="price-label">715,00 $</div>
                <a href="#"><img src="img/offers/special-offer.png" alt="Special Offer"/></a>
                <div class="footer"><a href="#">The Buccaneer</a></div>
              </div>
            </div>
            <!--Plus-->
            <div class="col-lg-1 col-md-1 col-sm-1">
            	<div class="sign">+</div>
            </div>
          	<!--Tile-->
          	<div class="col-lg-3 col-md-3 col-sm-3">
            	<div class="tile">
                <div class="price-label">715,00 $</div>
                <a href="#"><img src="img/offers/special-offer.png" alt="Special Offer"/></a>
                <div class="footer"><a href="#">The Buccaneer</a></div>
              </div>
            </div>
            <!--Equal-->
            <div class="col-lg-1 col-md-1 col-sm-1">
            	<div class="sign">=</div>
            </div>
            <!--Offer-->
            <div class="col-lg-4 col-md-4 col-sm-4">
            	<div class="offer">
              	<h3 class="light-color">save</h3>
                <h4 class="text-primary">100,00 $</h4>
                <a class="btn btn-success" href="#">Buy for 1200$</a>
              </div>
            </div>
          </div>
        </div>
      </section><!--Special Offer Close-->
      
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
