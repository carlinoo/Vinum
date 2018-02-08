<?php

  class Table {


    public function __construct($name) {
      $this->table_name = $name;
    }


    // Add a column to a table
    public function add_column($col_name, $data_type, $operations) {
      $db = DB::connect();

      $statement = $db->prepare("ALTER TABLE $this->table_name ADD COLUMN $col_name $data_type $operations");

      $statement->execute();
    }


    // Change the column name to a table
    public function change_column($original_name, $new_name, $data_type, $operations) {
      $db = DB:connect();

      $statement = $db->prepare("ALTER TABLE $this->table CHANGE  $original_name $new_name $data_type $operations");

      $statement->execute();
    }


    // Drop a column from a table
    public function drop_column($col_name) {
      $db = DB::connect();

      $statement = $db->prepare("ALTER TABLE $this->table_name DROP COLUMN $col_name");

      $statement->execute();
    }

  }

 ?>
