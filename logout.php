<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//javascript
/* function GoogleSignOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
        console.log('User signed out.');
    });
}
 */

session_destroy();

header('Location: index.php');
?>
