<?php

  require_once('app/core/vinum_controller.php');

  // This is the ApplicationController from which the rest of controllers will inherit from
  class ApplicationController extends VinumController {

    // Nothing cannot be seeing by guests except home and the user login
    public function __construct() {
      // self::user_restricted_only();
    }


  }



 ?>
