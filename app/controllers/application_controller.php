<?php

  require_once('app/core/vinum_controller.php');

  // This is the ApplicationController from which the rest of controllers will inherit from
  class ApplicationController extends VinumController {

    // Anthing you put here will be executed before anything else
    public function __construct() {
      // self::user_restricted_only();
    }

  }



 ?>
