<?php
  class Pageable {
    private $opts;

    public function __construct($queryString) {
      $defaults = [
        "simple" => false,
        "page" => 0,
        "size" => 9999999,
        "keyword" => "",
        "filter" => "",
        "totalElements" => 0
      ];

      $this->opts = BaseEntity::mergeOptions($defaults, $queryString);
    }

    public function setTotalElements($totalElements) {
      $this->opts["totalElements"] = $totalElements;
    }

    public function get() {
      return $this->opts;
    }

    public function getSimple() {
      return filter_var($this->opts["simple"], FILTER_VALIDATE_BOOLEAN);
    }

    public function getPage() {
      return intval($this->opts["page"]);
    }

    public function getSize() {
      return intval($this->opts["size"]);
    }

    public function getKeyword() {
      return $this->opts["keyword"];
    }

    public function getFilter() {
      return $this->opts["filter"];
    }

    public function getTotalElements() {
      return intval($this->opts["totalElements"]);
    }

    public function getTotalPages() {
      return ceil($this->getTotalElements() / $this->getSize());
    }

    public function getOffset() {
      return $this->getSize() * $this->getPage();
    }

    public function getResponse($response) {
      if ($response instanceof Response) {
        return $response;
      }
      
      if ($this->getSimple()) {
        return $response;
      }
      
      $response = [
        "content" => $response,
        "page" => $this->getPage(),
        "size" => $this->getSize(),
        "totalElements" => $this->getTotalElements(),
        "totalPages" => $this->getTotalPages()
      ];

      return $response;
    }
  }
?>