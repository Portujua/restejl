<?php
	class Db {
		private $dsn = "mysql:dbname=test;host=localhost;charset=UTF8;";
		private $username = "root";
		private $password = "21115476";

		private function connect() {
			$db = new PDO($this->dsn, $this->username, $this->password);       
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      return $db;
		}

		public function run($sql, $params = [], $fetchType = PDO::FETCH_OBJ) {
			$db = $this->connect();

			$q = $db->prepare($sql);

			$q->execute($params);

			$words = explode(' ', $sql);

			if (strtolower($words[0]) == 'select') {
				return $q->fetchAll($fetchType);
			}
			else {
				return $q;
			}
		}
	}