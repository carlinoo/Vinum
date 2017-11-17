<?php

  class HomeController extends ApplicationController {

    public function __construct() {
      self::guest_restricted_only();
    }

    // This Action will be displayed if the user tries to access an action or controller that doesnt exist
    public function error() {
      require_once("app/views/home/error.php");
    }


    // This action is the main page of the application
    public function index() {

      require_once("app/views/home/index.php");
    }

  }


 ?>
