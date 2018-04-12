
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

}