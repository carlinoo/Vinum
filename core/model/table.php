<?php

  class Table {


    public function __construct($name) {
      $this->table_name = $name;
    }


    public function add_column($col_name, $data_type, $operations) {
      Migration::add_column_to($this->table_name, $col_name, $data_type, $operations);
    }

  }

 ?>
