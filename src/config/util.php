<?php
  class Util {
    public static function jsonToArray($json) {
      return json_decode($json, true);
    }

    public static function jsonPdoToArray($jsonPdo) {
      return Util::jsonToArray(json_encode($jsonPdo));
    }
  }
?>