<?php
namespace swapz;
use Exception, PDO;

//includes..
require_once('includes/func.selfurl.php');

//Check for a session, if there isn't one start one ** User doesn't have to be logged in ** that can be checked in other paces (ie. contact a user)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//Get and track current and previous pages....
$previous_page = selfurl();
$_SESSION['previous_page'] = $previous_page; //Track current page............... Incase of login...........

if (isset($_SESSION['uid']) == FALSE) { //if not logged in do something
  header('Location: register.php');
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

    <!-- The real deal -->
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
<body>
    <!-- Header -->
    <?PHP require_once('includes/header.php'); ?>    
    
    <!--Page Content-->
    <div class="page-content">
        
        <!--Breadcrumbs-->
      <ol class="breadcrumb">
        <li><a href="index.html">Home</a></li>
        <li>Product Name</li>
      </ol><!--Breadcrumbs Close-->
    
        <!--Product Form-->
      <section class="container">
      	<h2>Add product</h2>
        <div class="row">
          <!--Form-->
            <form action="process_product.php" method="POST" enctype="multipart/form-data" autocomplete="on" class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 extra-space-bottom">
          	<div class="icon icon-compass"></div>
            <p>Use quotations to input multiple word tags. (ie. "Oil Pump")</p>
            <div class="form-group">
              <label for="ot_id">Order ID</label>
              <input class="form-control" type="text" name="productName" id="ot_id" placeholder="Product Name" required>
            </div>
            <div class="form-group">
                <label for="ot_id">Product Description</label>
                <textarea class="form-control" rows="10" id="cs-message" placeholder="Product Description" name="productDesc" required></textarea>            
            </div>
            <div class="form-group">
              <label for="tags">Tags</label>
              <input class="form-control" type="text" name="productTags" id="singleFieldTags2" placeholder="Product Tags" required>
            </div>
            <div class="form-group">
              <label for="ot_email">Image</label>
              <input class="form-control" type="file" name="productImage" id="ot_email" required>
            </div>
            <input class="btn btn-success btn-block" type="submit" value="Submit">
          </form>          
        </div>
      </section><!--Product Form-->
      
    </div>
    
    <!-- Sticky Buttons -->
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
    
</body>
</html>

