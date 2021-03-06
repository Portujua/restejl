<?php
  /**
	* It contains all Filter methods.
	*
	* @package Base
	* @author Eduardo Lorenzo <ejlorenzo19@gmail.com>
	* @since 21/09/2017
	* @license MIT
  */
  
  /**
	* Filter class
	* 
	* @method String getField()
  * @method String getOperator()
  * @method String getValue()
  * @method Array extractFilters(String $s)
	*/
  class Filter {
    private $field;
    private $operator;
    private $value;

    public function __construct($s) {
      preg_match_all('/^([A-Za-z\.\_]+)([\<\>\:\%]{1,2})(.+)/', $s, $matches);

      $this->field = $matches[1][0];
      $this->operator = $matches[2][0];
      $this->value = $matches[3][0];

      // Convert colon into equal
      $this->operator = str_replace(':', '=', $this->operator);

      // Convert percentage into LIKE
      if ($this->operator == '%') {
        $this->operator = 'like';
        $this->value = '%'.$this->value.'%';
      }
    }

    public function getField() {
      return $this->field;
    }

    public function getOperator() {
      return $this->operator;
    }

    public function getValue() {
      return $this->value;
    }

    public static function extractFilters($s) {
      $filters = [];
      
      // TODO: Fix the case when there's a comma or a colon in any of the fields
      foreach (explode(',', $s) as $filter) {
        $filters[] = new Filter($filter);
      }

      return $filters;
    }
  }
?>