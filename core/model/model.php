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
      $this->is_istatic = $static;
      parent::__construct(array(), ArrayObject::ARRAY_AS_PROPS);

    }

    // This method will return weather a call has been made statically or from an instance
    private static function is_static($argv) {
      return (isset($argv[0]) && is_array($argv[0]) && isset($argv[1]) && is_string($argv[1]) && class_exists($argv[1]));
    }



  }

 ?>
