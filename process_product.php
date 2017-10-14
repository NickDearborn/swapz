<?php
namespace swapz;
use Exception, PDO;

//includes..
require_once('includes/appsettings.php');
require_once('includes/class.simpleImage.php');
require_once('includes/func.db.php');

//Check for a session, if there isn't one start one ** User doesn't have to be logged in ** that can be checked in other paces (ie. contact a user)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//Connect to DB
$dbh = connect_db();

//Move new uploaded file to proper locations
try {
  // Does the image exist?
  if(!is_uploaded_file($_FILES['productImage']['tmp_name'])) {
    throw new Exception('File unable to be processed[0]');
  }

  // Check file size
  if ($_FILES["productImage"]["size"] > 2300000) {
    throw new Exception('File size too large');
  }

  // Check file type to make sure it's an image
  $ext = strtolower(pathinfo($_FILES['productImage']['name'], PATHINFO_EXTENSION)); //Get file type of uploaded file
  if($ext != "jpg" && $ext != "png" && $ext != "jpeg" && $ext != "gif" && $ext != "bmp" ) { //Limit File Types to known types
    throw new Exception('Only JPG, JPEG, PNG, BMP & GIF file types are allowed.');
  }

  //Create a new file names to avoid DUPLICATES
  $currentDateTime = date("YmdHis"); //create date in this variable so it doesn't change if there is a delay in the script
  $newImageName =  $currentDateTime . $_SESSION['uid'] . $_SESSION['cid'] . "." . $ext;
  $newImageThumbName = $currentDateTime . $_SESSION['uid'] . $_SESSION['cid'] . "-THUMB." . $ext;
  $newImageMidName = $currentDateTime . $_SESSION['uid'] . $_SESSION['cid'] . "-MID." . $ext;
  $newImageLargeName = $currentDateTime . $_SESSION['uid'] . $_SESSION['cid'] . "-LARGE." . $ext;

  $realpath = realpath(UPLOADED_IMAGE_TMP_LOC); //Get real path from server storage
  $realpath = $realpath . '/' . $newImageName; //create full path

  //move file to the applications tmp directory to processing
  if(!move_uploaded_file($_FILES['productImage']['tmp_name'], UPLOADED_IMAGE_TMP_LOC . $newImageName)) {
    throw new Exception('File unable to be processed[1]');
  }

  $image = new SimpleImage();
  $image->fromFile(UPLOADED_IMAGE_TMP_LOC . $newImageName)->autoOrient()->resize(IMAGE_LARGE_WIDTH, IMAGE_LARGE_HEIGHT)->toFile(UPLOADED_IMAGE_LARGE_LOC . $newImageLargeName);
  $image->fromFile(UPLOADED_IMAGE_TMP_LOC . $newImageName)->autoOrient()->resize(IMAGE_MID_WIDTH,IMAGE_MID_HEIGHT)->toFile(UPLOADED_IMAGE_MID_LOC . $newImageMidName);
  $image->fromFile(UPLOADED_IMAGE_TMP_LOC . $newImageName)->autoOrient()->resize(IMAGE_THUMB_WIDTH, IMAGE_THUMB_HEIGHT)->toFile(UPLOADED_IMAGE_THUMB_LOC . $newImageThumbName);

  if(!unlink($realpath)) { // DELETE THE ORIGINAL
    throw new Exception('Unable to delete original file');
  }
} catch (Exception $e) {
  //echo "Error: " . $e->getMessage();
  exit("Error: $e"); //Kill execution and print error for tester
}

//Insert new record into Products Table
try { //Insert New Product Information
  $statement = $dbh->prepare("INSERT INTO product (productName,productDesc,productTags,user_userID) VALUES (:name, :desc, :tags, :uid)");
  $statement->execute(array(
    "name" => $_POST["productName"],
    "desc" => $_POST["productDesc"],
    "tags" => $_POST["productTags"],
    "uid" => $_SESSION["uid"]
  ));
  //debug
  //echo 'INSERT INTO product (productName,productDesc,productTags,productCreated,productedUpdated,company_companyID,user_userID) VALUES (' . $_POST["productName"] . ',' . $_POST["productDesc"] . ',' . $_POST["productTags"] . ', NULL, NULL,' . $_SESSION["cid"] . ',' . $_SESSION["uid"] . ')';
  $currentProductID = $dbh->lastInsertID();  //return newly created ID
} catch (PDOException $e) {
  echo "Error: " . $e;
}

try { //Insert image references into the DB
  $statement = $dbh->prepare("INSERT INTO image (imageReference,product_productID) VALUES (:ref, :pid)");
  $statement->execute(array(
    "ref" => $newImageName,
    "pid" => $currentProductID
  ));
} catch (PDOException $e) {
  echo "ERROR: " . $e;
}

header('Location: confirm.php?r=3&p=' . $currentProductID . '&e=' . $e);
?>
