<?php

  // This is the ApplicationController from which the rest of controllers will inherit from
  class ApplicationController {

    // Nothing cannot be seeing by guests except home and the user login
    public function __construct() {
      self::user_restricted_only();
    }

    // This action will render a view passed as a parameter
    protected function render_view($view, $params = []) {
      require_once('app/views/' . $view . '.php');
    }

    protected function no_render() {
      header('Location: index.php');
    }

    // This method will run the on costructor of certain controllers
    protected static function user_restricted_only() {
      if (!user_signed_in()) {
        redirect_to('/');
        return false;
      }

      return true;
    }


    // This method will run on the constructor of certain controllers
    protected static function guest_restricted_only() {
      if (user_signed_in()) {
        redirect_to('book/index');
        return false;
      }

      return true;
    }
  }



 ?>
