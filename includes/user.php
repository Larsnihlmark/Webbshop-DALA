<?php
require_once('database.php');

class User {
    private $connection;
    private $database;

    function __construct() {
        $this->database = new Database();
        $this->connection = $this->database->connect();
        
    }


    function register($username, $email, $password) {
        try {
            $sql = "SELECT COUNT(Email) AS num FROM account WHERE Email = :email";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':email', $email);
            $statement->execute();

            $emailExists = $statement->fetch(PDO::FETCH_ASSOC);

            if($emailExists['num'] > 0) {
                $message['status'] = 'error'; 
                $message['failMessage'] = 'Det finns redan ett konto med denna email!'; 
                header('Content-type: application/json');
                echo json_encode($message);  
            } else {
                // save user with prepare statenents
                $statement = $this->connection->prepare("INSERT INTO account (Username, Email, Password) VALUES ( :username, :email, :pass)");
                $statement->bindParam(':username', $username);
                $statement->bindParam(':email', $email);
                $statement->bindParam(':pass', $password);
                $statement->execute();
            }
        } catch (EXCEPTION $err) {
            throw new Exception($err);
        }
    }

    function login($username, $password) {
        try {
            $sql = "SELECT COUNT(Username) as num, Password FROM account WHERE Username = :username";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':username', $username);
            $statement->execute();

            $fetchPass = $statement->fetch(PDO::FETCH_ASSOC);

            header('Content-type: application/json');

            $error = '';
            

            // If account exists
            if ($fetchPass['num'] > 0) {  
                // If password match
                if (password_verify($_POST["password"], $fetchPass["Password"])) {
                    $_SESSION["user"] = $username;  
                    echo json_encode("Du är nu inloggad");
                    die;
                } else {
                    // If password doesn't match
                    $error = json_encode("Lösenord matchar inte");
                }

            } else {
                // If account doesn't exist
                $error = json_encode("Användaren finns inte");
            }

            echo $error;
         
        } catch (EXCEPTION $err) {
            throw $err;
        }
    }


    // Kan brytas ner till login functionen vi har där uppe


    function checkIfAdmin($username) {
        $sql = "SELECT IsAdmin FROM account WHERE Username = :username";
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':username', $username);
        $statement->execute();
        $checkAdmin = $statement->fetch(PDO::FETCH_ASSOC);
        if ($checkAdmin["IsAdmin"] == "1") { 
            return true;
        } else {
            return false;
        }
    }

    function getEmail($username) {
        $sql = "SELECT Email from account WHERE Username = :username";
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':username', $username);
        $statement->execute();
        $res = $statement->fetch();
        return $res["Email"];
    }
}


?>