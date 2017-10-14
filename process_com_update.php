<?PHP
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
    
    try { //Update Product's state to inactive (0)
     $statement = $dbh->prepare("UPDATE company, user SET company.companyName=:name, company.companyPhone=:phone, company.companyAddressLine1=:add1, company.companyAddressLine2=:add2, company.companyCity=:city, company.companyProvince=:province, company.companyCountry=:country, company.companyPostal=:postal, company.companyWebsite=:website WHERE company.companyID = user.company_companyID AND user.userID = :uid");
     $statement->execute(array(       
       "name" => $_POST['companyName'],
       "phone" => $_POST['companyPhone'],
       "add1" => $_POST['companyAddressLine1'],
       "add2" => $_POST['companyAddressLine2'],
       "city" => $_POST['companyCity'],
       "province" => $_POST['companyProvince'],
       "country" => $_POST['companyCountry'],
       "postal" => $_POST['companyPostal'],
       "website" => $_POST['companyWebsite'],
       "uid" => $_SESSION['uid']        
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