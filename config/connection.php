<?php
  class DB {
    private static $instance = NULL;


    private function __construct() {}

    private function __clone() {}

    public static function connect() {
      if (!isset(self::$instance)) {
        try {
          self::$instance = new PDO("mysql:host=" . $_ENV['database_host'] . ";dbname=" . $_ENV['database_name'], $_ENV['database_username'], $_ENV['database_password']);
          // set the PDO error mode to exception
          self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
        }
      }
      return self::$instance;
    }
  }
?>
