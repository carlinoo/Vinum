<?php
  // This file will be called from 'rails db:migrate' and it will migrate the database
  require "config/variables.php";
  require_once('config/connection.php');
  require_once('../core/model/migration.php');

  m = new Migration();



  $db = Db::connect();

    // Check if database exists
  if (!isset($_ENV['database_name']) && $_ENV['database_name'] != '') {
    echo "Please go to config/variables.php and create a database name";
    return;
  }
    $database_create = $db->prepare("CREATE DATABASE IF NOT EXISTS " . $_ENV['database_name']);
    $database_create->execute();

    // Check if the table exists
    $table = $db->prepare("SELECT count(*) AS 'count' FROM information_schema.tables WHERE table_schema = 'RainbowBooks' AND table_name = 'vinum_info'");
    $table->execute();

    // If the table doesnt exist, we create it
    if ($table->fetch(PDO::FETCH_ASSOC)['count'] == 0) {
      $table = $db->prepare("CREATE TABLE vinum_info (migration_file VARCHAR(500) NOT NULL, migrated_at TIMESTAMP)");
      $table->execute();
    }

    // get a string of all files in the /db/migrations folder
    $files = scandir('db/migrations');

    unset($files[0]);
    unset($files[1]);

    // get all migrations from the database
    $mg = $db->prepare("SELECT migration_file FROM vinum_info");
    $mg->execute();
    $migrations = $mg->fetchAll(PDO::FETCH_ASSOC);

    foreach ($migrations as $migration) {
      if (in_array($migration['migration_file'], $files)) {
        unset($files[array_search($migration['migration_file'], $files)]);
      }
    }

    $mig_files = array_values($files);

    // We try to execute all migrations in order of the time they were created
    try {
      foreach ($mig_files as $file) {
        $migr = $db->prepare(file_get_contents("db/migrations/" . $file));
        $migr->execute();
        $migr = $db->prepare("INSERT INTO vinum_info (migration_file) VALUES (:file)");
        $migr->bindParam(':file', $file);
        $migr->execute();
      }
    } catch (Exception $e) {
      echo "\n\nAn error happened while migrating:\n\n" . $e;
      echo "\n\n";
      return;
    }

    echo "\n\nThe migration has been successful\n\n";
    return;
 ?>
