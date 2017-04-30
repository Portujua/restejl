<?php
	class Db {
		private $dsn = "mysql:dbname=test;host=localhost;charset=UTF8;";
		private $username = "root";
		private $password = "21115476";
		private $db;

		public function __construct() {
			$this->connect();
		}

		private function connect() {
			$this->db = new PDO($this->dsn, $this->username, $this->password);       
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		public function run($sql, $params = [], $fetchType = PDO::FETCH_OBJ) {
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

		public function startTransaction() {
			$this->db->query("start transaction");
		}

		public function commit() {
			$this->db->query("commit");
		}

		public function rollback() {
			$this->db->query("rollback");
		}
	}

	class QueryBuilder {
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

		static public function delete($table, $params, $useLike) {
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
	}