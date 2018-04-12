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
 */

namespace Unm\Deaton;
require_once("autoload.php");

/**
 * ScheduleItem class is used to sanitize and work with data that will be stored in the scheduleItem database
 *
 */
class ScheduleItem implements \JsonSerializable {
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
     * @param $newScheduleItemStartTime string the scheduleItem start date
     * @param $newScheduleItemEndTime string the scheduleItem end date
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
            $this->setScheduleItemStartTime($newScheduleItemStartTime);
            $this->setScheduleItemEndTime($newScheduleItemEndTime);
            $this->setScheduleItemUserId($newScheduleItemUserId);

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

        if(strlen($newScheduleItemDescription) > 1000){
            throw(new \RangeException("scheduleItemDescription is larger than 1000"));
        }

        $this->scheduleItemDescription = $newScheduleItemDescription;
    }

    /**
     * @return String The description of the schedule name
     */
    public function getScheduleItemName(){
        return $this->scheduleItemName;
    }

    /**
     * @param String $newScheduleItemName, can only be 1000 characters long
     *
     * @throws \RangeException If $newScheduleItemDescription is over 1000 characters long
     */
    public function setScheduleItemName($newScheduleItemName)
    {
        $newScheduleItemName = trim($newScheduleItemName);
        $newScheduleItemName = filter_var($newScheduleItemName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

        if(strlen($newScheduleItemName) > 24){
            throw(new \RangeException("scheduleItemDescription is larger than 1000"));
        }

        $this->scheduleItemName = $newScheduleItemName;
    }

    public function getScheduleItemStartTime(){
        return new \DateTime($this->scheduleItemStartTime);
    }

    public function setScheduleItemStartTime($newScheduleItemStartTime){
        $this->scheduleItemStartTime = $newScheduleItemStartTime;
    }

    public function getScheduleItemEndTime(){
         return new \DateTime($this->scheduleItemEndTime);
;
    }

    public function setScheduleItemEndTime($newScheduleItemEndTime){
        $this->scheduleItemEndTime = $newScheduleItemEndTime;
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
        //check toinsert make sure this user doesn't already exist
        if($this->scheduleItemId !== null || $this->scheduleItemUserId == null) {
            throw(new \PDOException("not a new scheduleItem or no user Id"));
        }

        //create query template
        $query = "INSERT INTO scheduleItem(scheduleItemDescription, scheduleItemName, scheduleItemStartTime, scheduleItemEndTime, scheduleItemUserId) VALUES (:scheduleItemDescription, :scheduleItemName, :scheduleItemStartTime, :scheduleItemEndTime, :scheduleItemUserId)";
        $statement = $pdo->prepare($query);
        // bind member variables to placeholders in the template
        $parameters = ["scheduleItemDescription" => $this->scheduleItemDescription,"scheduleItemName"=>$this->scheduleItemName,"scheduleItemStartTime"=>$this->scheduleItemStartTime->format('Y-m-d H:i:s'), "scheduleItemEndTime" => $this->scheduleItemEndTime->format('Y-m-d H:i:s'), "scheduleItemUserId" => $this->scheduleItemUserId];
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
        $parameters = ["scheduleItemId" => $this->scheduleItemId, "scheduleItemDescription" => $this->scheduleItemDescription, "scheduleItemName"=>$this->scheduleItemName, "scheduleItemStartTime"=>$this->scheduleItemStartTime->format('Y-m-d H:i:s'), "scheduleItemEndTime" => $this->scheduleItemEndTime->format('Y-m-d H:i:s'), "scheduleItemUserId" => $this->scheduleItemUserId];
        $statement->execute($parameters);
    }

    /**
     * Get scheduleItems associated with the specified scheduleItemUserId.
     * @param \PDO $pdo a PDO connection object
     * @param int $scheduleItemUserId a valid scheduleItemId
     * @return \SplFixedArray User found or null if not found
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError when parameters are not the correct data type.
     **/
    public static function getScheduleItemByScheduleItemUserId(\PDO $pdo, $scheduleItemUserId) {
        if($scheduleItemUserId <= 0) {
            throw(new \RangeException("scheduleItemUserId must be positive."));
        }
        // create query template
        $query = "SELECT scheduleItemId, scheduleItemDescription, scheduleItemName, scheduleItemStartTime, scheduleItemEndTime, scheduleItemUserId FROM scheduleItem WHERE scheduleItemUserId = :scheduleItemUserId";
        $statement = $pdo->prepare($query);
        // bind the user id to the place holder in the template
        $parameters = ["scheduleItemUserId" => $scheduleItemUserId];
        $statement->execute($parameters);
        // grab the user from mySQL

        $scheduleItems = new \SplFixedArray($statement->rowCount());

        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        while(($row = $statement->fetch()) !== false){
            try{
                $scheduleItem = new ScheduleItem($row['scheduleItemId'], $row['scheduleItemDescription'], $row['scheduleItemName'], $row['scheduleItemStartTime'], $row['scheduleItemEndTime'], $row['scheduleItemUserId']);
                $scheduleItems[$scheduleItems->key()] = $scheduleItem;
                $scheduleItems->next();

            }catch(\Exception $e){
                throw (new \PDOException($e->getMessage()));
            }
        }

        return $scheduleItems;
    }
    /**
     * format instance variables for JSON serialization
     * @return array an array with serialized state variables
     **/
    public function jsonSerialize() {
        $fields = ["scheduleItemId"=>$this->getScheduleItemId(), "scheduleItemDescription"=> $this->getScheduleItemDescription(), "scheduleItemName"=>$this->getScheduleItemName(), "scheduleItemStartTime"=>$this->scheduleItemStartTime,"scheduleItemEndTime"=>$this->scheduleItemEndTime,"scheduleItemUserId"=>$this->scheduleItemUserId];
        return ($fields);
    }
}