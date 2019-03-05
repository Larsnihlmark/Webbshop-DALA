<?php
require_once('../../includes/user.php');

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $user = new User();
        $email = $_POST['email'];
        $pass = $_POST['password'];

        if($_POST["action"] == "registerUser") {
            $user->register($email, $pass);
        }
        if($_POST["action"] == "logIn") {            
            // Later: Match with database and authenticate
            $user->login($email, $pass);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        
    }

    if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    
    }

} catch(EXCEPTION $err) {
    echo $err;
}

?>