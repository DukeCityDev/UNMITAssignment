<?php
/**
 *
 *
 * User: deaton
 * Date: 4/11/18
 * Time: 9:38 PM
 *
 *
 *
 *   scheduleItemId INT UNSIGNED AUTO_INCREMENT  NOT NULL,
 *   scheduleItemDesciption VARCHAR(1000) NOT NULL,
 *   scheduleItemName VARCHAR(24),
 *   scheduleItemStartTime DATETIME NOT NULL,
 *   scheduleItemEndTime DATETIME NOT NULL,
 *   userId INT UNSIGNED NOT NULL,
 *   dateTimePosted DATETIME NOT NULL
 */

namespace Unm\Deaton;
require_once("autoload.php");

/**
 * ScheduleItem class is used to sanitize and work with data that will be stored in the scheduleItem database
 *
 */
class ScheduleItem{
    /**
     * @var int ID for the scheduleItem
     */
    private $scheduleItemId;
    /**
     * @var String a description of the schedule Item, it can be up to 1000 characters long
     */
    private $scheduleItemDescription;
    /**
     * @var String The name of the schedule item, it can be up to 24 characters long
     */
    private $scheduleItemName;
    /**
     * @var DATETIME The start time of the schedule item
     */
    private $scheduleItemStartTime;
    /**
     * @var DATETIME The end time of the schedule item
     */
    private $scheduleItemEndTime;
    /**
     * @var int The id for the user who has the scheduleItem
     */
    private $scheduleItemUserId;

    /**
     * ScheduleItem constructor.
     * @param $newScheduleItemId null|int the scheduleItem of the Id
     * @param $newScheduleItemDescription string the scheduleItem description
     * @param $newScheduleItemName string the scheduleItem name
     * @param $newScheduleItemStartTime \DateTime the scheduleItem start date
     * @param $newScheduleItemEndTime \DateTime the scheduleItem end date
     * @param $newScheduleItemUserId int the scheduleItem user id
     * @throws \Exception
     * @throws \TypeError
     */
    public function __construct($newScheduleItemId, $newScheduleItemDescription, $newScheduleItemName, $newScheduleItemStartTime, $newScheduleItemEndTime, $newScheduleItemUserId)
    {
        try {
            $this->setScheduleItemId($newScheduleItemId);
            $this->setScheduleItemDescription($newScheduleItemDescription);
            $this->setScheduleItemName($newScheduleItemName);
            $this->setScheduleStartTime($newScheduleItemStartTime);
            $this->setScheduleEndTime($newScheduleItemEndTime);
            $this->setScheduleItemTime($newScheduleItemUserId);

        } catch(\InvalidArgumentException $invalidArgument) {
            throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
        } catch(\RangeException $range) {
            throw(new \RangeException($range->getMessage(), 0, $range));
        } catch(\TypeError $typeError) {
            throw(new \TypeError($typeError->getMessage(), 0, $typeError));
        } catch(\Exception $exception) {
            throw(new \Exception($exception->getMessage(), 0, $exception));
        }
    }

    /**
     * @return int The Id of the schedule item
     */
    public function getScheduleItemId()
    {
        return $this->scheduleItemId;
    }

    /**
     * mutator method for schedule item id
     * @param int $newScheduleItemId
     * @throws \RangeException if $newScheduleItemId is negative
     * @throws \TypeError if $newUserId is not an integer
     **/
    public function setScheduleItemId($newScheduleItemId) {
        // if the user id is null, this is a new user without an id from mySQL
        if($newScheduleItemId === null) {
            $this->scheduleItemId = null;
            return;
        }
        // verify that scheduleItemId is positive
        if($newScheduleItemId <= 0) {
            throw (new \RangeException("schedule item ID is not positive"));
        }
        $this->scheduleItemId = $newScheduleItemId;
    }

    /**
     * @return String The description of the schedule item
     */
    public function getScheduleItemDescription(){
        return $this->scheduleItemDescription;
    }

