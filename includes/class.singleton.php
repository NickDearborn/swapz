<?php

class singletonc {
    private static $_instance = null;
    
    private function __construct () {}
    
    private function __clone (){}
    
    public static function getInstance() {
        if(!is_object(self::$_instance)) {
            self::$_instance = new singletonc();
            return self::$_instance;
        }
    }
    
    public function greet() {
        echo 'yo';
    }
}

$obj1 = singletonc::getInstance();
$obj2 = singletonc::getInstance();
$obj3 = singletonc::getInstance();

$obj1->greet();
$obj2->greet();
$obj3->greet();

?>