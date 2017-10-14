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
    
    try { //Update Product
     $statement = $dbh->prepare("UPDATE product SET productName=:name, productDesc=:desc, productTags=:tags WHERE productID=:pid");
     $statement->execute(array(       
       "name" => $_POST['productName'],
       "desc" => $_POST['productDesc'],
       "tags" => $_POST['productTags'],
       "pid" => $_POST['pid']
      ));
    } catch (PDOException $e) {
      echo "Error: " . $e;
    }    
    
    //if all goes swell send back
    if(isset($_SESSION['previous_page'])) { //Redirecter
        header('Location: ' . $_SESSION['previous_page']); //redirect to previous page if successful login
    } else {
        header('Location: index.php');
    }    
?>