<?php
  class AuthRepository {
    private $table;

    public function __construct() {
      $this->table = QB::table('user');
    }
      
    /**
    * Login
    * 
    * @param Array $data - Auth data
    * @return JSONObject - Data result or error message, both as JSON format
    */
    public function login($data) {
      $result = $this->table
          ->select('id')
          ->where('username', '=', $data['username'])
          ->where('password', '=', $data['password'])
          ->get();

      if (count($result) == 0) {
        throw new BadCredentialsException();
      }
      else {
        return $result[0];
      }
    }
  }
?>