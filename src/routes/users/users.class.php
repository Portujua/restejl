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
			$result = $this->getDb()->run('select * from user limit '.$pageable->getOffset().', '.$pageable->getSize());

			$pageable->setTotalElements($this->getDb()->getTotalElements('user'));

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
		$this->getDb()->startTransaction();

		try {
			$status = $this->getDb()->run(
				'insert into user (username, password) values (:username, :password)',
				[":username" => $data['username'], ":password" => $data['password']]
			);

			if (!$status) {
				return Response::getBaseInternalError();
			}
			else if ($status instanceof Response) {
				$this->getDb()->rollback();
				return $status;
			}

			$this->getDb()->commit();
			return "Operation completed successfully";
		}
		catch (Exception $ex) {
			$this->getDb()->rollback();
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
			$status = $this->getDb()->run(QueryBuilder::update(User::$table, $data, User::$pk), $data);

			if (!$status) {
				return Response::getBaseInternalError();
			}
			else if ($status instanceof Response) {
				$this->getDb()->rollback();
				return $status;
			}

			$this->getDb()->commit();
			return "Operation completed successfully";
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
			$status = $this->getDb()->run(QueryBuilder::update(User::$table, $data, User::$pk), $data);

			if (!$status) {
				return Response::getBaseInternalError();
			}
			else if ($status instanceof Response) {
				$this->getDb()->rollback();
				return $status;
			}

			$this->getDb()->commit();
			return "Operation completed successfully";
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
			$status = $this->getDb()->run(QueryBuilder::deleteById(User::$table, $data, User::$pk), [User::$pk => $data[User::$pk]]);

			if (!$status) {
				return Response::getBaseInternalError();
			}
			else if ($status instanceof Response) {
				$this->getDb()->rollback();
				return $status;
			}

			$this->getDb()->commit();
			return "Operation completed successfully";
		}
		catch (Exception $ex) {
			$this->getDb()->rollback();
			return Response::getBaseInternalError($ex->getMessage());
		}
	}
}

?>