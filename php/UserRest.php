<?php
session_start();

/**
 * Created by PhpStorm.
 * User: deaton
 * Date: 4/12/18
 * Time: 1:32 PM
 */

require_once(dirname(__DIR__)."/php/autoload.php");
use Unm\Deaton\{User,ScheduleItem};
/**
 * API for the User class.
 */
// Check the session. If it is not active, start the session.
//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
   // get mySQL connection
    $host = '';
    $db = '';
    $user = '';
    $pass = '';
    $charset = 'utf8';
    $options = array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new \PDO($dsn, $user, $pass, $options);

//    $user = new User(null, "Test3", "Daniel","Eaton","danielgeaton747@gmail.com", hash_pbkdf2("sha512", "this is a password", bin2hex(random_bytes(32)), 262144),bin2hex(random_bytes(32)));
//
//    $user->insert($pdo);
//
//    $scheduleItem = new ScheduleItem(null,"Description", "item",new DateTime("now"), new \DateTime("now"), $user->getUserId());
//    $scheduleItem->insert($pdo);


    //check which HTTP method was used
    $method = array_key_exists("HTTP_x_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];


    // handle POST request
    if ($method === "GET") {

        $requestContent = file_get_contents("php://input");
        $requestObject = json_decode($requestContent);
        // check username & password available and sanitize
        if(empty($requestObject->userUsername) === true) {
            $reply->status = 405;
            $reply->message = "password is empty";
        } else {
            $userNameCheck = filter_var($requestObject->userUsername, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }
        if(empty($requestObject->userPassword) === true) {
            $reply->status = 405;
            $reply->message = "password is empty";
        } else {
            $userPasswordCheck = filter_var($requestObject->userPassword, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }

        //$reply->message = $requestObject->userPassword;
        $mainUser = User::getUserByUserUsername($pdo,$userNameCheck);
        if(empty($mainUser) === true) {
            $reply->status = 405;
            $reply->message = "username or password is incorrect";
        } else{
            if(!$mainUser->checkHash($userPasswordCheck)) {
                $reply->status = 405;
                $reply->message = "username or password is incorrect";
            }
            else{
                $_SESSION["userId"] = $mainUser->getUserId();
                $reply->status = 200;
                $reply->message = $mainUser->getUserId();
            }
        }

    }

} catch
(Exception $exception) {
    $reply->status = $exception->getCode();
    $reply->message = $exception->getMessage();
} catch(TypeError $typeError) {
    $reply->status = $typeError->getCode();
    $reply->message = $typeError->getMessage();
}
//header("Content-type: application/json");
if($reply->data === null) {
    unset($reply->data);
}
echo json_encode($reply);
