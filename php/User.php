<?php
/**
 *
 * User: deaton
 * Date: 4/11/18
 * Time: 1:11 PM
 */

namespace Unm\Deaton;
require_once("autoload.php");
/**
 * User Information Class
 *
 * This User will access and store data.
 *
 **/
class User implements \JsonSerializable {
    /**
     * id for this user; this is the primary key
     * @var int $userId
     **/
    private $userId;
    /**
     * user name for this user
     * @var string $userUsername
     **/
    private $userUsername;
    /**
     * first name for this user
     * @var string $userFirstName
     */
    private $userFirstName;
    /**
     * last name for this user
     * @var string $userLastName
     */
    private $userLastName;
    /**
     * email for this user
     * @var string $userEmail
     **/
    private $userEmail;

    /**
     * hash for this user
     * @var string $userHash
     **/
    private $userHash;
    /**
     * salt for this user
     * @var string $userSalt
     **/
    private $userSalt;

    /**
     * User constructor.
     * @param $newUserId
     * @param $newUserUsername
     * @param $newUserFirstName
     * @param $newUserLastName
     * @param $newUserEmail
     * @param $newUserHash
     * @param $newUserSalt
     * @throws \Exception
     * @throws \TypeError
     */
    public function __construct($newUserId, $newUserUsername,$newUserFirstName,$newUserLastName, $newUserEmail, $newUserHash, $newUserSalt) {
        try {
            $this->setUserId($newUserId);
            $this->setUserUsername($newUserUsername);
            $this->setUserFirstName($newUserFirstName);
            $this->setUserLastName($newUserLastName);
            $this->setUserEmail($newUserEmail);
            $this->setUserHash($newUserHash);
            $this->setUserSalt($newUserSalt);
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
     * accessor method for user id
     * @return int
     **/
    public function getUserId() {
        return $this->userId;
    }
    /**
     * mutator method for user id
     * @param int $newUserId
     * @throws \RangeException if $newUserId is negative
     * @throws \TypeError if $newUserId is not an integer
     **/
    public function setUserId($newUserId) {
        // if the user id is null, this is a new user without an id from mySQL
        if($newUserId === null) {
            $this->userId = null;
            return;
        }
        // verify that user id is positive
        if($newUserId <= 0) {
            throw (new \RangeException("user id is not positive"));
        }
        $this->userId = $newUserId;
    }
    /**
     * accessor method for user name
     * @return string
     **/
    public function getUserUsername() {
        return $this->userUsername;
    }
    /**
     * mutator method for user user name
     * @param string $newUserUsername
     * @throws \InvalidArgumentException if $newUserUsername is empty or is not a string
     * @throws \RangeException if $newUserUsername is too long
     **/
    public function setUserUsername($newUserUsername) {
        $newUserUsername = trim($newUserUsername);
        $newUserUsername = filter_var($newUserUsername, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $newUserUsername = strtolower($newUserUsername);
        if(empty($newUserUsername)) {
            throw (new \InvalidArgumentException("user name is empty or has invalid contents"));
        }
        if(strlen($newUserUsername) > 24) {
            throw(new \RangeException("user name is too large"));
        }
        $this->userUsername = $newUserUsername;
    }

    /**
     * accessor method for user's first name
     * @return string
     **/
    public function getUserFirstName() {
        return $this->userFirstName;
    }

    /**
     * mutator method for userFirstName
     * @param string $newUserFirstName
     * @throws \InvalidArgumentException if $newUserFirstName is empty or is not a string
     * @throws \RangeException if $newUserFirstName is longer than 24 characters
     **/
    public function setUserFirstName($newUserFirstName) {
        $newUserFirstName = trim($newUserFirstName);
        $newUserFirstName = filter_var($newUserFirstName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $newUserFirstName = strtolower($newUserFirstName);
        if(empty($newUserFirstName)){
            throw (new \InvalidArgumentException("first name is empty or has invalid contents"));
        }
        if(strlen($newUserFirstName) > 24) {
            throw(new \RangeException("first name is too large"));
        }
        $this->userFirstName = $newUserFirstName;
    }
    /**
     * accessor method for user's last name
     * @return string
     **/
    public function getUserLastName() {
        return $this->userLastName;
    }

    /**
     * mutator method for UserLastname
     * @param string $newUserLastName
     * @throws \InvalidArgumentException if $newUserLastName is empty or is not a string
     * @throws \RangeException if $newUserLastName is longer than 24 characters
     **/
    public function setUserLastName($newUserLastName) {
        $newUserLastName = trim($newUserLastName);
        $newUserLastName = filter_var($newUserLastName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $newUserLastName = strtolower($newUserLastName);
        if(empty($newUserLastName)) {
            throw (new \InvalidArgumentException("last name is empty or has invalid contents"));
        }
        if(strlen($newUserLastName) > 24) {
            throw(new \RangeException("last name is too large"));
        }
        $this->userLastName = $newUserLastName;
    }
    /**
     * accessor method for email
     * @return string
     **/
    public function getUserEmail() {
        return $this->userEmail;
    }
    /**
     * mutator method for user email
     * @param string $newUserEmail
     * @throws \InvalidArgumentException if $newUserEmail is empty or is not a string
     * @throws \RangeException if $newUserEmail is too long
     **/
    public function setUserEmail($newUserEmail) {
        $newUserEmail = trim($newUserEmail);
        $newUserEmail = filter_var($newUserEmail, FILTER_SANITIZE_EMAIL, FILTER_FLAG_NO_ENCODE_QUOTES);
        if(empty($newUserEmail)) {
            throw (new \InvalidArgumentException("email is empty or has invalid contents"));
        }
        if(strlen($newUserEmail) > 160) {
            throw(new \RangeException("email is too large"));
        }
        $this->userEmail = $newUserEmail;
    }

    public function getUserHash() {
        return $this->userHash;
    }
    /**
     * mutator method for user password hash
     * @param string $newUserHash
     **/
    public function setUserHash($newUserHash) {
        $newUserHash = trim($newUserHash);
        $newUserHash = strtolower($newUserHash);
        if(ctype_xdigit($newUserHash) === false) {
            throw (new \InvalidArgumentException("hash is empty or has invalid contents"));
        }
        if(strlen($newUserHash) !== 128) {
            throw(new \RangeException("hash length " . strlen($newUserHash) . ", is incorrect length"));
        }
        $this->userHash = $newUserHash;
    }
    /**
     * accessor method for password salt
     * @return string
     **/
    public function getUserSalt() {
        return $this->userSalt;
    }
    /**
     * mutator method for user password salt
     * @param string $newUserSalt
     **/
    public function setUserSalt($newUserSalt) {
        $newUserSalt = trim($newUserSalt);
        $newUserSalt = strtolower($newUserSalt);
        if(ctype_xdigit($newUserSalt) === false) {
            throw (new \InvalidArgumentException("salt is empty or has invalid contents"));
        }
        if(strlen($newUserSalt) !== 64) {
            throw(new \RangeException("salt is incorrect length"));
        }
        $this->userSalt = $newUserSalt;
    }

    /**
     * Insert a new User entry.
     * @param \PDO $pdo the PDO connection object.
     * @throws \PDOException if mySQL related errors occur.
     * @throws \TypeError if $pdo is not a PDO connection object.
     **/
    public function insert(\PDO $pdo) {
        //check to make sure this user doesn't already exist
        if($this->userId !== null) {
            throw(new \PDOException("not a new user"));
        }
        //create query template
        $query = "INSERT INTO user( userUsername, userFirstName, userLastName, userEmail, userHash, userSalt) VALUES ( :userUsername, :userFirstName, :userLastName ,:userEmail, :userHash, :userSalt)";
        $statement = $pdo->prepare($query);
        // bind member variables to placeholders in the template
        $parameters = ["userUsername" => $this->userUsername,"userFirstName"=>$this->userFirstName, "userLastName"=>$this->userLastName, "userEmail" => $this->userEmail, "userHash" => $this->userHash, "userSalt" => $this->userSalt];
        $statement->execute($parameters);
        $this->userId = intval($pdo->lastInsertId());
    }
    /**
     * Delete a User entry.
     * @param \PDO $pdo PDO connection object.
     * @throws \PDOException if mySQL related errors occur.
     * @throws \TypeError if $pdo is not a PDO object.
     **/
    public function delete(\PDO $pdo) {

        if($this->userId == null){
            throw new \PDOException("can't deleted uninserted user",0,null);
        }
        // create query template
        $query = "DELETE FROM user WHERE userId = :userId";
        $statement = $pdo->prepare($query);
        // bind member variables to placeholder in template
        $parameters = ["userId" => $this->userId];
        $statement->execute($parameters);
    }
    /**
     * Updates the User entry in mySQL.
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError if $pdo is not a PDO connection object.
     **/
    public function update(\PDO $pdo) {
        //create query template
        if($this->userId == null){
            throw new \PDOException("can't update un-inserted user",0,null);
        }

        $query = "UPDATE user SET userUsername = :userUsername, userFirstName = :userFirstName, userLastName= :userLastName,userEmail = :userEmail, userHash = :userHash, userSalt = :userSalt WHERE userId = :userId";
        $statement = $pdo->prepare($query);
        // bind member variables to placeholders
        $parameters = ["userId" => $this->userId, "userUsername" => $this->userUsername, "userFirstName"=>$this->userFirstName, "userLastName"=>$this->userLastName, "userEmail" => $this->userEmail, "userHash" => $this->userHash, "userSalt" => $this->userSalt];
        $statement->execute($parameters);
    }
    /**
     * Get user associated with the specified user Id.
     * @param \PDO $pdo a PDO connection object
     * @param int $userId a valid user Id
     * @return User|null User found or null if not found
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError when parameters are not the correct data type.
     **/
    public static function getUserByUserId(\PDO $pdo, int $userId) {
        if($userId <= 0) {
            throw(new \RangeException("user id must be positive."));
        }
        // create query template
        $query = "SELECT userId, userUsername, userFirstName, userLastName, userEmail, userHash, userSalt FROM user WHERE userId = :userId";
        $statement = $pdo->prepare($query);
        // bind the user id to the place holder in the template
        $parameters = ["userId" => $userId];
        $statement->execute($parameters);
        // grab the user from mySQL
        try {
            $user = null;
            $statement->setFetchMode(\PDO::FETCH_ASSOC);
            $row = $statement->fetch();
            if($row !== false) {
                $user = new User($row["userId"], $row["userUsername"], $row["userFirstName"], $row["userLastName"] ,$row["userEmail"], $row["userHash"], $row["userSalt"]);
            }
        } catch(\Exception $exception) {
            // if the row couldn't be converted, rethrow it
            throw(new \PDOException($exception->getMessage(), 0, $exception));
        }
        return ($user);
    }
    /**
     * Get all users associated with the specified username.
     * @param \PDO $pdo a PDO connection object
     * @param string $userUsername name of user being searched for
     * @return User Users found
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError when parameters are not the correct data type.
     **/
    public static function getUserByUserUsername(\PDO $pdo, string $userUsername) {
        $userUsername = trim($userUsername);
        $userUsername = filter_var($userUsername, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        if(empty($userUsername)) {
            throw (new \InvalidArgumentException("user username is invalid"));
        }
        // create query template
        $query = "SELECT userId, userUsername, userFirstName, userLastName, userEmail, userHash, userSalt FROM user WHERE userUsername = :userUsername";
        $statement = $pdo->prepare($query);
        // bind the username to the place holder in the template
        $parameters = ["userUsername" => $userUsername];
        $statement->execute($parameters);
        // grab the user from mySQL
        try {
            $user = null;
            $statement->setFetchMode(\PDO::FETCH_ASSOC);
            $row = $statement->fetch();
            if($row !== false) {
                $user = new User($row["userId"], $row["userUsername"], $row['userFirstName'], $row["userLastName"],$row["userEmail"], $row["userHash"], $row["userSalt"]);
            }
        } catch(\Exception $exception) {
            // if the row couldn't be converted, rethrow it
            throw(new \PDOException($exception->getMessage(), 0, $exception));
        }
        return ($user);
    }


    /**
     * Get all User objects.
     * @param \PDO $pdo PDO connection object
     * @return \SplFixedArray of User objects found or null if none found.
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError when variables are not the correct data type.
     **/
    public static function getAllUsers(\PDO $pdo) {
        //create query template
        $query = "SELECT userId, userUsername, userFirstName, userLastName, userEmail, userHash, userSalt FROM user";
        $statement = $pdo->prepare($query);
        $statement->execute();
        // build an array of user entries
        $users = new \SplFixedArray($statement->rowCount());
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        while(($row = $statement->fetch()) !== false) {
            try {
                $user = new User($row["userId"], $row["userUsername"],$row["userFirstName"], $row["userLastName"] ,$row["userEmail"], $row["userHash"], $row["userSalt"]);
                $users[$users->key()] = $user;
                $users->next();
            } catch(\Exception $exception) {
                // if the row couldn't be converted, rethrow it
                throw(new \PDOException($exception->getMessage(), 0, $exception));
            }
        }
        return ($users);
    }
    public function checkHash(string $password) {
        return (hash_pbkdf2("sha512", $password, $this->getUserSalt(), 262144) === $this->getUserHash());
    }
    /**
     * format state variables for JSON serialization
     * @return array an array with serialized state variables
     **/
    public function jsonSerialize() {
        //$fields = array();
        //array_push($fields, $this->getUserId(), $this->getUserUsername(), $this->getUserEmail(), $this->getUserZipCode(), $this->getUserActivation());
        $fields = ["userId"=>$this->getUserId(), "userUsername"=> $this->getUserUsername(), "userEmail"=>$this->getUserEmail()];
        return ($fields);
    }
}
