<?php

  class ReservationController extends ApplicationController {


    // This will show the books that are reserved by the current user
    public function index() {
      $user = current_user();

      $reservations = Reservation::where("username = '" . $user->username . "'");

      require_once('app/views/reservation/index.php');
    }

  }

 ?>
