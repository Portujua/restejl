<?php
	/**
	* It contains all Response methods
	*
	* @package Configuration
	* @author Eduardo Lorenzo <ejlorenzo19@gmail.com>
	* @since 18/09/2019
	* @license MIT
  */

  /**
	* Base response class
	*
	* Contains the methods related to a basic http response
	*/
	class Response {
		private $slim;
		private $data;
		private $status;

		public function __construct($data, $status = STATUS_OK) {
			$this->slim = null;
			$this->data = $data instanceof Response ? $data->getData() : $data;
			$this->status = $data instanceof Response ? $data->getStatus() : $status;
		}

		public function getData() {
			return $this->data;
		}

		public function getStatus() {
			return $this->status;
		}

		public function setSlim($slim) {
			$this->slim = $slim;
		}

		public function getResponse($jsonParse = true) {
			if ($jsonParse instanceof Response) {
				return $jsonParse->getResponse();
			}

			$this->slim->response()->status($this->status);

			return $jsonParse ? json_encode(["status" => $this->status, "data" => $this->data]) : $this->data;
		}

		public static function getBaseUnauthorized($message = "Error trying to access private content.", $jsonParse = true) {
			return new Response($message, STATUS_UNAUTHORIZED);
		}

		public static function getBaseInternalError($message = "There has been an error.", $jsonParse = true) {
			return new Response($message, STATUS_INTERNAL_SERVER_ERROR);
		}
  }
?>