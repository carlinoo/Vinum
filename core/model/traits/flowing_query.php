<?php

// Represents a collection for calling a database.
// For the static methods where arguments are passed, the arguments will be accesses as func_get_args()[0]. func_get_args()[1] will contain the name of the class calling the method

trait FlowingQuery {


  // This class method will return a list of objects retrieved from the database
  public static function where() {
    // get all arguments
    $class = $this->class;
    $argv = func_get_args();
    $number_of_args = count($argv);

    if ($number_of_args < 1) {
      return null;
    }

    $db = DB::connect();
    $items = new Model();

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





  // This will retrieve all columns from a table
  public static function get_column_names() {

    $db = DB::connect();

    $result = $db->prepare('DESCRIBE ' . $this->class);
    $result->execute();
    $result = $result->fetchAll(PDO::FETCH_COLUMN);

    return $result;
  } // end get_column_names()






  // This method will update the attributes of a certain class on the database
  public function update_attributes() {

    $db = DB::connect();

    $conditions = $this->style_sql_attributes($this->arguments);

    $update = $db->prepare('UPDATE ' . $class . ' SET' . $conditions . 'WHERE id = :id');
    $update->bindParam(':id', $this->id);
    $result = $update->execute();

    return $result;
  }





  // This method will order the array using the attribute of the object passed in either "ASC" or "DESC"
  public function order() {
    // Set the default values
    $attribute = 'id';
    $order = 'ASC';

    $class = $this->class;
    $argv = func_get_args();
    $argc = count($argv);


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
    $class = $this->class;
    $argv = func_get_args();

    $all = new Model();
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
    $class = $this->class;
    $argv = func_get_args();

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

    $class = $this->class;
    $argv = func_get_args();

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
    $items = new Model();

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






  // This class method will return the first item of a table sorted by id
  public static function first() {
    $class = $this->class;
    $argv = func_get_args();

    // If argument isnt passed and it isnt an integer, limit will be 1 
    $limit = (isset($argv[0]) && is_int($argv[0])) ? $argv[0] : 1;

    $db = DB::connect();

    $obj = $db->prepare('SELECT * FROM ' . $class . ' ORDER BY id ASC LIMIT ' . $limit);
    $obj->execute();

    $result = $obj->fetchAll(PDO::FETCH_ASSOC);

    $items = new Model();

    // If more than one object is being looked for
    if (count($result) > 1) {
      foreach ($result as $key) {
        $item = new $class($key);
        $items[] = $item;
      }

      return $items;
    }

    // if there is only one object, return the only only object of the array
    return new $class($result[0]);
  }






  // This method will return the last item of a table sorted by id
  public static function last() {
    $class = $this->class;
    $argv = func_get_args();

    // If argument isnt passed and it isnt an integer, limit will be 1
    $limit = (isset($argv[0]) && is_int($argv[0])) ? $argv[0] : 1;

    $db = DB::connect();

    $obj = $db->prepare('SELECT * FROM ' . $class . ' ORDER BY id DESC LIMIT ' . $limit);
    $obj->execute();

    $items = new Model();

    $result = $obj->fetchAll(PDO::FETCH_ASSOC);

    // If more than one object is being looked for
    if (count($result) > 1) {
      foreach ($result as $key) {
        $item = new $class($key);
        $items[] = $item;
      }

      return $items;
    }

    // if there is only one object, return the only only object of the array
    return new $class($result[0]);

  }






  // This method checks if it has a a value in the array
  public function has($value) {
    return in_array($value, $this->getArrayCopy());
  }




  // This method will check if an model exists on the database
  public function does_exist() {
    $class = $this->class;
    $argv = func_get_args();

    if ($value == null) {
      $value = 'id';
    }

    // If the object doesnt have the attribute passed on
    if (!$this->has_attribute($value)) {
      return false;
    }

    $response = $db->prepare('SELECT * FROM ' . $class . ' WHERE ' . $value . ' = :id');
    $response->bindParam(':id', $this->$value);
    $response->execute();

    return (!empty($response->fetch(PDO::FETCH_ASSOC)));
  }





  // ******************************* //
  // ****** PROTECTED METHODS ****** //
  // ******************************* //


  // This method will style attribute conditions for a sql statement
  protected function style_sql_attributes($attributes = null) {

    if ($attributes == null) {
      $attributes = get_object_vars($this->obj);
    }

    $class = $this->class;
    $conditions = '';

    // We loop through the attributes and we create the conditions array
    foreach ($attributes as $key => $value) {
      // We first check if the table has the attribute $key
      if ($class::has_attribute($key)) {

        // We cannot update the id of the record
        if ($key == 'id') {
          continue;
        }

        // We cannot update an $value if it's an object. If it is, update the id of it
        if (gettype($value) == 'object') {
          $value = $value->id;
        } elseif (gettype($value) == 'string') {
          $value = "'" . $value . "'";
        } elseif (gettype($value) == 'boolean') {
          $value = "'" . $value . "'";
        }

        $conditions = $conditions . " $key=$value,";
      } else {
        continue;
      }
    }

    // we replace the last ',' for an space
    $conditions = substr_replace($conditions, " ", -1);

    return $conditions;

  }






  // This function will check if a class has certain attributes
  protected static function has_attribute() {
    $class = $this->class;
    $argv = func_get_args();
    $attribute = $argv[0];

    $attributes = $class::get_column_names();

    foreach ($attributes as $value) {
      if ($value == $attribute) {
        return true;
      }
    }

    return false;
  }


}

 ?>
