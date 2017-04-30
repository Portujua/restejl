<?php
	/**
	* It contains all User Entity methods.
	*
	* @package Base
	* @author Eduardo Lorenzo <ejlorenzo19@gmail.com>
	* @since 30/04/2017
	* @license MIT
	*/

	/**
	* Base entity class for common methods across entities.
	* 
	* @method Db getDb()
	* @method Array setData(Array $base, Array $vals)
	* @method Array mergeOptions(Array $base, Array $vals, Boolean $addIfUndefined)
	* @method Array|String parseResponse(Array|String $pdoResponse, Boolean $isError, Boolean $jsonParse)
	*/
	class BaseEntity {
		/**
		* Database connection variable.
		*
		* Database connection will prevail as long as class instantiation lives.
		*
		* @var Db
		*/
		private $db;

		/**
		* Entity current data.
		*
		* It depends on the class that extends from this class.
		*
		* @deprecated No current use.
		*
		* @var Array
		*/
		public $data;

		/**
		* Class constructor
		*
		* @return void
		*/
		public function __construct() {
			$this->db = new Db();
			$this->slim = new \Slim\Slim;
			$this->data = [];
		}

		/**
		* Returns database connection.
		* 
		* @return Db - Returns the current opened database connection
		*/
		public function getDb() {
			return $this->db;
		}

		/**
		* Sets the data for this entity
		*
		* @deprecated No current use.
		* 
		* @param Array $base - Base array which contain entity attributes
		* @param Array $vals - Values array to be overwritten in the base array
		*
		* @return Array - Returns the modified data array
		*/
		public function setData($base = [], $vals = []) {
			$this->data = BaseEntity::mergeOptions($base, $vals);
			return $this->data;
		}

		/**
		* Merges an array with values into a base array.
		*
		* Copies $vals into $base overriding base values.
		* 
		* @param Array $base - Base array
		* @param Array $vals - Values array
		* @param Boolean $addIfUndefined - It determines if new keyvalues contained in $vals should be added into base
		*
		* @return Array - Returns the modified data array
		*/
		static public function mergeOptions($base = [], $vals = [], $addIfUndefined = false) {
			foreach ($base as $keyb => $valb) {
				foreach ($vals as $keyv => $valv) {
					if ($keyb == $keyv) {
						$base[$keyb] = $valv;
					}
				}
			}

			if ($addIfUndefined) {
				foreach ($vals as $keyv => $valv) {
					$exists = false;

					foreach ($base as $keyb => $valb) {
						if ($keyb == $keyv) {
							$exists = true;
							$base[$keyb] = $valv;
						}
					}

					if (!$exists) {
						$base[$keyv] = $valv;
					}
				}
			}

			return $base;
		}

		/**
		* Generates a response for the API call.
		* 
		* @todo Fix set response status code
		* 
		* @param Array|String $pdoResponse - The Db::run return value
		* @param Boolean $isError - Indicates whether is an error response or not
		* @param Boolean $jsonParse - Indicates if response should be parsed into json format
		*
		* @return JSONObject|String - Returns the JSON formatted response or the plain text response
		*/
		public function parseResponse($pdoResponse, $isError = false, $jsonParse = true) {
			if (!$isError) {
				$this->slim->response()->status(STATUS_OK);
				return $jsonParse ? json_encode(["status" => 200, "data" => $pdoResponse]) : $pdoResponse;
			}
			else {
				$this->slim->response()->status(STATUS_INTERNAL_SERVER_ERROR);
				return $jsonParse ? json_encode(["status" => 500, "error" => $pdoResponse]) : $pdoResponse;
			}
		}
	}

?>