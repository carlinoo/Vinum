<?php

  abstract class Vinum {


    // This constructor all the Models will use. It takes an hash and it
    public function __construct($params = null) {
      if ($params == null) {
        return;
      }

      // Loop thought all values and create the values
      foreach ($params as $key => $value) {
        $this->$key = $value;

        // If the $key finishes in '_id', it means its an object.
        // We create an object of the type $key taking away '_id'
        if (substr($key, -3) === '_id') {
          $obj = substr($key, 0, -3);
          $class = ucfirst($obj);

          if (class_exists($class)) {
            $this->$obj = $class::find($value);
          }
        }
      }
    }





    // This method will be called if a class method is not define
    public static function __callStatic($method, $argv) {
      $class = get_called_class();

      // If the method exists, return
      if (method_exists($class, $method)) {
        return;
      }

      // Check if the method exists
      if (!method_exists("FlowingQuery", $method)) {
        throw new Exception("Static method $class::$method does not exist", 1);

        return;
      }

      // We pass the caller of the class plus the extra parameters
      $arguments = [];
      $arguments[0] = $argv;
      $arguments[1] = $class;


      // call the method from the FlowingQuery and return the output
      return call_user_func_array(["FlowingQuery", $method], $arguments);

    }





    // this class method will get all the objects of a table called from the child and return them
    public static function all() {
      $class = get_called_class();
      $all = new FlowingQuery();
      $db = DB::connect();

      // We dont bind the param $class as it is only the caller of this function
      $results = $db->prepare('SELECT * FROM ' . $class);
      $results->execute();
      $results = $results->fetchAll();

      // We create a list of the objects from the database results
      foreach($results as $obj) {
        $item = new $class($obj);
        $all[] = $item;
      }

      return $all;
    } // end all()





    // This method will return an object queried from the database
    public static function find($id, $column = false) {
      $class = get_called_class();
      $db = DB::connect();

      // If $column is set, add change the condition
      if (!$column) {
        $column = "id";
      }

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









    // This class method will return the number of records of a table where the $attribute is not null
    public static function count($condition = null, $attribute = null) {
      if ($attribute == null) {
        $attribute = '*';
      } else {
        // Check if the table has the $attribute
        if (!self::has_attribute($attribute)) {
          return null;
        }
      }


      // If the condition is equal to null, get all
      if ($condition == null) {
        $condition = "WHERE 1 = 1";
      }

      $class = get_called_class();
      $db = DB::connect();

      $count = $db->prepare("SELECT COUNT(" . $attribute . ") AS count FROM $class WHERE $condition");
      $count->execute();
      $result = $count->fetch(PDO::FETCH_ASSOC);

      // return the count
      return (int)$result['count'];
    }




    // This will retrieve all columns from a table
    public static function get_column_names() {
      $class = get_called_class();
      $db = DB::connect();

      $result = $db->prepare('DESCRIBE ' . $class);
      $result->execute();
      $result = $result->fetchAll(PDO::FETCH_COLUMN);

      return $result;
    } // end get_column_names()







    // This function will check if a class has certain attributes
    private static function has_attribute($attribute) {
      $class = get_called_class();
      $attributes = $class::get_column_names();

      foreach ($attributes as $value) {
        if ($value == $attribute) {
          return true;
        }
      }

      return false;
    }






    // This method will update the attributes of a certain class on the database
    public function update_attributes($attributes) {
      $class = get_called_class();
      $db = DB::connect();

      $conditions = $this->style_sql_attributes($attributes);

      $update = $db->prepare('UPDATE ' . $class . ' SET' . $conditions . 'WHERE id = :id');
      $update->bindParam(':id', $this->id);
      $result = $update->execute();

      return $result;
    }





    // This method will return the last item of a table sorted by id
    public static function last() {
      $class = get_called_class();
      $db = DB::connect();

      $obj = $db->prepare('SELECT * FROM ' . $class . ' ORDER BY id DESC LIMIT 1');
      $obj->execute();

      $result = $obj->fetch(PDO::FETCH_ASSOC);

      return new $class($result);
    }




    // This class method will return the first item of a table sorted by id
    public static function first() {
      $class = get_called_class();
      $db = DB::connect();

      $obj = $db->prepare('SELECT * FROM ' . $class . ' ORDER BY id ASC LIMIT 1');
      $obj->execute();

      $result = $obj->fetch(PDO::FETCH_ASSOC);

      return new $class($result);
    }




    // This method will create a record if the caller doest exist on the database,
    // and if it does exist, it will update the record with its attributes,
    // returning wheather the action is successfull
    public function save_record($value = null) {

      // Check if there is a record already
      if ($this->does_exist($value)) {
        return $this->update_record($value);
      } else {
        return $this->create_record();
      }
    }



    // This method will create a record of an object to the database using its attributes
    // it returns true if the creating is successfull
    public function create_record() {
      $class = get_called_class();

      if ($this->does_exist()) {
        return false;
      }

      $db = DB::connect();

      $attributes = '';
      $values = '';

      // Loop through all the attributes to get the attributes and values
      foreach (get_object_vars($this) as $key => $value) {
        // We dont add the id of the object, as it is autoincremented
        if ($value == null) {
          continue;
        }

        $attributes = $attributes . " $key,";

        // We cannot add an object to sql statemnt. if the value is an object, get the id of it
        if (gettype($value) == 'object') {
          $value = $value->id;
        } elseif (gettype($value) === 'string') {
          $value = "'" . $value . "'";
        }

        $values = $values . " $value,";

      }

      // Delete the last comma
      $attributes = substr_replace($attributes, " ", -1);
      $values = substr_replace($values, " ", -1);

      $sql = $db->prepare("INSERT INTO " . $class . " (" . $attributes . ") VALUES (" . $values . ")");
      $sql->execute();

      return true;
    }




    // This method will update all the attributes of the object in the database
    // it returns true if the update is successfull
    public function update_record($value = null) {
      $class = get_called_class();

      if (!$this->does_exist()) {
        return false;
      }

      if ($value == null) {
        $value = 'id';
      }

      // If the object doesnt have the attribute passed on
      if (!$this->has_attribute($value)) {
        return false;
      }

      $db = DB::connect();

      // We style the attributes to set them
      $conditions = $this->style_sql_attributes();

      $sql = $db->prepare("UPDATE " . $class . " SET ". $conditions . " WHERE " . $value . " = :id");
      $sql->bindParam(':id', $this->$value);
      $sql->execute();

      return true;
    }




    // This function will destroy an element on the database
    public function destroy($primary_key = null) {
      $class = get_called_class();

      // If the class doesnt exist on the database
      if (!$this->does_exist()) {
        return false;
      }

      if ($primary_key == null) {
        $primary_key = 'id';
      }

      // Check if the class has the attribute $primary_key
      if (!$class::has_attribute($primary_key)) {
        return false;
      }

      $db = DB::connect();

      $destroy = $db->prepare("DELETE FROM $class WHERE $primary_key = :pk");
      $destroy->bindParam(':pk', $this->$primary_key);
      $destroy->execute();

      return true;
    }





    // This method will check if an model exists on the database
    public function does_exist($value = null) {
      $class = get_called_class();
      $db = DB::connect();

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
        $attributes = get_object_vars($this);
      }

      $class = get_called_class();
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



    // This method will update an object from an existing table database
    protected function update_object($value = null) {
      $db = DB::connect();
      $class = get_called_class();

      if ($value == null) {
        $value = 'id';
      }

      // If the object doesnt have the attribute passed on
      if (!$this->has_attribute($value)) {
        return false;
      }

      $obj = $db->prepare("SELECT * FROM " . $class . " WHERE " . $value . " = '" . $this->$value . "'");
      $obj->bindParam(':id', $this->id);
      $obj->execute();

      $obj = $obj->fetch(PDO::FETCH_ASSOC);

      // If a record doesnt match the id of the object
      if (empty($obj)) {
        return false;
      }

      // Update all the attributes
      foreach (get_object_vars($this) as $key => $value) {
        $this->$key = $obj[$key];
      }

      return true;
    }


  }

 ?>
