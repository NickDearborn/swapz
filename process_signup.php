<?php
//Check for a session, if there isn't one start one ** User doesn't have to be logged in ** that can be checked in other paces (ie. contact a user)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//includes..
require_once('includes/appsettings.php');
require_once('includes/func.hash_stuff.php');
require_once('includes/func.db.php');

//Cleans post variables
$rg_email = $_POST['email'];
$rg_password = $_POST['pass'];

//Connect to DB
$dbh = connect_db();

// ------------------ COMPANY INFORMATION SQL -------------------------------------------------

// Create Referral Code
function createRandomCode() { 
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    $pass = '' ; 

    while ($i <= 16) { 
        $num = rand() % 33; 
        $tmp = substr($chars, $num, 1); 
        $pass = $pass . $tmp; 
        $i++; 
    } 

    return $pass; 
} 

$referral = createRandomCode();

//build and execute company query  *** WORKING ***
try {
  $statement = $dbh->prepare("INSERT INTO company (companyName, companyReferralCode) VALUES (:name, :code)");
  $statement->execute(array(
    "name" => $_POST["companyName"],
    "code" => $referral
  ));
  $currentCompID = $dbh->lastInsertID(); // get auto incremented ID just created
} catch (PDOException $e) {
  echo "Error: " . $e;
}
// ---------------------- END OF COMPANY INFORMATION SQL -----------------------------------

try { //start by inserting a blank user into the table // This is done because login information is stored in credentials
  $statement = $dbh->prepare("INSERT INTO user (company_companyID) VALUES (:comp)");
  $statement->execute(array(
    "comp" => $currentCompID
  ));
  $currentUserID = $dbh->lastInsertID(); // get auto incremented ID just created
} catch (PDOException $e) {
  echo "ERROR: " . $e;
}

$hash = get_hash($rg_password);

//Insert credential's for user
try {
  $statement = $dbh->prepare("INSERT INTO credentials (credentials.credentialsID, credentialsEmail,credentialsPassword,credentialsIsAdmin) VALUES (:user, :email, :pass, :isadmin)");
  $statement->execute(array(
    "user" => $currentUserID,
    "email" => $rg_email,
    "pass" => $hash,
    "isadmin" => "0"    
  ));
} catch (PDOException $e) {
  echo " ERROR: " . $e;
}
// -----------------------  END OF USER INFO INSERT -----------------------------------------------//

header('Location: confirm.php?r=1'); //ship to confirm page on success
?>
