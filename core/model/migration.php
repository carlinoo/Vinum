<?php
  class Migration {

    public function __construct()  {

    }

    // To create a table
    static public function create_table($table_name, $callback) {
      // First we create a table with no content (only the id)
      $db = DB::connect();
      $create_table = $db->prepare("CREATE TABLE $table_name( id int auto_increment primary key )");
      $create_table->execute();

    }



    // To delete a table
    static public function drop_table($table_name) {
      $db = DB::conect();

      $run = $db->prepare("DROP TABLE $table_name");
      $run->execute();
    }
  }

 ?>
