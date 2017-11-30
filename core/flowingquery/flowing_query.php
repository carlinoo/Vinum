<?php

// Represents a collection for calling a database.
// For the static methods where arguments are passed, the arguments will be accesses as func_get_args()[0]. func_get_args()[1] will contain the name of the class calling the method

class FlowingQuery extends ArrayObject {


  public function __construct() {
    parent::__construct(array(), ArrayObject::ARRAY_AS_PROPS);
  }





  // This class method will return a list of objects retrieved from the database
  public static function where() {
    // get all arguments
    $argv = func_get_args()[0];
    $number_of_args = count($argv);

    if ($number_of_args < 1) {
      return null;
    }

    $class = func_get_args()[1];
    $db = DB::connect();
    $items = new FlowingQuery();

    $condition = $argv[0];

    $results = $db->prepare('SELECT * FROM ' . $class . ' WHERE ' . $condition);

    // Bind all arguments to the '?'
    for ($i = 1; $i < $number_of_args; $i++) {
      $results->bindValue($i, $argv[$i]);
    }

    $results->execute();
    $results = $results->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
      $item = new $class($result);
      $items[] = $item;
    }

    return $items;
  }





  // This method will order the array using the attribute of the object passed in either "ASC" or "DESC"
  public function order() {
    // Set the default values
    $attribute = 'id';
    $order = 'ASC';

    $argc = func_num_args();
    $argv = func_get_args();


    // If they pass only one parameter, find out if they are passing the order or the attribute
    if ($argc == 1 && gettype($argv[0]) == 'string') {
      // If it is either 'ASC' or 'DESC' set it as the order, if not use the passed string as an attribute
      if (strtoupper($argv[0]) == 'ASC' || strtoupper($argv[0]) == 'DESC') {
        $order = strtoupper($argv[0]);
      } else {
        $attribute = $argv[0];
      }

    // If they pass two strings as arguments
    } elseif ($argc == 2 && gettype($argv[0]) == 'string' && gettype($argv[1]) == 'string') {
      $attribute = $argv[0];
      $order = strtoupper($argv[1]);
    }

    $arr = $this->getArrayCopy();

    // Now sort by the order and attribute passed on
    usort($arr, function($a, $b) use ($attribute, $order) {

      if ($a->$attribute == $b->$attribute) {
        return 0;
      }

      // Return depending on the order
      if ($order == 'ASC') {
        return ($a->$attribute < $b->$attribute) ? -1 : 1;
      } else {
        return ($a->$attribute < $b->$attribute) ? 1 : -1;
      }
    });

    return $arr;
  }




  // This function will get all the objects from the database selecting only the fields passed
  // TODO FIXME not finished
  public static function select() {

    $argc = func_num_args();
    $argv = func_get_arg(0);
    $class = func_get_arg(1);

    $db = DB::connect();
    $items = new FlowingQuery();


  }




}

 ?>
