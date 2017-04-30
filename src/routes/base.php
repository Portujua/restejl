<?php

	class BaseEntity {
		private $db;
		private $slim;
		public $data;

		public function __construct() {
			$this->db = new Db();
			$this->slim = new \Slim\Slim;
			$this->data = [];
		}

		public function getDb() {
			return $this->db;
		}

		public function setData($base = [], $vals = []) {
			$this->data = BaseEntity::mergeOptions($base, $vals);
			return $this->data;
		}

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
		* @todo Fix set response status code
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