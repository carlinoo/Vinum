<?php
  require('core/model/traits/flowing_query.php');

  class Model extends ArrayObject {
    use FlowingQuery;

    const STATIC_CALL = true;
    const INSTANCE_CALL = false;

    private $obj;
    private $class;
    private $is_static;

    public function __construct($klass = null, $static = false, $object = null) {
      $this->obj = $object;
      $this->class = $klass;
      $this->is_static = $static;
      parent::__construct(array(), ArrayObject::ARRAY_AS_PROPS);
    }

    // This method will return weather a call has been made statically or from an instance
    private function is_static() {
      return ($this->obj == null && $this->is_static && is_string($this->class) && class_exists($this->class));
    }



  }

 ?>
