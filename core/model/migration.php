<?php

  require_once('core/model/table.php');

  class Migration {

    public function __construct()  {

    }

    // To create a table
    static public function create_table($table_name, $callback) {
      // First we create a table with no content (only the id)
      $db = DB::connect();
      $create_table = $db->prepare("CREATE TABLE $table_name( id int auto_increment primary key )");
      $create_table->execute();

      $table = new Table($table_name);

      $callback($table);

    }



    // To delete a table
    static public function drop_table($table_name) {
      $db = DB::connect();

      $run = $db->prepare("DROP TABLE $table_name");
      $run->execute();
    }


    // Add a column to a table
    static function add_column_to($table_name, $col_name, $data_type, $operations) {
      $db = DB::connect();

      $statement = $db->prepare("ALTER TABLE $table_name ADD COLUMN $col_name $data_type $operations");

      $statement->execute();
    }
  }

 ?>
