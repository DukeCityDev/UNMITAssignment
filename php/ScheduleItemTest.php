<?php
/**
 * Created by PhpStorm.
 * ScheduleItem: deaton
 * Date: 4/11/18
 * Time: 11:50 PM
 */
use Unm\Deaton\{ScheduleItem,User,UNMTest};

//require_once("UNMTest.php");
require_once(dirname(__DIR__)."/php/autoload.php");


/**
 * ScheduleItemTest Class
 *
 * This ScheduleItem Test will test the search for scheduleItems by Id, ScheduleItemname,
 *
 *
 **/
class ScheduleItemTest extends UNMTest {
    /**
     * schedule name for this scheduleItem
     * @var string $VALID_USERNAME
     **/
    protected $VALID_SCHEDULE_ITEM_NAME = "Meeting with John";
    /**
     * valid first description for this scheduleItem
     * @var string $VALID_USERNAME
     **/
    protected $VALID_SCHEDULE_ITEM_DESCRIPTION = "I need to meet with John for this reason and that reason and that other reason.";
    /**
     * valid start date for this scheduleItem
     * @var string $VALID_USERNAME
     **/
    protected $VALID_SCHEDULE_START_TIME = "2012-2-1";

    /**
     * valid for this end date
     * @var string $VALID_EMAIL
     **/
    protected $VALID_SCHEDULE_END_TIME = "2012-5-4";
    /**
     * valid User Id for this scheduleItem
     * @var string $VALID_HASH
     **/
    protected $VALID_USER_ID;
    /**
     * hash for this user
     * just gibberish letters
     * @var string $VALID_HASH
     **/
    protected $VALID_HASH;
    /**
     * salt for this user
     * just gibberish letters
     * @var string $VALID_SALT
     **/
    protected $VALID_SALT;
    /**
     * The scheduleItem being tested
     */
    protected $scheduleItem = null;

    protected $user = null;


    public final function setUp() {
        //run the default setUp() method
        //create new zip code for testing
        //creates password salt for testing
        $this->VALID_SALT = bin2hex(random_bytes(32));
        //creates password hash for testing
        $this->VALID_HASH = hash_pbkdf2("sha512", "this is a password", $this->VALID_SALT, 262144);

        $user = new User(null, "deaton747","Daniel","Eaton","deaton747@unm.edu",$this->VALID_HASH,$this->VALID_SALT);
        $user->insert($this->getPDO());
        $this->VALID_USER_ID = $user->getUserId();
    }
    /**
     * test inserting a valid ScheduleItem and verify that the actual mySQL data matches
     **/
    public function testInsertValidScheduleItem() {
        // count the number of rows and save it for later
        $numRows = $this->getConnection()->getRowCount("scheduleItem");
        // create a new ScheduleItem and insert to into mySQL
        $scheduleItem = new ScheduleItem(null,$this->VALID_SCHEDULE_ITEM_DESCRIPTION, $this->VALID_SCHEDULE_ITEM_NAME,$this->VALID_SCHEDULE_START_TIME, $this->VALID_SCHEDULE_END_TIME, $this->VALID_USER_ID);
        echo($this->VALID_USER_ID);
        $scheduleItem->insert($this->getPDO());
        // grab the data from mySQL and enforce the fields match our expectations
        $pdoScheduleItem = ScheduleItem::getScheduleItemByScheduleItemUserId($this->getPDO(), $this->VALID_USER_ID);
        echo("NEW: ");
        echo($pdoScheduleItem[0]->getScheduleItemUserId());

        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("scheduleItem"));
        $this->assertEquals($pdoScheduleItem[0]->getScheduleItemName(), $this->VALID_SCHEDULE_ITEM_NAME);
        $this->assertEquals($pdoScheduleItem[0]->getScheduleItemDescription(), $this->VALID_SCHEDULE_ITEM_DESCRIPTION);
        $this->assertEquals($pdoScheduleItem[0]->getScheduleItemStartTime(), $this->VALID_SCHEDULE_START_TIME);
        $this->assertEquals($pdoScheduleItem[0]->getScheduleItemEndTime(), $this->VALID_SCHEDULE_END_TIME);
        $this->assertEquals($pdoScheduleItem[0]->getScheduleItemUserId(), $this->VALID_USER_ID);
    }

    /**
     * test inserting a ScheduleItem that already exists
     * @expectedException \PDOException
     **/
    public function testInsertInvalidScheduleItem() {
        // create a ScheduleItem with a non null scheduleItem id and watch it fail
        $scheduleItem = new ScheduleItem(5786,$this->VALID_SCHEDULE_ITEM_DESCRIPTION, $this->VALID_SCHEDULE_ITEM_NAME,$this->VALID_SCHEDULE_START_TIME, $this->VALID_SCHEDULE_END_TIME, $this->VALID_USER_ID);
        $scheduleItem->insert($this->getPDO());
    }

