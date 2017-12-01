<?php

// Represents a collection for calling a database.
// For the static methods where arguments are passed, the arguments will be accesses as func_get_args()[0]. func_get_args()[1] will contain the name of the class calling the method

class FlowingQuery extends ArrayObject {

  // override the constructor
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





  // this class method will get all the objects of a table called from the child and return them
  public static function all() {
    $argv = func_get_arg(0);
    $class = func_get_arg(1);

    $all = new FlowingQuery();
    $db = DB::connect();

    // We dont bind the param $class as it is only the caller of this function
    $results = $db->prepare('SELECT * FROM ' . $class);
    $results->execute();
    $results = $results->fetchAll(PDO::FETCH_ASSOC);

    // We create a list of the objects from the database results
    foreach($results as $obj) {
      $item = new $class($obj);
      $all[] = $item;
    }

    return $all;
  } // end all()








  // This method will return an object queried from the database
  public static function find() {
    $argv = func_get_arg(0);
    $class = func_get_arg(1);

    // Make sure they send at least one parameter
    if (count($argv) < 1) {
      throw new Exception("$class::find() expects at least one parameter", 1);
      return;
    }

    $db = DB::connect();

    // If $column is set, add change the condition
    if (!isset($argv[1])) {
      $column = "id";
    } else {
      $column = $argv[1];
    }

    $id = $argv[0];

    // Check that $column exists on the table
    if (!in_array($column, $class::get_column_names())) {
      return null;
    }

    $result = $db->prepare('SELECT * FROM ' . $class . ' WHERE ' . $column . ' = :id');
    $result->bindParam(':id', $id);

    $result->execute();

    $result = $result->fetch(PDO::FETCH_ASSOC);

    // If the is no result
    if (!$result) {
      return null;
    }

    return new $class($result);
  } // end find()





  // This function will get all the objects from the database selecting only the fields passed
  public static function select() {

    $argv = func_get_arg(0);
    $class = func_get_arg(1);

    // If not arguemnts passed
    if (count($argv) < 1) {
      throw new Exception("$class::select() expects at least one argument", 1);
      return null;
    }

    // get all columns for the class
    $table_columns = $class::get_column_names();

    $selects = "";

    // Check if all arguments passed are
    foreach ($argv as $argument) {
      if (!in_array($argument, $table_columns)) {
        throw new Exception("Column $argument does not exist as $class.$argument", 1);
        return null;
      }
      // If it does belong on the array, add it to the string separated by a comma
      $selects .= " $argument,";
    }

    // Get rid of the last comma by a space
    $selects = substr_replace($selects, " ", -1);

    $db = DB::connect();
    $items = new FlowingQuery();

    $sql = $db->prepare("SELECT $selects FROM $class");
    $sql->execute();

    // Get all the items
    $results = $sql->fetchAll(PDO::FETCH_ASSOC);

    // We create a list of the objects from the database results
    foreach($results as $obj) {
      $item = new $class($obj);
      $items[] = $item;
    }

    return $items;

  }





  // This method checks if it has a a value in the array
  public function has($value) {
    return in_array($value, $this->getArrayCopy());
  }





  // This class method will return the number of records of a table where the $attribute is not null
  // public static function count() {
  //   $argv = func_get_arg(0);
  //   $class = func_get_arg(1);
  //
  //   if (!isset($argv[0])) {
  //     $attribute = '*';
  //   } else {
  //     $attribute = $argv[0];
  //
  //     // Check if the table has the $attribute
  //     if (!$class::has_attribute($attribute)) {
  //       return null;
  //     }
  //   }
  //
  //   $class = get_called_class();
  //   $db = DB::connect();
  //
  //   $count = $db->prepare("SELECT COUNT(" . $attribute . ") AS count FROM $class");
  //   $count->execute();
  //   $result = $count->fetch(PDO::FETCH_ASSOC);
  //
  //   // return the count
  //   return (int)$result['count'];
  // }




  // This method will return weather a call has been made statically or from an instance
  private static function is_static($argv) {
    return (isset($argv[0]) && is_array($argv[0]) && isset($argv[1]) && is_string($argv[1]) && class_exists($argv[1]));
  }
}

 ?>
