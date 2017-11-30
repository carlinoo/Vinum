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

    echo get_called_class();
    echo "<br><br>";
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

}

 ?>
