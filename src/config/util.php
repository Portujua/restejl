<?php
  class Util {
    public static function jsonToArray($json) {
      return json_decode($json, true);
    }

    public static function jsonPdoToArray($jsonPdo) {
      return Util::jsonToArray(json_encode($jsonPdo));
    }

    /**
		* Merges an array with values into a base array.
		*
		* Copies $vals into $base overriding base values.
		* 
		* @param Array $base - Base array
		* @param Array $vals - Values array
		* @param Boolean $addIfUndefined - It determines if new keyvalues contained in $vals should be added into base
		*
		* @return Array - Returns the modified data array
		*/
		public static function mergeOptions($base = [], $vals = [], $addIfUndefined = false) {
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
  }
?>