<?php
  // This file will be called from 'rails db:migrate' and it will migrate the database
  require "config/variables.php";
  require_once('config/connection.php');

  $db = Db::connect();


  // Check if the table exists
  $table = $db->prepare("SELECT count(*) AS 'count' FROM information_schema.tables WHERE table_schema = 'RainbowBooks' AND table_name = 'vinum_info'");
  $table->execute();

  // If the table doesnt exist, we create it
  if ($table->fetch(PDO::FETCH_ASSOC)['count'] == 0) {
    $table = $db->prepare("CREATE TABLE vinum_info (migration_file VARCHAR(500) NOT NULL, migrated_at TIMESTAMP)");
    $table->execute();
  }

 ?>
