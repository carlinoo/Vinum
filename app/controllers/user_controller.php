<?php

  class UserController extends ApplicationController {

    // Having the Constructor will avoid the ApplicationController's constructor to run
    public function __construct() {

    }

    // This method will sign out a user
    public function destroy_session() {
      if (destroy_session()) {
        redirect_to('/');
      }
    }




    // This action is where the user will be able to log in
    public function log_in() {
      // Only let do this to not logged in users
      self::guest_restricted_only();


      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // If username and password fields have been passed as params
        if (all_params_have_values_from($_POST, array('username', 'password'))) {
          $user = new User($_POST);

          $login = $user->log_in();

          // If the user was able to log in
          if ($login === true) {
            $_SESSION['username'] = $user->username;
            redirect_to('/');
          } else {
            $notice = $login;
          }
        } else {
          $notice = 'Please fill in all the form';
        }
      }

      require_once('app/views/user/log_in.php');
    }




    // This action is where the user will be able to sign up
    public function sign_up() {
      
      // Only let do this to not logged in users
      self::guest_restricted_only();

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // If the user entered all values, and $_POST keys match the User attributes except for the 'id' attribute
        if (all_params_have_values_from($_POST, User::get_column_names(), array('id'))) {

          $user = new User($_POST);

          $register = $user->register();

          // If the user registers with no problem
          if ($register === true) {
            $_SESSION['username'] = $user->username;
            redirect_to('/');
          } else {
            $notice = $register;
          }

        } else {
          // Give notice to fill all values
          $notice = "Please fill in all the form";
        }
      } // end REQUEST_METHOD

      require_once('app/views/user/sign_up.php');
    } // end sign_up()

  }

 ?>
