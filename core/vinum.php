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

      }
    }



    // This will be called every time an attribute of an object is called if that object is does not have that atribute
    public function __get($obj) {
      $class = get_called_class();

      // If the relation is has_one
      if (method_exists($this, "has_one") && in_array($obj, $this->has_one())) {
        $attr = $obj . '_id';

        // If the object does not have attribute ending in '_id'
        if (!property_exists($this, $attr)) {
          throw new Exception("$class does not have a attribute $attr", 1);
          return;
        }

        $object_class = ucfirst($obj);

        return $object_class::find($this->$attr);
      }


      // If the relation is has_many
      if (method_exists($this, "has_many") && in_array($obj, $this->has_many())) {

        // Get the singular of the word $obj. i.e. $book->categories will get 'category'
        $singular_obj = singularize($obj);

        $attr_class = ucfirst($singular_obj);

        $attr = lcfirst($class) . '_id';

        // If the table has an attribute of the called + '_id'
        if ($attr_class::has_attribute($attr)) {
          // Return a list of all objects
          return $attr_class::where("$attr = ?", $this->id);
        }

        // There might be a many_to_many, so we need to check two tables:
        // book_category and category_book
        $table1 = ucfirst($singular_obj) . "_" . $class;
        $table2 = $class . "_" . ucfirst($singular_obj);

        // TODO check if the model exist and if it exist on the DB
        
        // If the first combination has the attributes
        if ($table1::does_exist() && $table1::has_attribute($attr) && $table1::has_attribute($singular_obj . '_id')) {
          return $table1::where("$attr = ?", $this->id);
        }

        // If the second combination has the attributes
        if ($table2::does_exist() && $table2::has_attribute($attr) && $table2::has_attribute($singular_obj . '_id')) {
          return $table2::where("$attr = ?", $this->id);
        }


        // If there isnt any coincidence
        throw new Exception("has_many relation in class $class does include $singular_obj", 1);
        return;


      }


      // If the relation is belongs_to
      if (method_exists($this, "belongs_to") && in_array($obj, $this->belongs_to())) {
        $attr = lcfirst($obj) . '_id';
        $attr_class = ucfirst($obj);

        // If the table has not an attribute of the called + '_id'
        if (!$class::has_attribute($attr)) {
          throw new Exception("belongs_to relation in class $class does have $attr", 1);
          return;
        }

        // Return a list of all objects
        return $attr_class::find($this->$attr);
      }

      trigger_error("Variable $obj not declared", E_USER_WARNING);

      return NULL;
    }






    // This method will be called if a class method is not define
    public static function __callStatic($method, $argv) {
      $class = get_called_class();

      // If the method exists, return
      if (method_exists($class, $method)) {
        return;
      }

      // Check if the method exists
      if (!method_exists("Model", $method)) {
        throw new Exception("Static method $class::$method does not exist", 1);

        return;
      }

      $model = new Model($class, MODEL::STATIC_CALL);


      // call the method from the FlowingQuery and return the output
      return call_user_func_array([$model, $method], $argv);

    }





    // This method will be called if a method is not define
    public function __call($method, $argv) {
      $class = get_called_class();

      // If the method exists, return
      if (method_exists($class, $method)) {
        return;
      }

      // Check if the method exists
      if (!method_exists("Model", $method)) {
        throw new Exception("Static method $class::$method does not exist", 1);

        return;
      }

      // We pass the caller of the class plus the extra parameters

      $model = new Model($class, MODEL::INSTANCE_CALL, $this);

      // call the method from the FlowingQuery and return the output
      return call_user_func_array([$model, $method], $argv);

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