//    /**
//     * test inserting a ScheduleItem, editing it, and then updating it
//     **/
//    public function testUpdateValidScheduleItem() {
//        // count the number of rows and save it for later
//        $numRows = $this->getConnection()->getRowCount("scheduleItem");
//        // create a new ScheduleItem and insert to into mySQL
//        $scheduleItem = new ScheduleItem(null,$this->VALID_SCHEDULE_ITEM_DESCRIPTION, $this->VALID_SCHEDULE_ITEM_NAME,$this->VALID_SCHEDULE_START_TIME, $this->VALID_SCHEDULE_END_TIME, $this->VALID_USER_ID);
//        $scheduleItem->insert($this->getPDO());
//        // edit the ScheduleItem and update it in mySQL
//        $scheduleItem->setScheduleItemName("Changed Name");
//        $scheduleItem->update($this->getPDO());
//        // grab the data from mySQL and enforce the fields match our expectations
//        $pdoScheduleItem = ScheduleItem::getScheduleItemByUserId($this->getPDO(), $scheduleItem->getScheduleItemUserId());
//        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("scheduleItem"));
//        $this->assertEquals($pdoScheduleItem[0]->getScheduleItemScheduleItemName(), $this->VALID_SCHEDULE_ITEM_NAME);
//        $this->assertEquals($pdoScheduleItem[0]->getScheduleItemDescription(), $this->VALID_SCHEDULE_ITEM_DESCRIPTION);
//        $this->assertEquals($pdoScheduleItem[0]->getScheduleItemStartTime(), $this->VALID_SCHEDULE_START_TIME);
//        $this->assertEquals($pdoScheduleItem[0]->getScheduleItemEndTime(), $this->VALID_SCHEDULE_END_TIME);
//        $this->assertEquals($pdoScheduleItem[0]->getScheduleItemUserId(), $this->VALID_USER_ID);
//    }
//    /**
//     * test updating a ScheduleItem that does not exist
//     * @expectedException \PDOException
//     **/
//    public function testUpdateInvalidScheduleItem() {
//        // create a ScheduleItem, try to update it without actually inserting it and watch it fail
//        $scheduleItem = new ScheduleItem(5618,$this->VALID_SCHEDULE_ITEM_DESCRIPTION, $this->VALID_SCHEDULE_ITEM_NAME,$this->VALID_SCHEDULE_START_TIME, $this->VALID_SCHEDULE_END_TIME, $this->VALID_USER_ID);
//        $scheduleItem->update($this->getPDO());
//    }
//    /**
//     * test creating a ScheduleItem and then deleting it
//     **/
//    public function testDeleteValidScheduleItem() {
//        // count the number of rows and save it for later
//        $numRows = $this->getConnection()->getRowCount("scheduleItem");
//        // create a new ScheduleItem and insert to into mySQL
//        $scheduleItem = new ScheduleItem(null, $this->VALID_USERNAME, $this->VALID_FIRST_NAME,$this->VALID_LAST_NAME,$this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT);
//        $scheduleItem->insert($this->getPDO());
//        // delete the ScheduleItem from mySQL
//        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("scheduleItem"));
//        $scheduleItem->delete($this->getPDO());
//        // grab the data from mySQL and enforce the ScheduleItem does not exist
//        $pdoScheduleItem = ScheduleItem::getScheduleItemByScheduleItemId($this->getPDO(), $scheduleItem->getScheduleItemId());
//        $this->assertNull($pdoScheduleItem);
//        $this->assertEquals($numRows, $this->getConnection()->getRowCount("scheduleItem"));
//    }
//    /**
//     * test deleting a ScheduleItem that does not exist
//     * @expectedException \Exception
//     **/
//    public function testDeleteInvalidScheduleItem() {
//        // create a ScheduleItem and try to delete it without actually inserting it
//        $scheduleItem = new ScheduleItem(null, $this->VALID_USERNAME, $this->VALID_FIRST_NAME,$this->VALID_LAST_NAME,$this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT);
//        $scheduleItem->delete($this->getPDO());
//    }
//    /**
//     * test grabbing a ScheduleItem by scheduleItem name
//     **/
//    public function testGetValidScheduleItemByScheduleItemScheduleItemname() {
//        // count the number of rows and save it for later
//        $numRows = $this->getConnection()->getRowCount("scheduleItem");
//        // create a new ScheduleItem and insert to into mySQL
//        $scheduleItem = new ScheduleItem(null, $this->VALID_USERNAME, $this->VALID_FIRST_NAME,$this->VALID_LAST_NAME,$this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT);
//        $scheduleItem->insert($this->getPDO());
//        // grab the data from mySQL and enforce the fields match our expectations
//        $results = ScheduleItem::getScheduleItemByScheduleItemScheduleItemName($this->getPDO(), $scheduleItem->getScheduleItemScheduleItemName());
//        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("scheduleItem"));
//        //$this->assertCount(1, $results);
//        //$this->assertContainsOnlyInstancesOf("Unm\\Deaton\\ScheduleItem", $results);
//        // grab the result from the array and validate it
//        $pdoScheduleItem = $results;
//        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("scheduleItem"));
//        $this->assertEquals($pdoScheduleItem->getScheduleItemScheduleItemName(), $this->VALID_USERNAME);
//        $this->assertEquals($pdoScheduleItem->getScheduleItemFirstName(),$this->VALID_FIRST_NAME);
//        $this->assertEquals($pdoScheduleItem->getScheduleItemLastName(),$this->VALID_LAST_NAME);
//        $this->assertEquals($pdoScheduleItem->getScheduleItemEmail(), $this->VALID_EMAIL);
//        $this->assertEquals($pdoScheduleItem->getScheduleItemHash(), $this->VALID_HASH);
//        $this->assertEquals($pdoScheduleItem->getScheduleItemSalt(), $this->VALID_SALT);
//    }
//
//    /**
//     * test grabbing all ScheduleItems
//     **/
//    public function testGetAllValidScheduleItems() {
//        // count the number of rows and save it for later
//        $numRows = $this->getConnection()->getRowCount("scheduleItem");
//        // create a new ScheduleItem and insert to into mySQL
//        $scheduleItem = new ScheduleItem(null, $this->VALID_USERNAME,$this->VALID_FIRST_NAME,$this->VALID_LAST_NAME, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT);
//        $scheduleItem->insert($this->getPDO());
//        // grab the data from mySQL and enforce the fields match our expectations
//        $results = ScheduleItem::getAllScheduleItems($this->getPDO());
//        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("scheduleItem"));
//        $this->assertCount(1, $results);
//        $this->assertContainsOnlyInstancesOf("Unm\\Deaton\\ScheduleItem", $results);
//        // grab the result from the array and validate it
//        $pdoScheduleItem = $results[0];
//        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("scheduleItem"));
//        $this->assertEquals($pdoScheduleItem->getScheduleItemScheduleItemName(), $this->VALID_USERNAME);
//        $this->assertEquals($pdoScheduleItem->getScheduleItemFirstName(),$this->VALID_FIRST_NAME);
//        $this->assertEquals($pdoScheduleItem->getScheduleItemLastName(),$this->VALID_LAST_NAME);
//        $this->assertEquals($pdoScheduleItem->getScheduleItemEmail(), $this->VALID_EMAIL);
//        $this->assertEquals($pdoScheduleItem->getScheduleItemHash(), $this->VALID_HASH);
//        $this->assertEquals($pdoScheduleItem->getScheduleItemSalt(), $this->VALID_SALT);
//    }
}
