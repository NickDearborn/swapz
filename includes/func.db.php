<?php

function connect_db() {  //function for connecting to a PDO db.. used to have a single place with login information so I can change it.   
    //One place for Application Settings!
    //Connection stuff..
    $dsn = 'mysql:host=matdidtrek.ca;dbname=Swapz;charset=utf8';
    $db_user = 'swapzpublic';
    $db_pass = 'swapz';
    $options = array(
       PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    );
    
    try {
	$dbh = new PDO($dsn, $db_user, $db_pass, $options);
        return $dbh;
    } catch (PDOException $e){
	echo " ERROR:" . $e;
	exit;
    }
}
?>