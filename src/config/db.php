<?php
	/**
	* It contains all the Database Methods
	*
	* @package Configuration
	* @author Eduardo Lorenzo <ejlorenzo19@gmail.com>
	* @since 30/04/2017
	* @license MIT
	*/

	/**
	* Database class
	*
	* Contains all methods and configuration needed for the database
	*/
	class Db {
		/**
		* Connection string
		* 
		* Contains: database name and host
		*
		* @var String
		*/
		private $dsn;

		/**
		* Database username
		*
		* @var String
		*/
		private $username;

		/**
		* Database password
		*
		* @var String
		*/
		private $password;

		/**
		* Database connection
		*
		* @var PDO
		*/
		private $db;

		/**
		* Class constructor
		*
		* It opens automatically a connection to the database
		*
		* @return void
		*/
		public function __construct() {
			$this->connect();
			$this->ignoreToken = false;
		}

		/**
		* Connects to the database
		*
		* @return void
		*/
		private function connect() {
			$connect_to = (($_SERVER['HTTP_HOST'] == 'localhost') ? 'local' : 'main');
            
      if ($connect_to == "local") {
        $this->username = "root";
        $this->password = "21115476";
        $this->dsn = "mysql:dbname=test;host=localhost;charset=UTF8;";
      }
      else if ($connect_to == "main") {
      	$this->username = "salazars_eduardo";
        $this->password = "21115476";
        $this->dsn = "mysql:dbname=test;host=localhost;charset=UTF8;";
      }

			$this->db = new PDO($this->dsn, $this->username, $this->password);       
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		/**
		* Runs a query
		*
		* Runs a query with the current database connection and return it result
		*
		* @param String $sql - The database query 
		* @param Array $params - Array with values to be replaced in the query 
		* @param Int $fetchType - PDO constant to parse the rows fetched 
		*
		* @return Array|Boolean - Returns the array containing the query result or the query status
		*/
		public function run($sql, $params = [], $fetchType = PDO::FETCH_OBJ) {
			if (!Session::isActive() && !$this->ignoreToken) {
				return Response::getBaseUnauthorized();
			}

			$q = $this->db->prepare($sql);

			$run_status = $q->execute($params);

			$words = explode(' ', $sql);

			if (strtolower($words[0]) == 'select') {
				return $q->fetchAll($fetchType);
			}
			else {
				return $run_status;
			}
		}

		public function getTotalElements($table, $selector = "count(*)", $params = []) {
			$result = json_decode(json_encode($this->run("select $selector from $table", $params)), true)[0];
			$total = 0;

			foreach ($result as $key => $val) {
				$total += $val;
			}
			
			return $total;
		}

		/**
		* Starts a transaction on the current database connection
		*
		* @return void
		*/
		public function startTransaction() {
			$this->db->query("start transaction");
		}

		/**
		* Commits the changes on the current database transaction
		*
		* @return void
		*/
		public function commit() {
			$this->db->query("commit");
		}

		/**
		* Rolls back the changes on the current database transaction
		*
		* @return void
		*/
		public function rollback() {
			$this->db->query("rollback");
		}

		public function setIgnoreToken($value) {
			$this->ignoreToken = $value;
		}
	}

	/**
	* QueryBuilder class
	*
	* Contains some useful functions to programmatically generate queries
	*/
	class QueryBuilder {
		/**
		* Generates an update query 
		*
		* Query generated is based on the $params parameter
		* 
		* @param String $table - The table name in the database 
		* @param Array $params - All fields that will be updated
		* @param String $pk - Primary key field name 
		*
		* @return String - Returns the full update query
		*/
		static public function update($table, $params, $pk) {
			$q = "update $table set ";

			foreach ($params as $key => $val) {
				if ($key == $pk) continue;

				$q .= "$key=:$key,";
			}

			/** Remove the last comma */
			$q = substr($q, 0, -1);

			$q .= " where $pk=:$pk";

			return $q;
		}

		/**
		* Generates an delete query 
		*
		* Query generated is based on the $params parameter
		* 
		* @param String $table - The table name in the database 
		* @param Array $params - All fields that will be updated
		* @param Boolean $useLike - Indicates whether to use LIKE or EQUAL operator
		*
		* @return String - Returns the full delete query
		*/
		static public function delete($table, $params, $useLike = false) {
			$q = "delete from $table where 1=1";

			foreach ($params as $key => $val) {
				if (!$useLike) {
					$q .= " and $key=:$key";
				}
				else {
					$q .= " and $key like '%$val%'";
				}
			}

			return $q;
		}

		/**
		* Generates an delete query 
		*
		* Query generated is based on the $params parameter
		* 
		* @param String $table - The table name in the database 
		* @param Array $params - All fields that will be updated
		* @param Boolean $useLike - Indicates whether to use LIKE or EQUAL operator
		*
		* @return String - Returns the full delete query
		*/
		static public function deleteById($table, $params, $pk) {
			$q = "delete from $table where 1=1";

			foreach ($params as $key => $val) {
				if ($key == $pk) {
					$q .= " and $key=:$key";
				}
			}

			return $q;
		}
	}