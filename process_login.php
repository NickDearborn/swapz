<?php
namespace swapz;
use Exception, PDO;

//includes..
require_once('includes/appsettings.php');
require_once('includes/func.hash_stuff.php');
require_once('includes/func.db.php');

//Cleans Post variables
$li_email = $_POST['email'];
$li_pass = $_POST['pass'];

//Check for a session, if there isn't one start one ** User doesn't have to be logged in ** that can be checked in other paces (ie. contact a user)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//Connect to DB
$dbh = connect_db();

//check if already logged in..
if (isset($_SESSION['uid']) && !empty($_SESSION['uid'])) { //check for login
    header('Location: index.php');  //ship em to index b/c already logged in and trying to access this page.. ** Could use redirecter that is being built **
}

try { //return userID, credentialspass, and companyID associated with email.. If no results then the user does not exist
  $statement = $dbh->prepare('
        SELECT 
            user.userID, credentials.credentialsPassword, company.companyID
        FROM 
            user, credentials, company
        WHERE 
            credentials.credentialsEmail = :email
        AND credentials.credentialsID = user.userID
        AND user.company_companyID = company.companyID
  ');
  $statement->execute(array(
    "email" => $li_email
  ));
  $creds = $statement->fetch();
} catch(PDOException $e) {
  echo "ERROR: " . $e;
}

//verify password is correct, and set UID if applicable
if(verify_hash($li_pass, $creds[1])) {
    //login passed
    $_SESSION['uid'] = $creds[0];
    $_SESSION['cid'] = $creds[2];
    
    header('Location: confirm.php?r=2&s=1');
} else {
    //Login failed
    header("Location: confirm.php?r=2&s=2");
}
?>