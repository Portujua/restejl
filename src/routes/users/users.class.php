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
		"id" => null,
		"username" => null,
		"password" => null
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
	static public $tableName = "user";
	
	/**
	* User table in database
	*
	* @var QB Table
	*/
	static public $table;

	/**
	* Class constructor
	*
	* @return void
	*/
	public function __construct() {
		parent::__construct();
		User::$table = QB::table(User::$tableName);
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
	static public function putPayload($data) {
		return BaseEntity::mergeOptions(User::$base, $data, true);
	}

	/**
	* Generates the patch payload array including the PK and all fields inside $data.
	*
	* @category Classes
	* @param Variable $pkVal - The PK value
	* @param Array $data - The PATCH method data
	* @return Array - Returns an array with $vals values copied into the base structure
	*/
	static public function patchPayload($pkVal, $data) {
		$patch = BaseEntity::mergeOptions([], [User::$pk => $pkVal], true);
		return BaseEntity::mergeOptions($patch, $data, true);
	}

	/**
	* Lists all records
	* 
	* @return JSONObject - Data result or error message, both as JSON format
	*/
	public function listAll($pageable) {
		try {
			$result = $this->getDb()->run(
				User::$table->limit($pageable->getSize())->offset($pageable->getOffset())
			);

			$pageable->setTotalElements(User::$table->count());

			return $pageable->getResponse($result);
		}
		catch (Exception $ex) {
			return Response::getBaseInternalError($ex->getMessage());
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
		try {
			$id = User::$table->insert($data);
			return User::$table->where(User::$pk, '=', $id)->get();
		}
		catch (Exception $ex) {
			return Response::getBaseInternalError($ex->getMessage());
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
			User::$table->where(User::$pk, $data[User::$pk])->update($data);
			return User::$table->where(User::$pk, '=', $data[User::$pk])->get();
		}
		catch (Exception $ex) {
			$this->getDb()->rollback();
			return Response::getBaseInternalError($ex->getMessage());
		}
	}

	/**
	* Partially Updates a record
	* 
	* @param Array $data - User data structure
	* @return JSONObject - Data result or error message, both as JSON format
	*/
	public function patch($data) {
		/** We start a transaction in case something fails */
		$this->getDb()->startTransaction();

		try {
			User::$table->where(User::$pk, $data[User::$pk])->update($data);
			return User::$table->where(User::$pk, '=', $data[User::$pk])->get();
		}
		catch (Exception $ex) {
			$this->getDb()->rollback();
			return Response::getBaseInternalError($ex->getMessage());
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
			$r = User::$table->where(User::$pk, $data[User::$pk])->delete();
			return "Operation completed successfully";
		}
		catch (Exception $ex) {
			$this->getDb()->rollback();
			return Response::getBaseInternalError($ex->getMessage());
		}
	}
}

?>