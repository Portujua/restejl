<?php
/**
* It contains all Auth Entity methods.
*
* @package Classes
* @author Eduardo Lorenzo <ejlorenzo19@gmail.com>
* @since 30/04/2017
* @license MIT
*/

/**
* Auth class.
*/
class Auth extends BaseEntity {
  public function __construct() {
    parent::__construct();
    $this->getDb()->setIgnoreToken(true);
  }
	/**
	* Adds a new record
	* 
	* @param Array $data - Auth data
	* @return JSONObject - Data result or error message, both as JSON format
	*/
	public function login($data) {
		try {
			$status = $this->getDb()->run(
				'select * from user where username=:username and password=:password',
				[":username" => $data['username'], ":password" => $data['password']]
      );

			if (!$status) {
				return Response::getBaseUnauthorized("Bad login credentials");
      }
      
			return $status;
		}
		catch (Exception $ex) {
			return Response::getBaseInternalError($ex->getMessage());
		}
	}
}

?>