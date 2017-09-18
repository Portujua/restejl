<?php
	/**
	* It contains all Session methods
	*
	* @package Configuration
	* @author Eduardo Lorenzo <ejlorenzo19@gmail.com>
	* @since 18/09/2019
	* @license MIT
  */

  /**
	* Session class
	*
	* Contains all methods and configuration needed for the sessions. All methods are static
	*/
	class Session {
    private static $lastToken;

    /**
    * Starts the php session manager
    *
    * @return void
    */
    static private function start() {
      @session_start();
    }

    /**
    * Checks wether the session name is active or not
    *
    * @param String $name - The name of the session
    *
    * @return Boolean - Returns true if exists and it's active, otherwise returns false
    */
    static public function isActive($token = null) {
      Session::start();

      if ($token == null) {
        $token = Session::$lastToken;
      }

      // TODO: more complex validation based on time for example
      return isset($_SESSION[$token]);
    }

    /**
    * Sets the token to be checked on the run database method
    *
    * @return void
    */
    static public function setLastToken($token) {
      Session::$lastToken = $token;
    }

    /**
    * Creates a session
    *
    * @return void
    */
    static public function set($token = null) {
      Session::start();

      if ($token == null) {
        $token = Session::$lastToken;
      }

      $_SESSION[$token] = true;
    }

    /**
    * Removes a session
    *
    * @return void
    */
    static public function unset($token = null) {
      Session::start();

      if ($token == null) {
        $token = Session::$lastToken;
      }

      unset($_SESSION[$token]);
    }

    /**
    * Generates a random session id
    *
    * @return String - The session ID
    */
    static public function generateId() {
      return uniqid();
    }
  }
?>