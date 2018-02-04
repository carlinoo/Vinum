<?php

  class Table {


    public function __construct($name) {
      $this->table_name = $name;
    }


    // Add a column to a table
    public function add_column($col_name, $data_type, $operations) {
      Migration::add_column_to($this->table_name, $col_name, $data_type, $operations);
    }


    // Drop a column from a table
    public function drop_column($col_name) {
      Migration::drop_column_from($this->table_name, $col_name);
    }

  }

 ?>
