<?php
/**
 * Created by PhpStorm.
 * User: deaton
 * Date: 4/12/18
 * Time: 5:47 PM
 */

require_once(dirname(__DIR__)."/php/autoload.php");
use Unm\Deaton\{ScheduleItem};
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
    if ($method === "GET") {

        $requestContent = file_get_contents("php://input");
        $requestObject = json_decode($requestContent);
        // check userId and sanitize
        if(empty($_GET["userId"]) === true) {
            $reply->status = 405;
            $reply->message = "userId is empty";
        } else {
            $userIdCheck = filter_var($_GET["userId"], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }

        //return all scheduleItems associated with the userId in the request
        $scheduleItemList = ScheduleItem::getScheduleItemByScheduleItemUserId($pdo,$userIdCheck);
        $reply->message = $scheduleItemList;

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