    /**
     * @param String $newScheduleItemDescription, can only be 1000 characters long
     *
     * @throws \RangeException If $newScheduleItemDescription is over 1000 characters long
     */
    public function setScheduleItemDescription($newScheduleItemDescription)
    {
        $newScheduleItemDescription = trim($newScheduleItemDescription);
        $newScheduleItemDescription = filter_var($newScheduleItemDescription, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

        if(str_len($newScheduleItemDescription) > 1000){
            throw(new \RangeException("scheduleItemDescription is larger than 1000"));
        }

        $this->scheduleItemDescription = $newScheduleItemDescription;
    }

    public function getScheduleItemStartTime(){
        return $this->scheduleItemStartTime;
    }

    public function setScheduleItemStartTime($newScheduleItemStartTime){
        $this->scheduleItemStartTime = $newScheduleItemStartTime;
    }

    public function getScheduleItemEndTime(){
        return $this->scheduleItemEndTime;
    }

    public function setScheduleItemEndTime($newScheduleItemEndTime){
        $this->scheduleItemStartTime = $newScheduleItemEndTime;
    }

    /**
     * Accessor method for scheduleItemUserId
     * @return int the scheduleItem's User Id
     */
    public function getScheduleItemUserId(){
        return $this->scheduleItemUserId;
    }

    /**
     * mutator method for scheduleItemUserId
     * @param int $newScheduleItemUserId
     * @throws \RangeException if $newUserId is negative
     * @throws \TypeError if $newUserId is not an integer
     **/
    public function setScheduleItemUserId($newScheduleItemUserId) {
        // if the user id is null, this is a new user without an id from mySQL
        if($newScheduleItemUserId === null) {
            throw new \TypeError("User Id is null");
        }
        // verify that user id is positive
        if($newScheduleItemUserId <= 0) {
            throw (new \RangeException("user id is not positive"));
        }
        $this->scheduleItemUserId = $newScheduleItemUserId;
    }

    /**
     * Insert a new scheduleItem entry.
     * @param \PDO $pdo the PDO connection object.
     * @throws \PDOException if mySQL related errors occur.
     * @throws \TypeError if $pdo is not a PDO connection object.
     **/
    public function insert(\PDO $pdo) {
        //check to make sure this user doesn't already exist
        if($this->scheduleItemId !== null || $this->scheduleItemUserId == null) {
            throw(new \PDOException("not a new scheduleItem or no user Id"));
        }
        //create query template
        $query = "INSERT INTO scheduleItem( scheduleItemDesciption, scheduleItemName, scheduleItemStartTime, scheduleItemEndTime, scheduleItemUserId) VALUES (:scheduleItemDesciption, :scheduleItemName, :scheduleItemStartTime, :scheduleItemEndTime, :scheduleItemUserId)";
        $statement = $pdo->prepare($query);
        // bind member variables to placeholders in the template
        $parameters = ["scheduleItemName" => $this->scheduleItemId,"scheduleItemName"=>$this->scheduleItemName,"scheduleItemDescription"=>$this->scheduleItemDescription ,"scheduleItemStartTime"=>$this->scheduleItemStartTime, "scheduleItemEndTime" => $this->scheduleItemEndTime, "scheduleItemUserId" => $this->scheduleItemUserId];
        $statement->execute($parameters);
        $this->scheduleItemId = intval($pdo->lastInsertId());
    }
    /**
     * Delete a scheduleItem entry.
     * @param \PDO $pdo PDO connection object.
     * @throws \PDOException if mySQL related errors occur.
     * @throws \TypeError if $pdo is not a PDO object.
     **/
    public function delete(\PDO $pdo) {

        if($this->scheduleItemId == null){
            throw new \PDOException("can't deleted uninserted scheduleItem");
        }
        // create query template
        $query = "DELETE FROM scheduleItem WHERE scheduleItemId = :scheduleItemId";
        $statement = $pdo->prepare($query);
        // bind member variables to placeholder in template
        $parameters = ["scheduleItemId" => $this->scheduleItemId];
        $statement->execute($parameters);
    }
    /**
     * Updates the scheduleItem entry in a database.
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError if $pdo is not a PDO connection object.
     **/
    public function update(\PDO $pdo) {
        //create query template
        if($this->scheduleItemId == null){
            throw new \PDOException("can't update un-inserted scheduleItem",0,null);
        }

        $query = "UPDATE scheduleItem SET scheduleItemDescription = :scheduleItemDescription, scheduleItemName = :scheduleItemName, scheduleItemStartTime = :scheduleItemStartTime,scheduleItemEndTime = :scheduleItemEndTime, scheduleItemUserId = :scheduleItemUserId WHERE scheduleItemId = :scheduleItemId";
        $statement = $pdo->prepare($query);
        // bind member variables to placeholders
        $parameters = ["scheduleItemId" => $this->scheduleItemId, "scheduleItemDescription" => $this->scheduleItemDescription, "scheduleItemName"=>$this->scheduleItemName, "scheduleItemStartTime"=>$this->scheduleItemStartTime, "scheduleItemEndTime" => $this->scheduleItemEndTime, "scheduleItemUserId" => $this->scheduleItemUserId];
        $statement->execute($parameters);
    }

    /**
     * Get scheduleItems associated with the specified scheduleItemUserId.
     * @param \PDO $pdo a PDO connection object
     * @param int $userId a valid scheduleItemId
     * @return User|null User found or null if not found
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError when parameters are not the correct data type.
     **/
    public static function getUserByUserId(\PDO $pdo, int $scheduleItemUserId) {
        if($scheduleItemUserId <= 0) {
            throw(new \RangeException("scheduleItemUserId must be positive."));
        }
        // create query template
        $query = "SELECT scheduleItemId, scheduleItemDescription, scheduleItemName, scheduleItemStartDate, scheduleItemEndDate, scheduleItemUserId FROM scheduleItem WHERE scheduleItemUserId = :scheduleItemUserId";
        $statement = $pdo->prepare($query);
        // bind the user id to the place holder in the template
        $parameters = ["scheduleItemUserId" => $scheduleItemUserId];
        $statement->execute($parameters);
        // grab the user from mySQL

        $scheduleItems = new \SplFixedArray($statement->rowCount());

        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $row = $statement->fetch();
        while(($row = $statement->fetch()) !== false){
            try{
                $scheduleItem = new ScheduleItem($row['scheduleItemId'], $row['scheduleItemDescription'], $row['scheduleItemName'], $row['scheduleItemStartTime'], $row['scheduleItemEndTime'], $row['newScheduleItemUserId']);
                $scheduleItems[$scheduleItems->key()] = $scheduleItem;
                $scheduleItems->next();

            }catch(\Exception $e){
                throw(new \PDOException(($e->getMessage(),0,$e)));
            }
        }


        if($row !== false) {
            $scheduleItem = new ScheduleItem($row["userId"], $row["userUsername"], $row["userFirstName"], $row["userLastName"] ,$row["userEmail"], $row["userHash"], $row["userSalt"]);
        }
        return $scheduleItems;
    }

}