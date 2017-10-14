<?PHP
    /*
    1. check UID credentials
    2. make sure it's his product
    3. change state to 1 = deleted
    */

    namespace swapz;
    use Exception, PDO;

    //Check for a session, if there isn't one start one ** User doesn't have to be logged in ** that can be checked in other paces (ie. contact a user)
    if (session_status() == PHP_SESSION_NONE) {
       session_start();
    }

    //includes..
    require_once('includes/appsettings.php');
    require_once('includes/class.simpleImage.php');
    require_once('includes/func.db.php');

    //Connect to DB
    $dbh = connect_db();

    //Make sure that the current user owns the product being deactivated
    try {
      $statement = $dbh->prepare('
        SELECT
          product.productName
        FROM
          product
        WHERE
          user.userID = :uid & product.productID = :pid
      ');
      $statement->execute(array(
        "uid" => $_SESSION['uid'],
        "pid" => $_GET['pid']
      ));
      
      $results = $statement->fetchAll();
      
      if(!isset($results) && !empty($results)){
          //If no results send to search ************ Can change this in future ***********
          header('Location: index.php?state=666');
      }
    } catch (PDOException $e) {
      echo "Error: " . $e;
    }
    
    try { //Update Product's state to inactive (0)
     $statement = $dbh->prepare("UPDATE product SET productState = 0 WHERE productID = :pid");
     $statement->execute(array(
       "pid" => $_GET['pid']
      ));
    } catch (PDOException $e) {
      echo "Error: " . $e;
    }
    
    //echo "UPDATE 'product' SET 'productState' = 0 WHERE productID=" . $_GET['pid'];
    
    
    //if all goes swell send back
    if(isset($_SESSION['previous_page'])) { //Redirecter
        header('Location: ' . $_SESSION['previous_page']); //redirect to previous page if successful login
    } else {
        header('Location: index.php');
    }
?>