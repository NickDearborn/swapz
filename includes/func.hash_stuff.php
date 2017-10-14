<?php   
    function get_salt() {
        $salt = uniqid(mt_rand(), true);
        
        return $salt;
    }

    function get_hash($password, array $opt = NULL) {
        if(empty($opt)) {
            $options = [
                'salt' => get_salt(), //write your own code to generate a suitable salt
                'cost' => 12 // the default cost is 10
            ];
        } else {
            $options = $opt;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT, $options);
        
        return $hash;
    }
    
   function verify_hash($password, $hash) {
        if (password_verify($password, $hash)) {
          return true;
        }
        else {
          return false;
        }
    } 
?>