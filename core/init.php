<?php


  // This file will setup app the connections, the initializers, the ApplicationController and the Application model
  date_default_timezone_set('Europe/London');
  require "config/variables.php";
  require_once('config/connection.php');
  require_once('core/extras/classes/anomaly.php');
  require_once('core/flowingcollection/flowing_collection.php');
  require_once('lib/initializer.php');
  require_once('app/controllers/application_controller.php');
  require_once('app/models/application.php');
  require_once('core/app.php');


?>
