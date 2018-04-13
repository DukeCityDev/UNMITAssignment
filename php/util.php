<?php

/**
 * Created by PhpStorm.
 * User: deaton
 * Date: 4/12/18
 * Time: 3:20 PM
 */

require_once(dirname(__DIR__)."/php/autoload.php");
use Unm\Deaton\{User,ScheduleItem};

//$salt = bin2hex(random_bytes(32));
//$user2 = new User(null, "deaton74747", "Daniel","Eaton","danielgeaton747@gmail.com", hash_pbkdf2("sha512", "Crufsp747", $salt, 262144),$salt);

$host = '';
$db = '';
$user = '';
$pass = '';
$charset = 'utf8';
$options = array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new \PDO($dsn, $user, $pass, $options);

//227

$scheduleItem = new ScheduleItem(null,"Description", "schedule3",new \DateTime("now"), new \DateTime("now"), 227);

$scheduleItem->insert($pdo);
