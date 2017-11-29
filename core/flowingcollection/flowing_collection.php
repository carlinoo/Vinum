<?php

// Represents a collection for calling a database

class FlowingCollection extends ArrayObject {

  public function __construct() {
    parent::__construct(array(), ArrayObject::ARRAY_AS_PROPS);
  }
}

 ?>
