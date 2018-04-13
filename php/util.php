<?php

/**
 * Created by PhpStorm.
 * User: deaton
 * Date: 4/12/18
 * Time: 3:20 PM
 */

require_once(dirname(__DIR__)."/php/autoload.php");
use Unm\Deaton\{User,ScheduleItem};


$host = '';
$db = '';
$user = '';
$pass = '';
$charset = 'utf8';
$options = array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new \PDO($dsn, $user, $pass, $options);


$salt = bin2hex(random_bytes(32));
$user1 = new User(null, "DanielEaton", "Daniel","Eaton","deaton747@unm.edu", hash_pbkdf2("sha512", "password3", $salt, 262144),$salt);

$user1->insert($pdo);
$user1Id = $user1->getUserId();

$user2 = new User(null, "TKKing", "TK","King","TKKing@unm.edu", hash_pbkdf2("sha512", "password1", $salt, 262144),$salt);

$user2->insert($pdo);
$user2Id = $user2->getUserId();

$user3 = new User(null, "JustinDonThomas", "Justice","Don Thomas","justindonthomas@unm.edu", hash_pbkdf2("sha512", "password3", $salt, 262144),$salt);

$user3->insert($pdo);
$user3Id = $user3->getUserId();


$scheduleItem1 = new ScheduleItem(null,"Meet with UNM Faculty", "Meeting 1",new \DateTime("2018-04-13 05:06:43"), new \DateTime("2018-04-13 08:01:43"), $user2Id);

$scheduleItem1->insert($pdo);

$scheduleItem2 = new ScheduleItem(null,"Finish the proposal for the big project coming up!", "Finish Project Proposal",new \DateTime("2018-04-12 05:06:43"), new \DateTime("2018-04-12 08:01:43"), $user2Id);

$scheduleItem2->insert($pdo);

$scheduleItem3 = new ScheduleItem(null,"Have meeting with Regents", "Meet with Regents",new \DateTime("2018-04-12 05:06:43"), new \DateTime("2018-04-12 08:01:43"), $user2Id);

$scheduleItem3->insert($pdo);

$scheduleItem4 = new ScheduleItem(null,"Have meeting with Faculty", "Meet with CS Prof",new \DateTime("2018-04-12 05:06:43"), new \DateTime("2018-04-12 05:06:43"), $user3Id);

$scheduleItem4->insert($pdo);

$scheduleItem5 = new ScheduleItem(null,"Write the Unit Tests for Class1 and Class2", "Write Unit Tests",new \DateTime("2018-04-19 05:08:43"), new \DateTime("2018-04-21 08:03:42"), $user1Id);

$scheduleItem5->insert($pdo);

$scheduleItem6 = new ScheduleItem(null,"Write the REST Endpoints for Class1 and Class2", "Write REST Endpoints",new \DateTime("2018-04-19 05:08:43"), new \DateTime("2018-04-21 08:03:42"), $user1Id);

$scheduleItem6->insert($pdo);

$scheduleItem7 = new ScheduleItem(null,"Study sections 4.1 and 4.2", "Study Linear Algebra",new \DateTime("2018-04-13 05:08:43"), new \DateTime("2018-04-13 08:03:42"), $user1Id);

$scheduleItem7->insert($pdo);

$scheduleItem8 = new ScheduleItem(null,"Integrate Authorize.net payment processor for Client", "Integrate Authorize.net",new \DateTime("2018-04-14 05:08:43"), new \DateTime("2018-04-21 08:03:42"), 266);

$scheduleItem8->insert($pdo);

$scheduleItem9 = new ScheduleItem(null,"Meet with TK and Justin and hopefully get hired!", "Meet with TK Justin",new \DateTime("2018-04-14 05:08:43"), new \DateTime("2018-04-21 08:03:42"), 266);

$scheduleItem9->insert($pdo);