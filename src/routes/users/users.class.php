<?php
/**
* It contains all User Entity methods.
*
* @package Classes
* @author Eduardo Lorenzo <ejlorenzo19@gmail.com>
* @since 30/04/2017
* @license MIT
*/

/**
* User class.
*/
class User extends BaseEntity {
	/**
	* User base structure
	* 
	* @var Array
	*/
	static public $base = [
		"username" => "",
		"password" => ""
	];

	/**
	* User primary key in database
	*
	* @var String
	*/
	static public $pk = "id";

	/**
	* User table in database
	*
	* @var String
	*/
	static public $table = "user";

	/**
	* Class constructor
	*
	* @return void
	*/
	public function __construct() {
		parent::__construct();
	}

	/**
	* Merges passed data into base data structure. If $addNew is true it will add new fields if not present.
	*
	* @param Array $vals - Values to be replaced in the base structure
	* @param Boolean $addNew - Indicates if it should add new key values when not present
	* @return Array - Returns an array with $vals values copied into the base structure
	*/
	static public function createPayload($vals = [], $addNew = false) {
		return BaseEntity::mergeOptions(User::$base, $vals, $addNew);
	}

	/**
	* Generates the put payload array including the PK and all fields inside $data.
	*
	* @category Classes
	* @param Variable $pkVal - The PK value
	* @param Array $data - The PUT method data
	* @return Array - Returns an array with $vals values copied into the base structure
	*/
	static public function putPayload($pkVal, $data) {
		$put = BaseEntity::mergeOptions([], [User::$pk => $pkVal], true);
		return BaseEntity::mergeOptions($put, $data, true);
	}

	/**
	* Lists all records
	* 
	* @return JSONObject - Data result or error message, both as JSON format
	*/
	public function listAll() {
		try {
			return $this->parseResponse($this->getDb()->run('select * from user'));
		}
		catch (Exception $ex) {
			return $this->parseResponse($ex->getMessage(), true);
		}
	}

	/**
	* Adds a new record
	* 
	* @param Array $data - User data structure
	* @return JSONObject - Data result or error message, both as JSON format
	*/
	public function add($data) {
		/** We start a transaction in case something fails */
		$this->getDb()->startTransaction();

		try {
			$status = $this->getDb()->run(
				'insert into user (username, password) values (:username, :password)',
				[":username" => $data['username'], ":password" => $data['password']]
			);

			if (!$status) {
				throw new Exception("There has been an error");
			}

			$this->getDb()->commit();
			return $this->parseResponse("Operation completed successfully");
		}
		catch (Exception $ex) {
			$this->getDb()->rollback();
			return $this->parseResponse($ex->getMessage(), true);
		}
	}

	/**
	* Updates a record
	* 
	* @param Array $data - User data structure
	* @return JSONObject - Data result or error message, both as JSON format
	*/
	public function update($data) {
		/** We start a transaction in case something fails */
		$this->getDb()->startTransaction();

		try {
			$status = $this->getDb()->run(QueryBuilder::update(User::$table, $data, User::$pk), $data);

			if (!$status) {
				throw new Exception("There has been an error");
			}

			$this->getDb()->commit();
			return $this->parseResponse("Operation completed successfully");
		}
		catch (Exception $ex) {
			$this->getDb()->rollback();
			return $this->parseResponse($ex->getMessage(), true);
		}
	}

	/**
	* Deletes a record
	* 
	* @param Array $data - User data structure
	* @return JSONObject - Data result or error message, both as JSON format
	*/
	public function delete($data) {
		/** We start a transaction in case something fails */
		$this->getDb()->startTransaction();

		try {
			$status = $this->getDb()->run(QueryBuilder::delete(User::$table, $data, true), $data);

			if (!$status) {
				throw new Exception("There has been an error");
			}

			$this->getDb()->commit();
			return $this->parseResponse("Operation completed successfully");
		}
		catch (Exception $ex) {
			$this->getDb()->rollback();
			return $this->parseResponse($ex->getMessage(), true);
		}
	}
}

?>