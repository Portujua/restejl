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
	/**
	* Singleton instance
	* 
	* @var User
	*/
	private static $instance = null;
	
	/**
	* Class constructor
	*
	* @return void
	*/
	private function __construct() {
		
	}

	/**
	* Singleton instance getter
	*
	* @return Auth - The Auth instance
	*/
	public static function getInstance() {
		if (Auth::$instance == null) {
			Auth::$instance = new Auth();
		}

		return Auth::$instance;
	}
	
	/**
	* Adds a new record
	* 
	* @param Array $data - Auth data
	* @return JSONObject - Data result or error message, both as JSON format
	*/
	public function login($data) {
		try {
			$result = QB::table('user')
					->select('id')
					->where('username', '=', $data['username'])
					->where('password', '=', $data['password'])
					->get();

			if (count($result) == 0) {
				return Response::getBaseUnauthorized("Bad login credentials");
			}
			else {
				return $result[0];
			}			
		}
		catch (Exception $ex) {
			return Response::getBaseInternalError($ex->getMessage());
		}
	}
}

?>