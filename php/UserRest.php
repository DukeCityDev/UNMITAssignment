<?php
session_start();

/**
 * Created by PhpStorm.
 * User: deaton
 * Date: 4/12/18
 * Time: 1:32 PM
 */

require_once(dirname(__DIR__)."/php/autoload.php");
use Unm\Deaton\{User};
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


    //check which HTTP method was used
    $method = array_key_exists("HTTP_x_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];


    // handle POST request
    if ($method === "POST") {

        $requestContent = file_get_contents("php://input");
        $requestObject = json_decode($requestContent);
        // check username and sanitize it
        if(empty($requestObject->userUsername) === true) {
            $reply->status = 405;
            $reply->message = "password is empty";
        } else {
            $userNameCheck = filter_var($requestObject->userUsername, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        } //check password and sanitize it
        if(empty($requestObject->userPassword) === true) {
            $reply->status = 405;
            $reply->message = "password is empty";
        } else {
            $userPasswordCheck = filter_var($requestObject->userPassword, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }

        //get user by userName that was sent in the request
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
                //if the user's password hash matched, send a valid response with a user object containing username, id, and email
                $reply->status = 200;
                $reply->message = $mainUser;
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
if($reply->data === null) {
    unset($reply->data);
}
//send response
echo json_encode($reply);
